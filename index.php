<?php
/**
 * PATH_INFO の値を取得します。(PATH_INFO が無い場合、REQUEST_URI と SCRIPT_NAME をもとに PATH_INFO 相当の値を生成して返します)
 * アレンジ元： https://knooto.info/php-simple-routing/
 * @return string PATH_INFO の値 (PATH_INFO が無い場合、PATH_INFO 相当の値)
 */
function path_info()
{
  if (isset($_SERVER['PATH_INFO'])) {
    return $_SERVER['PATH_INFO'];
  } else if (isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {

    // 公開サイトのURLに書き換える
    $url = parse_url('https://pentaprogram.cms.am/' . $_SERVER['REQUEST_URI']);
    if ($url === false) return false;
    return '/' . ltrim(substr($url['path'], strlen(dirname($_SERVER['SCRIPT_NAME']))), '/');
  }
  return false;
}

/**
 * ルーティングを行います。
 *
 * @param array $map ルーティングマップの設定 ( array( array(リクエストメソッド, パスパターン, コールバック), ... )。例: array( array('GET', '/users/:id', 'var_dump') ))
 * @param string $method リクエストされたメソッド。指定しない場合は $_SERVER['REQUEST_METHOD'] が使用されます。
 * @param string $path リクエストされたパス。指定しない場合は $_SERVER['PATH_INFO'] (または $_SERVER['PATH_INFO'] 相当) が使用されます。
 * @return void
 */
function path_route(array $map, $method = null, $path = null)
{
  $method = strtoupper(is_null($method) ? $_SERVER['REQUEST_METHOD'] : $method);
  if (is_null($path)) $path = path_info();

  $code = '404';
  $codeMap = array();
  foreach ($map as $item) {
    if (count($item) < 3) continue; // ルーティング項目に値が3つ無い
    $sameMethod = ($item[0] == '*' || strtoupper($item[0]) == $method); // リクエストメソッドに対応するルーティング項目か確認

    if (preg_match('#\A[0-9]{3}\z#', $item[1])) { // エラー用 (404, 405)
      if ($sameMethod) $codeMap[$item[1]] = $item[2];
      continue;
    }
    $pattern = '#\A' . preg_replace('#:([a-zA-Z0-9_]+)#', '(?<$1>[^/]+?)', $item[1]) . '\z#'; // :id → (?<id>[^/]+?)

    if (!preg_match($pattern, $path, $matches)) continue; // パスパターンが一致しない
    if (!$sameMethod) { // メソッドが違う
      $code = '405';
      continue; // 405 の可能性あり
    }
    call_user_func($item[2], $matches); // コールバック呼び出し (メソッドとパスパターンが一致)
    return;
  }
  // エラー
  $statusMap = array('404' => 'Not Found', '405' => 'Method Not Allowed');
  header("HTTP/1.1 {$code} {$statusMap[$code]}");
  if (isset($codeMap[$code])) call_user_func($codeMap[$code], array($path));
  else echo $code;
}

function h($value, $encoding = 'UTF-8')
{
  return htmlspecialchars($value, ENT_QUOTES, $encoding);
} // HTMlエスケープ出力用
function eh($value, $encoding = 'UTF-8')
{
  echo h($value, $encoding);
} // 同上

/**
 * 以下にルーティングのルールを設定
 */
// ルーティング設定を与えてルーティングを実行します。
path_route(array(
  // indexページを返す
  array('*', '/', function () {
    include('index.tpl.php');
  }),
  // 404 を返す場合
  array('*', '404', function () {
    echo 'ページが見つかりません。';
  }),
  // 配下のページを返す
  array('GET', '/news/', function () {
    include('./news/index.tpl.php');
  }),
  // 記事URL末尾スラッシュありでアクセスできるようにする場合
  /*
   array('GET', '/news/:id/', function ($params) {
    include('./news/pages.tpl.php');
  }),
   */
  // 記事を返す
  array('GET', '/news/:id', function ($params) {
    include('./news/pages.tpl.php');
  }),
));
