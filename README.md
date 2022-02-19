## 環境

### PC

- macOS Catalina 10.15.7
- MAMPversion 6.2

### ミドルウェア系

- PHP 7.4.24
- Laravel Framework 6.20.29
- Composer version 1.10.20
- git version 2.24.3

## 環境構築

```
$ git clone https://github.com/yanagidakouya/nihon-university-kudolab.git solar_system
$ cd solar_system
$ composer install
$ cp .env.example .env
```

- .env（環境設定ファイル）のDBの接続情報は適宜自分のmysqlの接続情報を記載してください。

```
$ php artisan key:generate
$ php artisan config:clear
$ php artisan cache:clear
$ php artisan config:cache
$ php artisan migrate
$ php artisan serve
```

- サーバーが立ち上がったらブラウザで「localhost:8000」とアクセス
- 不明点やエラーが発生したら「yanagidakouya@gmail.com」までご連絡ください。