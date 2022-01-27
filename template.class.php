<?php
ini_set('display_errors', "On");

// 自分の APIの URL を記入
const YOUR_DOMAIN = 'php.microcms.io';

// 自分の API-KEY を記入
const YOUR_API_KEY = '7369999eaded4ab29771673e9df8b08c2ec8';


/***************************************
 *  microCMS + PHP
 *  アレンジ元： PHPとJSONでお手軽に量産型HTMLテンプレートを作ってみた
 *  http://www.fact-of-life.com/entry/2016/10/11/205137
 **************************************
 */
class template
{
  public $jsonData;

  public function show($tpl_file)
  {
    include("{$tpl_file}");
  }

  /***************************************
   *  データ取得（リスト）
   **************************************
   */
  public function getArticleList($api = 'news', $limit = 10, $category = null)
  {
    // APIを指定
    $param = $api . '?';

    // カテゴリー指定：2バイト文字（日本語）名称の場合もあるためURLエンコードする
    // ※カテゴリーフィールドのIDがcategoryの場合
    if ($category) {
      $param = $param . '&filters=category[contains]' . urlencode($category);
    }

    // 取得件数を指定
    $param = $param . '&limit=' . $limit;

    // JSONデータを取得、返す
    return $this->getJsonFromApi($param)["contents"];
  }

  /***************************************
   *  JSONを取得
   **************************************
   */
  private function getJsonFromApi($param)
  {
    $apiUrl = 'https://' . YOUR_DOMAIN . '/api/v1/' . $param;
    $headerData = array(
      'X-MICROCMS-API-KEY: ' . YOUR_API_KEY
    );

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData);

    $response = curl_exec($ch);
    curl_close($ch);
    $json = trim($response);

    //【データが表示されない場合】下記の一行のコメントを外してデータを見てみよう
    // var_dump($json);
    return json_decode($json, true);
  }

  /***************************************
   *  データ取得（単記事）
   **************************************
   */
  public function getArticle($api = 'news', $id = null, $draftKey = null)
  {
    // APIを指定
    $param = $api . '/';

    // 記事IDを指定
    $param = $param . $id;

    // 下書きの場合はdraftKey指定
    if ($draftKey) {
      $param = $param . '?draftKey=' . $draftKey;
    }

    // JSONデータを取得、返す
    return $this->getJsonFromApi($param);
  }

  /***************************************
   *  UTC を日本時間に変換しつつ整形
   **************************************
   */
  public function formatDate($date)
  {
    // 日本時間をセット
    $datetime = new DateTime($date);
    $datetime->setTimezone(new DateTimeZone('Asia/Tokyo'));

    // 日付を整形
    $dateText = $datetime->format('Y年n月j日');

    // 曜日変換
    $dayOfWeek = array('日', '月', '火', '水', '木', '金', '土');
    $w = $datetime->format('w');

    // 日付と曜日を返す
    return $dateText . '（' . $dayOfWeek[$w] . '）';
  }

  /***************************************
   *  プレーンテキストとリッチテキストの繰り返しフィールド
   **************************************
   */
  public function echoCustomRepeat($json, $id, $i)
  {
    if (isset($json->jsonData[$id][$i]['richText'])) {
      echo $json->jsonData[$id][$i]['richText'];
    }
    if (isset($json->jsonData[$id][$i]['plainText'])) {
      echo $json->jsonData[$id][$i]['plainText'];
    }
  }

}
