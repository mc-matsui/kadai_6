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


//全国地方公共団体コード
$public_group_code = htmlspecialchars($_POST["public_group_code"], ENT_QUOTES);
//旧郵便番号
$zip_code_old = htmlspecialchars($_POST["zip_code_old"], ENT_QUOTES);
//郵便番号
$zip_code = htmlspecialchars($_POST["zip_code"], ENT_QUOTES);
//都道府県名(半角カタカナ)
$prefecture_kana = htmlspecialchars($_POST["prefecture_kana"], ENT_QUOTES);
//市区町村名(半角カタカナ)
$city_kana = htmlspecialchars($_POST["city_kana"], ENT_QUOTES);
//町域名(半角カタカナ)
$town_kana = htmlspecialchars($_POST["town_kana"], ENT_QUOTES);
//都道府県名(漢字)
$prefecture = htmlspecialchars($_POST["prefecture"], ENT_QUOTES);
//市区町村名(漢字)
$city = htmlspecialchars($_POST["city"], ENT_QUOTES);
//町域名(漢字)
$town = htmlspecialchars($_POST["town"], ENT_QUOTES);
//一町域で複数の郵便番号か
$town_double_zip_code = htmlspecialchars($_POST["town_double_zip_code"], ENT_QUOTES);
//小字毎に番地が起番されている町域か
$town_multi_address = htmlspecialchars($_POST["town_multi_address"], ENT_QUOTES);
//丁目を有する町域名か
$town_attach_district = htmlspecialchars($_POST["town_attach_district"], ENT_QUOTES);
//一郵便番号で複数の町域か
$zip_code_multi_town = htmlspecialchars($_POST["zip_code_multi_town"], ENT_QUOTES);
//更新確認
$update_check = htmlspecialchars($_POST["update_check"], ENT_QUOTES);
//更新理由
$update_reason = htmlspecialchars($_POST["update_reason"], ENT_QUOTES);

if (!preg_match("/^[0-9]{5}$/", $public_group_code)){
	print "全国地方公共団体コードは5桁の半角数字で入力してください！！";
}

if (!preg_match("/^[0-9]{3}$/", $zip_code_old)){
	print "旧郵便番号は3桁の半角数字で入力してください！！";
}

if (!preg_match("/^[0-9]{7}$/", $zip_code)){
	print "郵便番号は7桁の半角数字で入力してください！！";
}

if (!preg_match('/^[ｦ-ﾟｰ ]+$/u', $prefecture_kana)){
	print "都道府県名は半角ｶﾀｶﾅで入力してください！！";
}



//town_double_zip_code    (1=該当、0=該当せず)
if ($town_double_zip_code == 1) {
	$town_double_zip_code = "該当";
}else{
	$town_double_zip_code = "該当せず";
}

//town_multi_address      (1=該当、0=該当せず)
if ($town_multi_address == 1) {
	$town_multi_address = "該当";
}else{
	$town_multi_address = "該当せず";
}

//town_attach_district    (1=該当、0=該当せず)
if ($town_attach_district == 1) {
	$town_attach_district = "該当";
}else{
	$town_attach_district = "該当せず";
}

//zip_code_multi_town     (1=該当、0=該当せず)
if ($zip_code_multi_town == 1) {
	$zip_code_multi_town = "該当";
}else{
	$zip_code_multi_town = "該当せず";
}

//update_check            (0=変更なし、1=変更あり、2=廃止(廃止データのみ使用))
if ($update_check == 0) {
	$update_check = "変更なし";
}elseif($update_check == 1) {
	$update_check = "変更あり";
}else{
	$update_check = "廃止(廃止データのみ使用)";
}

//update_reason           (0=変更なし、1=市政・区政・町政・分区・政令指定都市施行、2=住居表示の実施、
//3=区画整理、4=郵便区調整等、5=訂正、6=廃止(廃止データのみ使用))
if ($update_reason == 0) {
	$update_reason = "変更なし";
}elseif($update_reason == 1) {
	$update_reason = "市政・区政・町政・分区・政令指定都市施行";
}elseif($update_reason == 2) {
	$update_reason = "住居表示の実施";
}elseif($update_reason == 3) {
	$update_reason = "区画整理";
}elseif($update_reason == 4) {
	$update_reason = "郵便区調整等";
}elseif($update_reason == 5) {
	$update_reason = "訂正";
}else{
	$update_reason = "廃止(廃止データのみ使用)";
}

if ($public_group_code == "" || $zip_code_old == "" || $zip_code == "" || $prefecture_kana == "" || $city_kana == "" ||
$town_kana == "" || $prefecture == "" || $city == "" || $town == "" ) {
	print <<<EOF
		<p>未入力項目があります</p><br><br>
		<form>
			<input type="button" onClick='history.back();' value="戻る">
		</form>
EOF;

}else{
	print <<<EOF

	{$public_group_code}<br><br>
	{$zip_code_old}<br><br>
	{$zip_code}<br><br>
	{$prefecture_kana}<br><br>
	{$city_kana}<br><br>
	{$town_kana}<br><br>
	{$prefecture}<br><br>
	{$city}<br><br>
	{$town}<br><br>
	{$town_double_zip_code}<br><br>
	{$town_multi_address}<br><br>
	{$town_attach_district}<br><br>
	{$zip_code_multi_town}<br><br>
	{$update_check}<br><br>
	{$update_reason}<br><br>

 <br><br><br>
 <p>上記の内容で登録完了しますか？</p>
<form action="kadai4_1_insert.php" method="post">
	<input type="hidden" name="public_group_code" value="{$public_group_code}">
	<input type="hidden" name="zip_code_old" value="{$zip_code_old}">
	<input type="hidden" name="zip_code" value="{$zip_code}">
	<input type="hidden" name="prefecture_kana" value="{$prefecture_kana}">
	<input type="hidden" name="city_kana" value="{$city_kana}">
	<input type="hidden" name="town_kana" value="{$town_kana}">
	<input type="hidden" name="prefecture" value="{$prefecture}">
	<input type="hidden" name="city" value="{$city}">
	<input type="hidden" name="town" value="{$town}">
	<input type="hidden" name="town_double_zip_code" value="{$_POST["town_double_zip_code"]}">
	<input type="hidden" name="town_multi_address" value="{$_POST["town_multi_address"]}">
	<input type="hidden" name="town_attach_district" value="{$_POST["town_attach_district"]}">
	<input type="hidden" name="zip_code_multi_town" value="{$_POST["zip_code_multi_town"]}">
	<input type="hidden" name="update_check" value="{$_POST["update_check"]}">
	<input type="hidden" name="update_reason" value="{$_POST["update_reason"]}">
	<input type="submit" value="完了">
	<input type="button" onClick='history.back();' value="戻る">
</form>


EOF;



}



?>


</body>
</html>
