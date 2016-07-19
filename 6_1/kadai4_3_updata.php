<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題4_3, レコードを上書きするページを作成する(UPDATE)</title>
</head>
<body>

<h1>●課題4_3, レコードを上書きするページを作成する(UPDATE)</h1>
<br>
<?php
//DB接続
$link = mysql_connect("localhost","root","3212");
mysql_query("SET NAMES utf8",$link);
if (!$link)
{
	die("接続できませんでした" .mysql_error());
}
$db = mysql_select_db("test" , $link);
if (!$db)
{
	die("データベース接続エラーです。" .mysql_error());
}

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


//DB編集

$sql = "UPDATE  `kadai_matsui_ziplist` SET
`public_group_code` =  '$public_group_code',
`zip_code_old` =  '$zip_code_old',
`zip_code` =  '$zip_code',
`prefecture_kana` =  '$prefecture_kana',
`city_kana` =  '$city_kana',
`town_kana` =  '$town_kana',
`prefecture` =  '$prefecture',
`city` =  '$city',
`town` =  '$town',
`town_double_zip_code` =  '$town_double_zip_code',
`town_multi_address` =  '$town_multi_address',
`town_attach_district` =  '$update_check',
`zip_code_multi_town` =  '$zip_code_multi_town',
`update_check` =  '$update_check',
`update_reason` = '$update_reason' WHERE  `zip_code` =  '$zip_code'";

mysql_query("$sql");


$rst = mysql_query($sql, $link);

if ($rst)
{
	print "1件更新完了しました<br><br>";
}
else
{
	print "更新失敗しました<br><br>";
}


print "<a href = \"kadai6_1.php\">リストページに戻る</a>";


mysql_close($link);
?>


</body>
</html>
