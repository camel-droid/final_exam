<?php
// 外部ファイルの読み込み
require_once("./common/db.php");
require_once("./common/Product.php");
?>
<?php
// リクエストパラメータを取得
isset($_REQUEST["action"]) ? $action = $_REQUEST["action"] : $action = "";
isset($_REQUEST["id"]) ? $id = $_REQUEST["id"] : $id = 0;

$product = null;
if ($action !== "delete") {
  // リクエストパラメータを取得
  isset($_REQUEST["category"]) ? $category = $_REQUEST["category"] : $category = "";
  isset($_REQUEST["name"]) ? $name = $_REQUEST["name"] : $name = "";
  isset($_REQUEST["price"]) ? $price = $_REQUEST["price"] : $price = "";
  isset($_REQUEST["detail"]) ? $detail = $_REQUEST["detail"] : $detail = "";
  // 商品クラスのインスタンス化
  $product = new Product($id, $category, $name, $price, $detail);
} else {
  // 削除処理の場合：商品のID検索
  // データベース接続関連オブジェクトの初期化
  $pdo = null;
  $pstmt = null;
  try {
    // データベース接続オブジェクトを取得
    $pdo = connectDB();
    // SQLの設定
    $sql = "select * from product where id = ?";
    // SQL実行オブジェクトを取得
    $pstmt = $pdo->prepare($sql);
    // プレースホルダにリクエストパラメータを設定
    $pstmt->bindValue(1, $id);
    // SQLの実行と結果セットの取得
    $pstmt->execute();
    $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
    $product = null;
    if (count($records) > 0) {
      $category = $records[0]["category"];
      $name = $records[0]["name"];
      $price = $records[0]["price"];
      $detail = $records[0]["detail"];
      $product = new Product($id, $category, $name, $price, $detail);
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
    die;
  } finally {
    unset($pstmt);
    unset($pdo);
  }
}

// セッションに登録
session_start();
$_SESSION["product"] = $product;
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
<main id="confirm">
	<h2>商品の確認</h2>
	<p>以下の情報で更新します。</p>
	<table class="form">
	  <?php if ($action !== "entry"): ?>
		<tr>
			<th>商品ID</th>
			<td><?= $product->getId() ?></td>
		</tr>
		<?php endif; ?>
		<tr>
			<th>カテゴリ</th>
			<td><?= $product->getCategory() ?></td>
		</tr>
		<tr>
			<th>商品名</th>
			<td><?= $product->getName() ?></td>
		</tr>
		<tr>
			<th>価格</th>
			<td><?= $product->getPrice() ?></td>
		</tr>
		<tr>
			<th>商品説明</th>
			<td><?= $product->getDetail() ?></td>
		</tr>
		<tr class="buttons">
			<td colspan="2">
				<form name="inputs">
					<button formaction="complete.php" formmethod="post" name="action" value="<?= $action ?>">実行する</button>
				</form>
			</td>
		</tr>
	</table>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>