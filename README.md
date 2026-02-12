# coachtechフリマ

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)
![MySQL](https://img.shields.io/badge/mysql-%2300f.svg?style=for-the-badge&logo=mysql&logoColor=white)

ユーザー間で手軽に商品の売買ができる、プラットフォームです。

## 機能一覧

- ユーザー登録・プロフィール編集
- メール認証機能
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

## 🛠 データベース設計

> 各テーブル名をクリックすると、詳細なカラム構成を確認できます。
> <br>

<details>
<summary> 📘 <code>users</code></summary>
<br>

| カラム名              | 型              | PK  | UK  | NN  | 備考 |
| :-------------------- | :-------------- | :-: | :-: | :-: | :--- |
| **id**                | unsigned bigint |  ○  |     |  ○  |      |
| **name**              | string          |     |     |  ○  |      |
| **email**             | string          |     |  ○  |  ○  |      |
| **password**          | string          |     |     |  ○  |      |
| **remember_token**    | varchar(100)    |     |     |     |      |
| **email_verified_at** | timestamp       |     |     |     |      |

</details>

<details>
<summary> 📗 <code>items</code></summary>
<br>

| カラム名         | 型              | PK  | UK  | NN  | FK (参照先)      |
| :--------------- | :-------------- | :-: | :-: | :-: | :--------------- |
| **id**           | unsigned bigint |  ○  |     |  ○  |                  |
| **user_id**      | unsigned bigint |     |     |  ○  | `users(id)`      |
| **name**         | string          |     |     |  ○  |                  |
| **price**        | integer         |     |     |  ○  |                  |
| **brand**        | string          |     |     |     |                  |
| **description**  | text            |     |     |  ○  |                  |
| **image_url**    | string          |     |     |  ○  |                  |
| **condition_id** | unsigned bigint |     |     |  ○  | `conditions(id)` |
| **is_sold**      | boolean         |     |     |  ○  |                  |

</details>

<details>
<summary> 📕 <code>purchases</code></summary>
<br>

| カラム名      | 型              | PK  | UK  | NN  | FK (参照先) |
| :------------ | :-------------- | :-: | :-: | :-: | :---------- |
| **id**        | unsigned bigint |  ○  |     |  ○  |             |
| **user_id**   | unsigned bigint |     |     |  ○  | `users(id)` |
| **item_id**   | unsigned bigint |     |  ○  |  ○  | `items(id)` |
| **post_code** | string(8)       |     |     |  ○  |             |
| **address**   | string          |     |     |  ○  |             |
| **building**  | string          |     |     |     |             |

</details>

<details>
<summary> 📙 <code>profiles</code></summary>
<br>

| カラム名      | 型              | PK  | UK  | NN  | FK (参照先) |
| :------------ | :-------------- | :-: | :-: | :-: | :---------- |
| **id**        | unsigned bigint |  ○  |     |  ○  |             |
| **user_id**   | unsigned bigint |     |  ○  |  ○  | `users(id)` |
| **img_path**  | string          |     |     |     |             |
| **post_code** | string(8)       |     |     |     |             |
| **address**   | string          |     |     |     |             |
| **building**  | string          |     |     |     |             |

</details>

<details>
<summary> 📒 <code>favorites</code></summary>
<br>

| カラム名    | 型              | PK  | UK  | NN  | FK (参照先) |
| :---------- | :-------------- | :-: | :-: | :-: | :---------- |
| **id**      | unsigned bigint |  ○  |     |  ○  |             |
| **user_id** | unsigned bigint |     |  ○  |  ○  | `users(id)` |
| **item_id** | unsigned bigint |     |  ○  |  ○  | `items(id)` |

</details>

<details>
<summary> 📔 <code>categories</code></summary>
<br>

| カラム名 | 型              | PK  | UK  | NN  | 備考 |
| :------- | :-------------- | :-: | :-: | :-: | :--- |
| **id**   | unsigned bigint |  ○  |     |  ○  |      |
| **name** | string          |     |     |  ○  |      |

</details>

<details>
<summary> 📑 <code>category_item</code></summary>
<br>

| カラム名        | 型              | PK  | UK  | NN  | FK (参照先)      |
| :-------------- | :-------------- | :-: | :-: | :-: | :--------------- |
| **id**          | unsigned bigint |  ○  |     |  ○  |                  |
| **item_id**     | unsigned bigint |     |     |  ○  | `items(id)`      |
| **category_id** | unsigned bigint |     |     |  ○  | `categories(id)` |

</details>

<details>
<summary> 💬 <code>comments</code></summary>
<br>

| カラム名    | 型              | PK  | UK  | NN  | FK (参照先) |
| :---------- | :-------------- | :-: | :-: | :-: | :---------- |
| **id**      | unsigned bigint |  ○  |     |  ○  |             |
| **user_id** | unsigned bigint |     |     |  ○  | `users(id)` |
| **item_id** | unsigned bigint |     |     |  ○  | `items(id)` |
| **comment** | text            |     |     |  ○  |             |

</details>

<details>
<summary> ⚙️ <code>conditions</code></summary>
<br>

| カラム名      | 型              | PK  | UK  | NN  | 備考 |
| :------------ | :-------------- | :-: | :-: | :-: | :--- |
| **id**        | unsigned bigint |  ○  |     |  ○  |      |
| **condition** | string          |     |     |  ○  |      |

</details>

### ER図

![ER図](フリマアプリER図.jpg)
