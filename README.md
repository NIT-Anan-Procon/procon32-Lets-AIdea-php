# procon32 Lets AIdea php
> Let's AIdeaを動作させるのに必要な情報を提供するAPI群。

## 動作環境
- Ubuntu 20.04.2 LTS
- PHP 7.4.x
- Apache 2.4.x
- MariaDB 10.3.x

## 初期設定

```
git clone https://github.com/NIT-Anan-Procon/procon32_Lets_AIdea_php.git
cd procon32_Lets_AIdea_php.git
composer install
```

## PHP CS Fixer について

> このリポジトリには[PHP-CS-Fixer](https://cs.symfony.com/)がComposerからインストールされています。

### 使い方

```
./vendor/bin/php-cs-fixer fix ./
```

## ファイル構成
- API
    - CreateRoom.php
        - 部屋を作成し、作成者のゲーム情報を返すAPI。
    - JoinRoom.php
        - 部屋に参加し、参加者のゲーム情報を返すAPI。
    - LeaveRoom.php
        - 部屋を抜けたのを感知したときに抜けた人のゲーム情報を削除するAPI。
    - DeleteRoom.php
        - ゲームが終了し部屋を削除するAPI。
    - Login.php
        - ログイン認証を行いCookieにJWTを保存するAPI。
    - Logout.php
        - ログアウトするときにCookieに保存しているJWTを削除するAPI。
    - AddPoint.php
        - 指定したプレイヤーの獲得ポイントを保存するAPI。
    - GetPoint.php
        - 指定したプレイヤーの獲得したポイントを返すAPI。
    - AddWord.php
        - 指定したユーザーの説明文をDBに保存する。
- composer
- lib
    - APIで使用されるライブラリ群
