<?php
// 外部ファイルの読み込み
require_once("./common/db.php");
require_once("./common/ProductDao.php");
require_once("./common/ProductDto.php");
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
  $product = new ProductDto($id, $category, $name, $price, $detail);
} else {
	// 削除処理の場合：商品のID検索
	$dao = new ProductDao();
	$product = $dao->findById($id);
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