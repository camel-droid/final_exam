<?php
// 外部ファイルの読み込み
require_once("./common/db.php");
require_once("./common/Product.php");
?>
<?php
// データベース接続関連オブジェクトの初期化
$pdo = null;
$pstmt = null;
/**
 * データベースからの全件検索
 */
try {
  // データベースに接続
  $pdo = connectDB();
  // 実行するSQLを設定
  $sql = "select * from product";
  // SQL実行オブジェクトを取得
  $pstmt = $pdo->prepare($sql);
  // SQLの実行と結果セットの取得
  $pstmt->execute();
  $records = $pstmt->fetchAll(PDO::FETCH_ASSOC);
  // 結果セットを商品の配列に格納
  $products = [];
  foreach ($records as $record) {
    $id = $record["id"];
    $category = $record["category"];
    $name = $record["name"];
    $price = $record["price"];
    $detail = $record["detail"];
    $product = new Product($id, $category, $name, $price, $detail);
    $products[] = $product;
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
<main id="list">
	<h2>商品一覧</h2>
	<?php if (count($products) > 0): ?>
	<table class="list">
		<tr>
			<th>商品ID</th>
			<th>カテゴリ</th>
			<th>商品名</th>
			<th>価格</th>
			<th></th>
		</tr>
		<?php foreach ($products as $product): ?>
		<tr>
			<td><?= $product->getId() ?></td>
			<td><?= $product->getCategory() ?></td>
			<td><?= $product->getName() ?></td>
			<td>&yen;<?= $product->getPrice() ?></td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="<?= $product->getId() ?>" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>