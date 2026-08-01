<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateNames = DB::table('categories')
            ->select('name')
            ->groupBy('name')
            ->havingRaw('count(*) > 1')
            ->pluck('name');

        foreach ($duplicateNames as $name) {
            $ids = DB::table('categories')->where('name', $name)->orderBy('id')->pluck('id');
            $keepId = $ids->first();
            $duplicateIds = $ids->slice(1)->all();

            if (empty($duplicateIds)) {
                continue;
            }

            DB::table('category_item')
                ->whereIn('category_id', $duplicateIds)
                ->update(['category_id' => $keepId]);

            DB::table('categories')->whereIn('id', $duplicateIds)->delete();
        }

        // category_item has no unique(item_id, category_id) constraint, so
        // repointing above may have produced duplicate pairs. Remove them.
        $duplicatePairs = DB::table('category_item')
            ->select('item_id', 'category_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('item_id', 'category_id')
            ->havingRaw('count(*) > 1')
            ->get();

        foreach ($duplicatePairs as $pair) {
            DB::table('category_item')
                ->where('item_id', $pair->item_id)
                ->where('category_id', $pair->category_id)
                ->where('id', '!=', $pair->keep_id)
                ->delete();
        }

        Schema::table('categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
