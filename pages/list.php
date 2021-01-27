<?php
// データベース接続情報文字列
$dsn = "mysql:host=localhost;dbname=productdb;charset=utf8";
$user = "productdb_admin";
$password = "admin123";
// データベース接続関連オブジェクトの初期化
$pdo = null;
$pstmt = null;
/**
 * データベースからの全件検索
 */
try {
  // データベースに接続
  $pdo = new PDO($dsn, $user, $password);
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
    $product["id"] = $record["id"];
    $product["category"] = $record["category"];
    $product["name"] = $record["name"];
    $product["price"] = $record["price"];
    $product["detail"] = $record["detail"];
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
			<td><?= $product["id"] ?></td>
			<td><?= $product["category"] ?></td>
			<td><?= $product["name"] ?></td>
			<td>&yen;4100</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="<?= $product["id"] ?>" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<?php endforeach; ?>
		<!--
		<tr>
			<td>1</td>
			<td>財布・小物入れ</td>
			<td>和財布(女性用)</td>
			<td>&yen;4100</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>2</td>
			<td>財布・小物入れ</td>
			<td>市松文様 小物入れ</td>
			<td>&yen;2500</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>3</td>
			<td>財布・小物入れ</td>
			<td>籠</td>
			<td>&yen;1900</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>4</td>
			<td>食卓用</td>
			<td>ランチョンマット</td>
			<td>&yen;900</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>5</td>
			<td>食卓用</td>
			<td>お椀</td>
			<td>&yen;900</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>6</td>
			<td>食卓用</td>
			<td>夫婦箸</td>
			<td>&yen;1800</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>7</td>
			<td>その他</td>
			<td>扇子</td>
			<td>&yen;820</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		<tr>
			<td>8</td>
			<td>その他</td>
			<td>手染め 手ぬぐい</td>
			<td>&yen;520</td>
			<td class="buttons">
				<form name="inputs">
					<input type="hidden" name="id" value="" />
					<button formaction="update.php" formmethod="post" name="action" value="update">更新</button>
					<button formaction="confirm.php" formmethod="post" name="action" value="delete">削除</button>
				</form>
			</td>
		</tr>
		-->
	</table>
	<?php endif; ?>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>