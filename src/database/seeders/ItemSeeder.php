<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Condition;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::first() ?? User::factory()->create([
            'name' => 'テスト',
            'email' => 'sell@my.com',
        ]);

        User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'テストユーザー', 'password' => bcrypt('password')]
        );

        $conditionMap = Condition::pluck('id', 'condition');

        $items = [
            [
                'name' => '自家焙煎コーヒー豆 200g',
                'price' => 900,
                'brand' => '豆香焙煎舎',
                'description' => '深煎りが香ばしい自家焙煎のブレンドコーヒー豆です。',
                'image_url' => asset('images/items/coffee-beans.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => '自家焙煎コーヒー豆 500g',
                'price' => 2000,
                'brand' => '豆香焙煎舎',
                'description' => 'ご家庭用にたっぷり使える500gの自家焙煎コーヒー豆です。',
                'image_url' => asset('images/items/coffee-beans.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'ハンドドリップドリッパー',
                'price' => 1600,
                'brand' => null,
                'description' => '円錐形で抽出しやすい陶器製のドリッパーです。',
                'image_url' => asset('images/items/dripper.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [10]
            ],
            [
                'name' => 'コーヒーミル(手挽き)',
                'price' => 4000,
                'brand' => '珈琲道具店',
                'description' => 'じっくり豆を挽ける手動のコーヒーミルです。',
                'image_url' => asset('images/items/coffee-grinder.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'アンティーク風コーヒーミル(装飾用)',
                'price' => 4500,
                'brand' => null,
                'description' => 'インテリアとして飾れるアンティーク風のコーヒーミルです。',
                'image_url' => asset('images/items/coffee-grinder.svg'),
                'condition' => '状態が悪い',
                'category_ids' => [3]
            ],
            [
                'name' => 'フレンチプレス',
                'price' => 2500,
                'brand' => null,
                'description' => 'コーヒーも紅茶も淹れられるガラス製フレンチプレスです。',
                'image_url' => asset('images/items/french-press.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'ドリップバッグコーヒー10個セット',
                'price' => 1000,
                'brand' => null,
                'description' => '手軽に本格コーヒーが楽しめるドリップバッグ10個セットです。',
                'image_url' => asset('images/items/drip-bags.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [10]
            ],
            [
                'name' => 'コーヒー豆保存キャニスター',
                'price' => 1800,
                'brand' => null,
                'description' => '豆の鮮度を保つ密閉式の保存キャニスターです。',
                'image_url' => asset('images/items/canister.svg'),
                'condition' => 'やや傷や汚れあり',
                'category_ids' => [3]
            ],
            [
                'name' => 'コーヒー豆保存キャニスター(大)',
                'price' => 2200,
                'brand' => null,
                'description' => 'たっぷり入る大容量サイズの保存キャニスターです。',
                'image_url' => asset('images/items/canister.svg'),
                'condition' => '良好',
                'category_ids' => [3]
            ],
            [
                'name' => 'ステンレスタンブラー',
                'price' => 800,
                'brand' => null,
                'description' => '保温・保冷どちらにも使える丈夫なタンブラーです。',
                'image_url' => asset('images/items/tumbler.svg'),
                'condition' => '状態が悪い',
                'category_ids' => [10]
            ],
            [
                'name' => '陶器のマグカップ',
                'price' => 1200,
                'brand' => null,
                'description' => '手に馴染む厚みのある陶器製マグカップです。',
                'image_url' => asset('images/items/mug.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [3]
            ],
            [
                'name' => '陶器のマグカップ(ペア)',
                'price' => 2200,
                'brand' => null,
                'description' => 'お揃いで使えるペアのマグカップセットです。',
                'image_url' => asset('images/items/mug.svg'),
                'condition' => '良好',
                'category_ids' => [3]
            ],
            [
                'name' => 'ミルクピッチャー',
                'price' => 1500,
                'brand' => null,
                'description' => 'ラテアートの練習にも使えるミルクピッチャーです。',
                'image_url' => asset('images/items/pitcher.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'ケトル(細口)',
                'price' => 3500,
                'brand' => null,
                'description' => '注ぎ口が細くドリップに最適なケトルです。',
                'image_url' => asset('images/items/kettle.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [10]
            ],
            [
                'name' => 'コーヒーフィルター(ペーパー)100枚',
                'price' => 400,
                'brand' => null,
                'description' => '未使用に近いペーパーフィルター100枚セットです。',
                'image_url' => asset('images/items/filter.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'デミタスカップ&ソーサー',
                'price' => 1600,
                'brand' => null,
                'description' => 'エスプレッソにぴったりな小ぶりのカップ&ソーサーです。',
                'image_url' => asset('images/items/demitasse.svg'),
                'condition' => 'やや傷や汚れあり',
                'category_ids' => [3]
            ],
            [
                'name' => 'コーヒーサーバー(ガラス製)',
                'price' => 1300,
                'brand' => null,
                'description' => '目盛り付きで使いやすいガラス製のコーヒーサーバーです。',
                'image_url' => asset('images/items/server.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'カフェ風メニューボード(黒板)',
                'price' => 1800,
                'brand' => null,
                'description' => 'カフェの入り口に置いていた黒板タイプのメニューボードです。',
                'image_url' => asset('images/items/chalkboard.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [3]
            ],
            [
                'name' => 'ラテアート風マグカップ',
                'price' => 1000,
                'brand' => null,
                'description' => 'ラテアート柄がプリントされたかわいいマグカップです。',
                'image_url' => asset('images/items/latte-cup.svg'),
                'condition' => '良好',
                'category_ids' => [3]
            ],
            [
                'name' => 'コーヒーの木の鉢植え',
                'price' => 2500,
                'brand' => null,
                'description' => 'お部屋を彩るコーヒーの木の鉢植えです。',
                'image_url' => asset('images/items/plant.svg'),
                'condition' => '良好',
                'category_ids' => [3]
            ],
            [
                'name' => 'カフェ風ウォールシェルフ',
                'price' => 3200,
                'brand' => null,
                'description' => 'マグカップや小物を飾れる木製のウォールシェルフです。',
                'image_url' => asset('images/items/shelf.svg'),
                'condition' => 'やや傷や汚れあり',
                'category_ids' => [3]
            ],
            [
                'name' => 'カフェ風エプロン',
                'price' => 2000,
                'brand' => null,
                'description' => 'カフェ店員風のシンプルなキャンバス地エプロンです。',
                'image_url' => asset('images/items/apron.svg'),
                'condition' => 'やや傷や汚れあり',
                'category_ids' => [1]
            ],
            [
                'name' => 'カフェ風エプロン(ロングタイプ)',
                'price' => 2400,
                'brand' => null,
                'description' => '丈長でしっかり汚れをカバーできるエプロンです。',
                'image_url' => asset('images/items/apron.svg'),
                'condition' => '良好',
                'category_ids' => [1]
            ],
            [
                'name' => '手作りコースター5枚セット',
                'price' => 600,
                'brand' => 'なし',
                'description' => '麻紐で編んだハンドメイドのコースター5枚セットです。',
                'image_url' => asset('images/items/coasters.svg'),
                'condition' => '良好',
                'category_ids' => [11]
            ],
            [
                'name' => 'ハンドメイド刺繍コースター',
                'price' => 800,
                'brand' => 'なし',
                'description' => '一枚ずつ刺繍を施したハンドメイドのコースターです。',
                'image_url' => asset('images/items/coasters.svg'),
                'condition' => '良好',
                'category_ids' => [11]
            ],
            [
                'name' => 'カフェ店員風バンダナ',
                'price' => 700,
                'brand' => null,
                'description' => 'エプロンと合わせて使えるカフェ店員風のバンダナです。',
                'image_url' => asset('images/items/bandana.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [1]
            ],
            [
                'name' => '麻素材のカフェトートバッグ',
                'price' => 1500,
                'brand' => null,
                'description' => '通気性の良い麻素材のカフェ風トートバッグです。',
                'image_url' => asset('images/items/tote-bag.svg'),
                'condition' => 'やや傷や汚れあり',
                'category_ids' => [1]
            ],
            [
                'name' => 'クッキー型セット(カフェ柄)',
                'price' => 900,
                'brand' => null,
                'description' => 'マグカップ型など、カフェ柄が可愛いクッキー型セットです。',
                'image_url' => asset('images/items/cookie-cutter.svg'),
                'condition' => '良好',
                'category_ids' => [10]
            ],
            [
                'name' => 'コーヒーカップ型キャンドル',
                'price' => 700,
                'brand' => null,
                'description' => 'コーヒーカップの形をしたハンドメイドキャンドルです。',
                'image_url' => asset('images/items/candle.svg'),
                'condition' => '良好',
                'category_ids' => [11]
            ],
            [
                'name' => 'カフェ屋さんごっこおもちゃセット',
                'price' => 1800,
                'brand' => null,
                'description' => 'カップやレジがセットになったカフェ屋さんごっこ遊びおもちゃです。',
                'image_url' => asset('images/items/toy-set.svg'),
                'condition' => '目立った傷や汚れなし',
                'category_ids' => [14]
            ],
        ];

        foreach ($items as $itemData) {
            $categoryIds = $itemData['category_ids'] ?? [];
            unset($itemData['category_ids']);

            $conditionName = $itemData['condition'];
            $itemData['condition_id'] = $conditionMap[$conditionName];
            unset($itemData['condition']);

            $itemData['user_id'] = $seller->id;

            $item = Item::create($itemData);

            if (!empty($categoryIds)) {
                $item->categories()->attach($categoryIds);
            }
        }
    }
}
