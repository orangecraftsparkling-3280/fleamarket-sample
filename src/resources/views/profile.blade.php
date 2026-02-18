@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__content">
    <div class="profile-form__heading">
        <h1>プロフィール設定</h1>
    </div>

    <form class="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="image-upload-section">
            <div class="image-preview-container">
                @if($user->profile && $user->profile->img_path)
                <img src="{{ asset('storage/' . $user->profile->img_path) }}" alt="ユーザー画像" class="profile-circle">
                @else
                <div class="default-circle"></div>
                @endif
            </div>
            <div class="file-input-wrapper">
                <label for="image" class="btn-select-image">画像を選択する</label>
                <input type="file" name="img_path" id="image" accept="image/*" class="hidden-file-input">
            </div>
        </div>
        <div class="form__error">
            @error('img_path') {{ $message }} @enderror
        </div>

        {{-- ユーザー名 --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ユーザー名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">
                </div>
                <div class="form__error">
                    @error('name') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">郵便番号</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="post_code" value="{{ old('post_code', $user->profile->post_code ?? '') }}">
                </div>
                <div class="form__error">
                    @error('post_code') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">住所</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="address" value="{{ old('address', $user->profile->address ?? '') }}">
                </div>
                <div class="form__error">
                    @error('address') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">建物名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="building" value="{{ old('building', $user->profile->building ?? '') }}">
                </div>
                <div class="form__error">
                    @error('building') {{ $message }} @enderror
                </div>
            </div>
        </div>

        <div class="form__button">
            <button class="form__button-submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const previewContainer = document.querySelector('.image-preview-container');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(event) {
                let img = previewContainer.querySelector('img');

                if (!img) {
                    previewContainer.innerHTML = '';
                    img = document.createElement('img');
                    img.classList.add('profile-circle');
                    previewContainer.appendChild(img);
                }
                img.src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush