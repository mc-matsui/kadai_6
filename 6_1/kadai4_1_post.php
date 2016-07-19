<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題4_1, レコードを挿入するページを作成する(INSERT)</title>
</head>
<body>

<h1>●課題4_1, レコードを挿入するページを作成する(INSERT)</h1>

<form method="post" action="kadai4_1_check.php">
	全国地方公共団体コード ：<input type="text" name ="public_group_code"><br>
	<br>
	旧郵便番号：<input type="text" name ="zip_code_old"><br>
	<br>
	郵便番号 ：<input type="text" name ="zip_code"><br>
	<br>
	都道府県名(半角カタカナ) ：<input type="text" name ="prefecture_kana"><br>
	<br>
	市区町村名(半角カタカナ：<input type="text" name ="city_kana"><br>
	<br>
	町域名(半角カタカナ)：<input type="text" name ="town_kana"><br>
	<br>
	都道府県名(漢字)：<input type="text" name ="prefecture"><br>
	<br>
	市区町村名(漢字)：<input type="text" name ="city"><br>
	<br>
	町域名(漢字)：<input type="text" name ="town"><br>
	<br>
	一町域で複数の郵便番号か：
		<select name="town_double_zip_code">
			<option value="0" >該当せず</option>
			<option value="1" >該当</option>
		</select>
	<br><br>
	小字毎に番地が起番されている町域か：
		<select name="town_multi_address">
			<option value="0" >該当せず</option>
			<option value="1" >該当</option>
		</select>
	<br><br>
	丁目を有する町域名か：
		<select name="town_attach_district">
			<option value="0" >該当せず</option>
			<option value="1" >該当</option>
		</select>
	<br><br>
	一郵便番号で複数の町域か：
		<select name="zip_code_multi_town">
			<option value="0" >該当せず</option>
			<option value="1" >該当</option>
		</select>
	<br><br>
	更新確認：
		<select name="update_check">
			<option value="0" >変更なし</option>
			<option value="1" >変更あり</option>
			<option value="2" >廃止(廃止データのみ使用)</option>
		</select>
	<br><br>
	更新理由：
		<select name="update_reason">
			<option value="0" >変更なし</option>
			<option value="1" >市政・区政・町政・分区・政令指定都市施行</option>
			<option value="2" >住居表示の実施</option>
			<option value="3" >区画整理</option>
			<option value="4" >郵便区調整等</option>
			<option value="5" >訂正</option>
			<option value="6" >廃止(廃止データのみ使用)</option>
		</select>
	<br><br>
	<input type="submit" value="確認画面へ">
	<input type="reset" value="リセット">
</form>


</body>
</html>
