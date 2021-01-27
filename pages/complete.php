<?php
// 外部ファイルの読み込み
require_once("./common/db.php");
require_once("./common/Product.php");
?>
<?php
// リクエストパラメータの取得
isset($_REQUEST["action"]) ? $action = $_REQUEST["action"] : $action = "";
isset($_REQUEST["id"]) ? $id = $_REQUEST["id"] : $id = 0;
isset($_REQUEST["category"]) ? $category = $_REQUEST["category"] : $category = "";
isset($_REQUEST["name"]) ? $name = $_REQUEST["name"] : $name = "";
isset($_REQUEST["price"]) ? $price = $_REQUEST["price"] : $price = "";
isset($_REQUEST["detail"]) ? $detail = $_REQUEST["detail"] : $detail = "";
// 商品クラスのインスタンス化
$product = new Product($id, $category, $name, $price, $detail);
// データベース接続関連オブジェクトを初期化
$pdo = null;
$pstmt = null;
try {
  // データベース接続オブジェクトを取得
  $pdo = connectDB();
  // リクエストパラメータのactionキーによって処理を分岐
  if ($action === "entry") {
    /* 新規追加処理 */
    // 実行するSQLを設定
    $sql = "insert into product (category, name, price, detail) values (:category, :name, :price, :detail)";
    // リクエストパラメータをプレースホルダに設定する連想配列の設定
    $params = [];
    $params[":category"] = $product->getCategory();
    $params[":name"] = $product->getName();
    $params[":price"] = $product->getPrice();
    $params[":detail"] = $product->getDetail();
    // SQl実行オブジェクトを取得
    $pstmt = $pdo->prepare($sql);
    // SQLを実行
    $pstmt->execute($params);
  }
  
} catch (PDOException $e) {
  echo $e->getMessage();
  die;
} finally {
  unset($pstmt);
  unset($pdo);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>商品データベース</title>
	<link rel="stylesheet" href="../assets/css/style.css" />
</head>
<body>
<header>
	<h1>商品データベース</h1>
</header>
<main id="complete">
	<h2>商品の完了</h2>
	<p>処理を完了しました。</p>
	<p><a href="top.php">トップページに戻る</a></p>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>