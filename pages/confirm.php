<?php
// 外部ファイルの読み込み
require_once(dirname(__dir__)."/application/dto/ProductDTO.php");
?>
<?php
// セッション開始
session_start();
// セッションからパラメータを取得
$parameters = $_SESSION["parameters"];
$action = (string) $parameters["action"];
$product = $parameters["product"];
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
					<input type="hidden" name="action" value="<?= $action ?>" />
					<input type="hidden" name="mode" value="complete" />
					<button formaction="index.php" formmethod="post">実行する</button>
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