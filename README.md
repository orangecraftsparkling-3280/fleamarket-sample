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

Gemini said
README.mdの「データベース設計」セクションを完成させるためのコードを作成しました。

Markdownで表が表示されない主な原因は、HTMLタグ（<details>）の直後に空行がないことや、全角スペースが混じっていることです。以下のコードはそれらをすべて修正し、GitHub等で綺麗に表示されるように最適化しています。

そのままコピーして、### データベース設計 の下に貼り付けてください。

データベース設計
<details> <summary>テーブル仕様書を表示する</summary>

users（ユーザー）
カラム名	型	制約	説明
id	bigint	Primary Key	ユーザーの固有ID
name	string	Not Null	ユーザー名
email	string	Not Null, Unique	メールアドレス（ログイン用）
email_verified_at	timestamp	Nullable	メール認証日時
password	string	Not Null	パスワード（ハッシュ化）
remember_token	string	Nullable	ログイン保持用トークン
profiles（プロフィール）
カラム名	型	制約	説明
id	bigint	Primary Key	プロフィールID
user_id	bigint	Foreign Key, Unique	users.id への外部参照（1:1）
img_path	string	Nullable	プロフィール画像へのパス
post_code	string	Not Null	郵便番号
address	string	Not Null	住所
building	string	Nullable	建物名
items（商品）
カラム名	型	制約	説明
id	bigint	Primary Key	商品ID
user_id	bigint	Foreign Key	出品者のユーザーID
condition_id	bigint	Foreign Key	conditions.id への外部参照
name	string	Not Null	商品名
price	int	Not Null	販売価格
brand	string	Nullable	ブランド名
description	text	Not Null	商品の説明文
image_url	string	Not Null	商品画像のURL
is_sold	boolean	Default: false	販売済みフラグ
comments（コメント）
カラム名	型	制約	説明
id	bigint	Primary Key	コメントID
user_id	bigint	Foreign Key	コメント投稿者ID
item_id	bigint	Foreign Key	対象の商品ID
comment	text	Not Null	コメント本文
favorites（お気に入り）
カラム名	型	制約	説明
id	bigint	Primary Key	お気に入りID
user_id	bigint	Foreign Key	いいねしたユーザーID
item_id	bigint	Foreign Key	いいねされた商品ID
categories（カテゴリ）
カラム名	型	制約	説明
id	bigint	Primary Key	カテゴリID
name	string	Not Null	カテゴリ名
category_item（中間テーブル）
カラム名	型	制約	説明
item_id	bigint	Foreign Key	itemsテーブルとの紐付け
category_id	bigint	Foreign Key	categoriesテーブルとの紐付け
purchases（購入履歴）
カラム名	型	制約	説明
id	bigint	Primary Key	購入ID
user_id	bigint	Foreign Key	購入者のユーザーID
item_id	bigint	Foreign Key, Unique	購入された商品ID
post_code	string	Not Null	配送先郵便番号
address	string	Not Null	配送先住所
building	string	Nullable	配送先建物名
conditions（商品の状態）
カラム名	型	制約	説明
id	bigint	Primary Key	状態ID
condition	string	Not Null	商品の状態（新品、中古など）
</details>

### ER図
![alt text](フリマアプリER図-1.jpg)