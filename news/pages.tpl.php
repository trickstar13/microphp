<!doctype html>
<html lang="ja">
<head>
<meta name="viewport" content="width=device-width">
<meta charset="UTF-8">
<?php
  $dirname = dirname(__FILE__);
  include($dirname . './../template.class.php');
  $template = new template();

  // 下書きの場合はdraftkeyを取得
  $draftKey = $_GET['draftKey'];

  // API名と記事IDを指定して ニュース記事を取得
  $articleData = $template->getArticle('news', $params['id'], $draftKey);
  $template->jsonData = $articleData;
?>
<title><?php echo $template->jsonData['title']; ?> | お知らせ</title>
<?php include($dirname . './../_includes/meta-header.php'); ?>
</head>
<body>
<?php include($dirname . './../_includes/site-header.php'); ?>

<main class="main-area">
  <article class="main-box">
    <div class="content-heading">
      <h1><?php echo $template->jsonData['title'] ?></h1>
    </div>
    <div class="content-body">

      <!-- プレーンorリッチテキストの繰り返しを出力 -->
      <?php for ($i = 0; $i < count($template->jsonData['body']); $i++) {
        $template->echoCustomRepeat($template, 'body', $i);
      } ?>

      <aside class="content-footer">
        更新日：<?php echo $template->formatDate($template->jsonData['revisedAt']) ?>
      </aside>
    </div>
  </article>
  <nav class="content-footer">
    <a href="/news">お知らせ一覧</a>
  </nav>
</main>

<?php include($dirname . './../_includes/site-footer.php'); ?>
</body>
</html>
