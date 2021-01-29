<?php
// 外部ファイルの読み込み
require_once(dirname(__DIR__)."/application/dto/ProductDTO.php");
?>
<?php
// セッション開始
session_start();
// セッションからパラメータを取得
$parameters = $_SESSION["parameters"];
// セッションの破棄
$action = (string) $parameters["action"];
$product = $parameters["product"];

/*
// リクエストパラメータを取得
isset($_REQUEST["action"]) ? $action = $_REQUEST["action"] : $action = "";
isset($_REQUEST["id"]) ? $id = $_REQUEST["id"] : $id = 0;
// データベース接続関連オブジェクトの初期化
$pdo = null;
$pstmt = null;
$dao = new ProductDao();
$product = $dao->findById($id);
*/
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
<main id="update">
	<h2>商品の更新</h2>
	<p class="note">商品名と価格は<em>必須入力</em>です。</p>
	<form class="update">
	  <?php if (!is_null($product)): ?>
		<table class="form">
			<tr>
				<th>商品ID</th>
				<td>
					<?= $product->getId() ?>
					<input type="hidden" name="id" value="<?= $product->getId() ?>">
				</td>
			</tr>
			<tr>
				<th>カテゴリ</th>
				<td>
					<select name="category">
						<option value="財布・小物入れ" <?PHP if ($product->getCategory() === "財布・小物入れ") echo "selected"; ?>>財布・小物入れ</option>
						<option value="食卓用" <?PHP if ($product->getCategory() === "食卓用") echo "selected"; ?>>食卓用</option>
						<option value="その他" <?PHP if ($product->getCategory() === "その他") echo "selected"; ?>>その他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>商品名</th>
				<td><input type="text" name="name" value="<?= $product->getName() ?>"></td>
			</tr>
			<tr>
				<th>価格</th>
				<td><input type="number" name="price" value="<?= $product->getPrice() ?>">円</td>
			</tr>
			<tr>
				<th>商品説明</th>
				<td><textarea name="detail" id="" cols="30" rows="3"><?= $product->getDetail() ?></textarea></td>
			</tr>
			<tr class="buttons">
				<td colspan="2">
					<input type="hidden" name="action" value="<?= $action ?>" />
					<input type="hidden" name="mode" value="confirm" />
					<button formaction="index.php" formmethod="post">確認画面へ</button>
				</td>
			</tr>
		</table>
		<?php endif; ?>
	</form>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>