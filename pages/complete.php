<?php
// 外部ファイルの読み込み
require_once("./common/db.php");
require_once("./common/ProductDao.php");
require_once("./common/ProductDto.php");
?>
<?php
// リクエストパラメータの取得
isset($_REQUEST["action"]) ? $action = $_REQUEST["action"] : $action = "";
// セッションの取得
session_start();
$product = $_SESSION["product"];
// 処理の実行
$dao = new ProductDao();
// 処理の切り替え
if ($action === "entry") {
  $dao->inesert($product);
} elseif ($action === "update") {
  $dao->update($product);
} else {
  $dao->delete($product->getId());
}
// セッションの破棄
unset($_SESSION["product"]);
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