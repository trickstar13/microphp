# microCMS をPHPで動かすサンプル

## 免責
書いた人はPHPプログラマではありません。「そうじゃないんだなー」があったらごめんなさい。  
プルリクください。  

## 設置について
全てのファイルを適当なサーバのルートにアップロードします。  
`_htaccess`を`.htaccess`にリネームします（or 既存の.htaccessに追記）。  
※mod_rewriteが動かないサーバでは動きません。  

動作サンプル：  
https://pentaprogram.cms.am/

## 内容
### スキーマ
まず、microCMS にてサービスを作成します。
次に、リスト形式のAPIを作成し、`_schema` にある api-news...jsonをインポートすると同じ構造のAPIが作れます（API名はnews）。  

### APIの呼び出し
`template.class.php`にmicroCMS APIの呼び出しが入っています。
YOUR_DOMAIN と YOUR_API_KEY をそれぞれ自分のmicroCMSの情報に書き換えます。
※サンプルとして作成済みのmicroCMSのAPIのURLとキーが記入されています

### 画面プレビュー設定
microCMS上の画面プレビュー設定には`https://あなたのドメイン/news/{CONTENT_ID}?draftKey={DRAFT_KEY}`を入力します

### ルーティング
`/index.php`にて、該当URLにきた場合のテンプレートをそれぞれ指定しています。  
この`/index.php`の「公開サイトのURLに書き換える」部分を公開サイトURLに書き換えてください（たいていの場合は書き換えなくても動きますが…）

`.htaccess`では、アクセスがあった場合に該当するファイル／ディレクトリが存在すればそれを表示し、存在しなければindex.phpにリダイレクトするための設定を行っています。  
基本的には静的HTMLで作成しつつ、サイト内の一部のディレクトリをCMS化したい場合に便利な設定です。

### テンプレート
`/index.tpl.php`は、いわゆるトップページのテンプレートです。  
`/news/index.tpl.php`はお知らせの目次。/news/にアクセスがあった場合に表示されます。  
`/news/pages.tpl.php`はお知らせ個別ページです。/news/【記事id】にアクセスがあった場合に表示されます。  

`/_includes` 以下に、サイト内で共通で使用するであろうテンプレートパーツが入っています。

`meta-`と`site-`はmicroCMS関係ないですがよく使うので入れています。  
microCMS のAPIで　titleやdescription、og:image用のフィールドを追加して `meta-` でそれらを表示すると捗ります。

## その他
microCMSでは、作成時刻等が日本時間（JST）ではなく協定世界時（UTC）で返されるので、タイムゾーン変更も入れています。  
（microCMS側の入力欄は日本時間になってます）  
