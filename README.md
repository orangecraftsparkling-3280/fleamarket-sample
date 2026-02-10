# coachtechフリマ

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)

ユーザー間で手軽に商品の売買ができる、プラットフォームです。

## 機能一覧

- ユーザー登録・プロフィール編集
- 商品出品・編集・削除
- 商品一覧表示（販売中のみ/売却済み判定）
- 商品検索機能・カテゴリー絞り込み
- いいね機能・コメント投稿機能
- 決済機能（Stripe連携）

本プロジェクトはDockerコンテナ上で動作します。

## 環境構築

```bash
git clone https://github.com/orangecraftsparkling-3280/fleamarket-sample.git
cd confirmation-test
docker compose up -d --build
cp src/.env.example src/.env
docker compose exec php composer install
docker compose exec php php artisan key:generate
```

## .envファイル設定

```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

## .envファイル設定後実行

```bash
docker compose exec php php artisan migrate --seed
```

## 👤 テスト用ログインアカウント

環境構築後の動作確認用に、以下のユーザーでログイン可能です

| 項目               | 設定値             |
| :----------------- | :----------------- |
| **メールアドレス** | `user@example.com` |
| **パスワード**     | `password`         |

> ※ 商品を出品した状態を確認したい場合は、出品者用アカウント（`sell@my.com` / `password`）をご利用ください。

## 実行環境

### Docker環境

Docker 20.x 以上
Docker Compose 1.29.x 以上
PHP 8.x
Laravel 10.x
MySQL 8.0.26
Nginx 1.21.1

### ホストOS

macOS / Windows / Linux（Dockerが動作する環境）

### 推奨ブラウザ

Chrome / Firefox / Edge（最新バージョン）

### 接続先一覧

Webサイト: http://localhost

DB管理: http://localhost:8080

メールテスト: http://localhost:8025

### データベース設計

erDiagram
users ||--o| profiles : "1:1"
users ||--o{ items : "出品"
users ||--o{ purchases : "購入"
users ||--o{ favorites : "お気に入り"
users ||--o{ comments : "コメント投稿"

    items ||--o{ category_item : "属する"
    categories ||--o{ category_item : "含む"

    items ||--o| purchases : "1:1 (販売済)"
    items ||--o{ favorites : "被お気に入り"
    items ||--o{ comments : "被コメント"

    conditions ||--o{ items : "状態定義"
<details><summary>テーブル仕様書を表示する</summary
>users（ユーザー）カラム名型制約説明idbigintPrimary Keyユーザーの固有IDnamestringNot Nullユーザー名emailstringNot Null, UniqueメールアドレスpasswordstringNot Nullパスワードemail_verified_attimestampNullableメール認証日時remember_tokenstringNullableログイン保持用トークンprofiles（プロフィール）カラム名型制約説明idbigintPrimary KeyプロフィールIDuser_idbigintForeign Key, UniqueユーザーID（1:1）img_pathstringNullable画像パスpost_codestring(8)Not Null郵便番号addressstringNot Null住所buildingstringNullable建物名items（商品）カラム名型制約説明idbigintPrimary Key商品IDuser_idbigintForeign Key出品者IDcondition_idbigintForeign Key状態IDnamestringNot Null商品名priceintNot Null販売価格brandstringNullableブランド名descriptiontextNot Null商品説明image_urlstringNot Null商品画像URLis_soldbooleanDefault: false販売済みフラグcategories（カテゴリ）カラム名型制約説明idbigintPrimary KeyカテゴリIDnamestringNot Nullカテゴリ名category_item（中間テーブル）カラム名型制約説明item_idbigintForeign Key商品IDcategory_idbigintForeign KeyカテゴリIDpurchases（購入履歴）カラム名型制約説明idbigintPrimary Key購入IDuser_idbigintForeign Key購入者IDitem_idbigintForeign Key, Unique商品IDpost_codestring(8)Not Null配送先郵便番号addressstringNot Null配送先住所buildingstringNullable配送先建物名favorites（お気に入り）カラム名型制約説明idbigintPrimary Keyお気に入りIDuser_idbigintForeign KeyユーザーIDitem_idbigintForeign Key商品IDcomments（コメント）カラム名型制約説明idbigintPrimary KeyコメントIDuser_idbigintForeign Key投稿者IDitem_idbigintForeign Key商品IDcommenttextNot Null本文conditions（商品の状態）カラム名型制約説明idbigintPrimary Key状態IDconditionstringNot Null状態名称（新品/中古など）</details>
### ER図

![alt text](フリマアプリER図-1.jpg)
