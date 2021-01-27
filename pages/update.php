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
		<table class="form">
			<tr>
				<th>商品ID</th>
				<td>
					4
					<input type="hidden" name="id" value="4">
				</td>
			</tr>
			<tr>
				<th>カテゴリ</th>
				<td>
					<select name="category">
						<option value="財布・小物入れ" >財布・小物入れ</option>
						<option value="食卓用" selected>食卓用</option>
						<option value="その他" >その他</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>商品名</th>
				<td><input type="text" name="name" value="ランチョンマット"></td>
			</tr>
			<tr>
				<th>価格</th>
				<td><input type="number" name="price" value="900">円</td>
			</tr>
			<tr>
				<th>商品説明</th>
				<td><textarea name="detail" id="" cols="30" rows="3">お椀やお箸によく似合う、和風のランチョンマットです。</textarea></td>
			</tr>
			<tr class="buttons">
				<td colspan="2">
					<button formaction="confirm.php" formmethod="post" name="action" value="update">確認画面へ</button>
				</td>
			</tr>
		</table>
	</form>
</main>
<footer>
	<div id="copyright">&copy; 2021 The Applied Course of Web System Development.</div>
</footer>
</body>
</html>