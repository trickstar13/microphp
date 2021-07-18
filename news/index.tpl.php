<!doctype html>
<html lang="ja">
<head>
<meta name="viewport" content="width=device-width">
<meta charset="UTF-8">
<?php
  $dirname = dirname(__FILE__);
  include($dirname . './../template.class.php');
?>
<?php include($dirname . './../_includes/meta-header.php'); ?>
<title>お知らせ</title>
</head>
<body>
<?php include($dirname . './../_includes/site-header.php'); ?>

<main class="main-area">
  <div class="main-box">
    <h1>お知らせ一覧</h1>
    <?php

    $template = new template();

    // API名を指定して ニュース記事の一覧 を取得
    // 件数を増減する場合：getArticleList('news', 20)
    // カテゴリーを指定する場合：getArticleList('news', 20, 'カテゴリー名')
    $articleList = $template->getArticleList('news', 20);
    $template->jsonData = $articleList;

    // 記事リストのテンプレートを表示（トップページと共用）
    $frontpageToc = $dirname . './../_includes/news-list.tpl.php';
    $template->show($frontpageToc);

    ?>
</main>

<?php include($dirname . './../_includes/site-footer.php'); ?>
</body>
</html>
