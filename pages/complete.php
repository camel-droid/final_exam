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
// セッションの破棄
unset($_SESSION["product"]);
$dao = new ProductDao();
try {
  // データベース接続オブジェクトを取得
  $pdo = connectDB();
  // リクエストパラメータのactionキーによって処理を分岐
  if ($action === "entry") {
    /* 新規追加処理 */
    $dao->insert($product);
  } elseif ($action === "update") {
    /* 更新処理 */
    // 実行するSQLを設定
    $sql = "update product set category = :category, name = :name, price = :price, detail = :detail where id = :id";
    // リクエストパラメータをプレースホルダに設定する連想配列の設定
    $params = [];
    $params[":id"] = $product->getId();
    $params[":category"] = $product->getCategory();
    $params[":name"] = $product->getName();
    $params[":price"] = $product->getPrice();
    $params[":detail"] = $product->getDetail();
    // SQl実行オブジェクトを取得
    $pstmt = $pdo->prepare($sql);
    // SQLを実行
    $pstmt->execute($params);
  } elseif ($action === "delete") {
    /* 削除処理 */
    // 実行するSQLを設定
    $sql = "delete from product where id = ?";
    // SQl実行オブジェクトを取得
    $pstmt = $pdo->prepare($sql);
    // プレースホルダにリクエストパラメータを設定
    $pstmt->bindValue(1, $product->getId());
    // SQLを実行
    $pstmt->execute();
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