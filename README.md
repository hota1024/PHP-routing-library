# このライブラリについて
このライブラリはPHPでルーティングを行うためのライブラリです。

ただしこのライブラリはまだ問題があります。

- 処理速度の問題
- 機能が少ない問題
- セキュリティが万全ではない

以上のことを承知の上ご利用ください。

# ライセンス
Copyright (c) 2017 hota1024

This software is released under the MIT License.
http://opensource.org/licenses/mit-license.php

本ライブラリはMITライセンスの元公開しています。

# 使用方法
このライブラリをダウンロードし解答します。

その後中のRoutingLibraryの中のファイルとフォルダを全部プロジェクトのトップディレクトリに配置してください。

そしてトップディレクトリにhtaccess.txtを置いて、名前と拡張子を.htaccessに変えるか、.htaccessを作り以下のコードを貼り付けてください。

```
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule ^(.*)$ router/router.php [QSA,L]
</IfModule>
IndexIgnore ./router/*
```

あとはルートを定義して終わりです。

ファイル構成は
<img width="510" alt="2017-10-30 19 10 30" src="https://user-images.githubusercontent.com/24543982/32165524-6b1f7526-bda6-11e7-9b4c-b28e34171a4e.png">
こんな感じです。
ルーティングの定義や設定は以下のリファレンスで解説しています。

# リファレンス

## ファイル概要
|ファイル名|概要|
|:----|:----:|
|[ProjectFolder]|プロジェクトフォルダ|
|[ProjectFolder]/.htaccess|ルーティング用の.htaccess|
|[ProjectFolder]/router/|ルーティングライブラリのソース|
|[ProjectFolder]/router/conf.php|ルートの定義などを記述するファイル|
|[ProjectFolder]/router/const.php|ルーティングライブラリの定数定義ファイル|
|[ProjectFolder]/router/router.php|ルーティングライブラリの本体|

## conf.php
conf.phpでは以下のことを記述します。

- プロジェクトファイルの絶対パス
- URLの最後の/の扱い
- ルートの定義

### プロジェクトファイルの絶対パス
このライブラリにはプロジェクトファイルの絶対パスが必要です。
パスには２つあります。
`$notlocal_file_pass`が非ローカル環境のパス（例：http://example.com/）
`$local_file_pass`がローカル環境のパス（例：http://localhost:8888/）

### リダイレクト
このライブラリではURLの最後に/を付けるかつけないかでリダイレクトすることができます。
conf.phpの`$redirect`に`ROUTER_REDIRECT_SLASH`か`ROUTER_REDIRECT_NOSLASH`か`ROUTER_REDIRECT_NO`を代入してください。

|変数|内容|
|:----|:---:|
|ROUTER_REDIRECT_SLASH|URLの最後に/がついていない場合/をつけてリダイレクトします|
|ROUTER_REDIRECT_NOSLASH|URLの最後に/がついている場合/を外してリダイレクトします|
|ROUTER_REDIRECT_NO|URLの最後の/の有無にかかわらずにURLにアクセスします|

### ルート定義
ルートはconf.phpの`$routes`配列に定義していきます。

例えばabout.phpにabout/でアクセスしたい場合は以下のように書きます。

```php
$routes = [
  ['/about/','about.php']
];
```

このように書くことができます。

$routesの中にルート情報を配列で入れることでできます。

構文としては`['アクセスされたときのパス','アクセスするパス']`となります。

#### 変数

たとえば`users/[username]/profile/`が`userprofile.php?id=[username]`のようにアクセスしたい場合です。

$routesの中に`['/users/$user_id/profile/','userprofile.php?id=$user_id']`のようにパスの途中に$を書くことで変数が使えます。それがアクセスするパスの同じ変数の値に置換されてアクセスできます。
