# microCMS をPHPで動かすサンプル

## 免責
書いた人はPHPプログラマではありません。「そうじゃないんだなー」があったらごめんなさい。
プルリクください。

## 設置について
全てのファイルを適当なサーバのルートにアップロードします。
`_htaccess`を`.htaccess`にリネームします（or 既存の.htaccessに追記）。
※mod_rewriteが動かないサーバでは動きません。

[動作サンプル(http://pentaprogram.cms.am/)]

## 内容
### APIの呼び出し
`template.class.php`にmicroCMS APIの呼び出しが入っています。
microCMS にてサービスを作成したあと、`_schema` にある api-news...jsonをインポートすると同じ構造のAPIが作れます（API名はnews）。
※サンプル用として作成したmicroCMSのAPIのURLとキーが記入されています

microCMSでは、作成時刻等が日本時間（JST）ではなく協定世界時（UTC）で返されるので、タイムゾーン変更も入れています。
（microCMSの入力欄は日本時間です）

### ルーティング
`.htaccess`では、アクセスがあった場合に該当するファイル／ディレクトリが存在すればそれを表示し、存在しなければindex.phpにリダイレクトするための設定を行っています。
基本的には静的HTMLで作成しつつ、サイト内の一部のディレクトリをCMS化したい場合に便利な設定です。

`/index.php`にて、該当URLにきた場合のテンプレートをそれぞれ指定しています。
`/index.tpl.php`は、いわゆるトップページのテンプレートです。
`/news/index.tpl.php`はお知らせの目次。/news/にアクセスがあった場合に表示されます。
`/news/pages.tpl.php`はお知らせ個別ページです。/news/【記事id】にアクセスがあった場合に表示されます。

ルーティングのコピペ元は[PHP による単純なルーティングの例(https://knooto.info/php-simple-routing/)]です。

### テンプレート
`/_includes` 以下に、サイト内で共通で使用するテンプレートが入っています。

`meta-`と`site-`はmicroCMS関係ないですがよく使うので入れています。
microCMS のAPIで　titleやdescription、og:image用のフィールドを追加して `meta-` でそれらを表示すると捗ります。
