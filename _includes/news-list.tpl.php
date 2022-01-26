<ul>
  <?php for ($i = 0; $i < count($this->jsonData); $i++) {

    // 記事のID(=URL)を取得
    if (isset($this->jsonData[$i]['id'])) {
      $id = $this->jsonData[$i]['id'];
    }

    // 記事の更新日を取得
    if (isset($this->jsonData[$i]['revisedAt'])) {
      $date = $this->jsonData[$i]['revisedAt'];
      // 日付を日本時間に＆整形
      $date = $this->formatDate($date);
    } else {
      $date = '-';
    };

    // 記事のタイトルを取得
    if (isset($this->jsonData[$i]['title'])) {
      $title = $this->jsonData[$i]['title'];
    } else {
      $title = '無題';
    };

    ?>

    <li>
      <a href="/news/<?php echo $id ?>"><?php echo $title ?></a>
      <small><?php echo $date ?></small>
    </li>
  <?php } ?>
</ul>
