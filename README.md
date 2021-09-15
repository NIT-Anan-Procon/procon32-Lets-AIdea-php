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
レスポンスについては[API仕様書](https://docs.google.com/spreadsheets/d/1HOaOVWK5z_juemkQKkrjw3M8qarBx9vAb25yJum9RqQ/edit#gid=1900638138)に詳しく記述しています。
- API
    - User
        - CreateUser.php
        
            新しくユーザーを作成するAPI。
        - ChangeUserInfo.php

            ユーザーの名前、パスワード、アイコンを変更するAPI。
        - Login.php

            ログイン認証を行いCookieにJWTを保存するAPI。
        - Logout.php

            ログアウトするときにCookieに保存しているJWTを削除するAPI。
        - CheckLogin.php
        
            ログインしているかチェックするAPI。
        - GetUserInfo.php

            呼び出したユーザーの情報を返すAPI。
    - Room
        - CreateRoom.php

            部屋を作成し、作成者のゲーム情報を返すAPI。
        - JoinRoom.php

            部屋に参加し、参加者のゲーム情報を返すAPI。
        - GetRoomInfo.php
        
            部屋情報を返すAPI。
        - LeaveRoom.php

            部屋を抜けたのを感知したときに抜けた人のゲーム情報を削除するAPI。
        - JoinAgain.php

            ゲーム終了時にもう一度遊ぶを選択された時に実行するAPI。
    - Learn
        - Quiz.php

            画像、NGワード、類義語を取得しDBに保存するAPI。
        - Start.php

            学習モードの説明画面に必要な画像、NGワード、類義語をDBから取得し返すAPI。
    - Quiz
        - Start.php

            クイズモードの説明画面に必要な画像、NGワードを取得し返すAPI.
        - GetPicture.php

            クイズに必要な画像や説明文を返すAPI。
        - GetQuizResult.php

            獲得したポイントが多い順に並び変えてポイントとユーザー情報を返すAPI。
        -GetVoteInfo.php

            投票画面に必要な情報を返すAPI。
    - Library
        - GetLibrary.php

            ライブラリに保存された優秀作品を取得し返すAPI。
        - Good.php

            DBにいいねを加える、取り消すAPI。
    - AddPoint.php
        
        指定したプレイヤーの獲得ポイントを保存するAPI。
    - GetPoint.php
        
        指定したプレイヤーの獲得したポイントを返すAPI。
    - AddWord.php
        
        指定したユーザーの説明文をDBに保存する。
    - End.php

        結果発表画面に必要な情報を返すAPI。
- composer
- lib
    - APIで使用されるライブラリ群
