# coachtechフリマ

ユーザー間で手軽に商品の売買ができる、プラットフォームです。

#技術スタック
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

# .envファイル設定後実行

```bash
docker compose exec php php artisan migrate --seed
```

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

### ER図

![ER図](er-diagram.jpg)
