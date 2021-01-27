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
		<tr>
			<th>商品ID</th>
			<td>4</td>
		</tr>
		<tr>
			<th>カテゴリ</th>
			<td>食卓用</td>
		</tr>
		<tr>
			<th>商品名</th>
			<td>ランチョンマット</td>
		</tr>
		<tr>
			<th>価格</th>
			<td>900</td>
		</tr>
		<tr>
			<th>商品説明</th>
			<td>お椀やお箸によく似合う、和風のランチョンマットです。</td>
		</tr>
		<tr class="buttons">
			<td colspan="2">
				<form name="inputs">
					<button formaction="complete.php" formmethod="post" name="action" value="execute">実行する</button>
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