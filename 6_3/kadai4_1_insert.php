<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題4_1, レコードを挿入するページを作成する(INSERT)</title>
</head>
<body>

<h1>●課題4_1, レコードを挿入するページを作成する(INSERT)</h1>
<br>
<?php
//DB接続
require_once( 'db.php' );

//全国地方公共団体コード
$public_group_code = $_POST["public_group_code"];
//旧郵便番号
$zip_code_old = $_POST["zip_code_old"];
//郵便番号
$zip_code = $_POST["zip_code"];
//都道府県名(半角カタカナ)
$prefecture_kana = $_POST["prefecture_kana"];
//市区町村名(半角カタカナ)
$city_kana = $_POST["city_kana"];
//町域名(半角カタカナ)
$town_kana = $_POST["town_kana"];
//都道府県名(漢字)
$prefecture = $_POST["prefecture"];
//市区町村名(漢字)
$city = $_POST["city"];
//町域名(漢字)
$town = $_POST["town"];
//一町域で複数の郵便番号か
$town_double_zip_code = $_POST["town_double_zip_code"];
//小字毎に番地が起番されている町域か
$town_multi_address = $_POST["town_multi_address"];
//丁目を有する町域名か
$town_attach_district = $_POST["town_attach_district"];
//一郵便番号で複数の町域か
$zip_code_multi_town = $_POST["zip_code_multi_town"];
//更新確認
$update_check = $_POST["update_check"];
//更新理由
$update_reason = $_POST["update_reason"];


//DBに挿入
mysql_query("INSERT INTO `kadai_matsui_ziplist`(
		`public_group_code`, `zip_code_old`, `zip_code`,
		`prefecture_kana`, `city_kana`, `town_kana`,
		`prefecture`, `city`, `town`,
		`town_double_zip_code`, `town_multi_address`, `town_attach_district`,
		`zip_code_multi_town`, `update_check`, `update_reason`)
		VALUES (
		'$public_group_code','$zip_code_old','$zip_code',
		'$prefecture_kana','$city_kana','$town_kana',
		'$prefecture','$city','$town',
		'$town_double_zip_code','$town_multi_address','$town_attach_district',
		'$zip_code_multi_town','$update_check','$update_reason')"
);


print "1行登録完了しました<br><br>";

print "<a href = \"kadai6_3.php\">リストページに戻る</a>";








mysql_close($link);
?>


</body>
</html>
