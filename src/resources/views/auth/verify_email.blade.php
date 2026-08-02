@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify.css') }}">
@endsection

@section('content')
@php
$email = auth()->user()->email;
$domain = substr(strrchr($email, "@"), 1);

$mailerUrls = [
'gmail.com' => ['name' => 'Gmail', 'url' => 'https://mail.google.com/'],
'yahoo.co.jp' => ['name' => 'Yahoo!メール', 'url' => 'https://mail.yahoo.co.jp/'],
'icloud.com' => ['name' => 'iCloudメール', 'url' => 'https://www.icloud.com/mail'],
'outlook.jp' => ['name' => 'Outlook', 'url' => 'https://outlook.live.com/'],
'outlook.com' => ['name' => 'Outlook', 'url' => 'https://outlook.live.com/'],
'hotmail.com' => ['name' => 'Hotmail', 'url' => 'https://outlook.live.com/'],
];

$service = $mailerUrls[$domain] ?? [
'name' => 'メールソフト',
'url' => 'https://www.google.com/search?q=' . urlencode($domain . ' ログイン')
];

if (config('mail.mailers.smtp.host') === 'mailhog') {
$service = ['name' => 'MailHog', 'url' => config('services.mailhog.url')];
}
@endphp

<div class="verify-form__content">
    <h1>登録していただいたメールアドレスに認証メールを送付しました。</h1>
    <h2>メール認証を完了してください。</h2>

    @if (session('message'))
    <div class="alert-success">
        {{ session('message') }}
    </div>
    @endif

    <div class="verify-nav">
        <div class="btn-group">
            <a href="{{ $service['url'] }}" target="_blank" class="btn-primary-action">
                認証はこちらから
            </a>
        </div>
    </div>

    <div class="resend-section">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-text-link" {{ session('message') ? 'disabled' : '' }}>
                {{ session('message') ? '送信済み' : '認証メールを再送する' }}
            </button>
        </form>

        @if (session('message'))
        <a href="{{ route('verification.notice') }}" class="retry-link">
            もう一度送信する
        </a>
        @endif
    </div>
</div>
@endsection