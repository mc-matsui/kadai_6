<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>● 課題4_2, レコードを検索するページを作成する(SELECT)</title>
</head>
<body>

<h1>● 課題4_2, レコードを検索するページを作成する(SELECT)</h1>
<br>
<?php
//DB接続
require_once( 'db.php' );

//検索カラム
$kadai_search = htmlspecialchars($_POST["kadai_search"], ENT_QUOTES);
//検索テキスト
$name = htmlspecialchars($_POST["name"], ENT_QUOTES);


$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE $kadai_search LIKE '%$name%'");


//var_dump($name);die;

print  <<<EOM
		<h2>■検索結果</h2>
		<table border = "1">
			<tr>
				<th>全国地方公共団体コード</th>
				<th>旧郵便番号</th>
				<th>郵便番号</th>
				<th>都道府県名(半角カタカナ)</th>
				<th>市区町村名(半角カタカナ)  </th>
				<th>町域名(半角カタカナ)</th>
				<th>都道府県名(漢字)</th>
				<th>市区町村名(漢字)</th>
				<th>町域名(漢字)</th>
				<th>一町域で複数の郵便番号か</th>
				<th>小字毎に番地が起番されている町域か</th>
				<th>丁目を有する町域名か</th>
				<th>一郵便番号で複数の町域か</th>
				<th>更新確認</th>
				<th>更新理由</th>
			</tr>


EOM;


//結果セットの行数を取得する
$rows = mysql_num_rows($result);

if ($rows) {



while ($row = mysql_fetch_array($result)) {
	//town_double_zip_code    (1=該当、0=該当せず)
	if ($row['town_double_zip_code'] == 1) {
		$row['town_double_zip_code'] = "該当";
	}else{
		$row['town_double_zip_code'] = "該当せず";
	}

	//town_multi_address      (1=該当、0=該当せず)
	if ($row['town_multi_address'] == 1) {
		$row['town_multi_address'] = "該当";
	}else{
		$row['town_multi_address'] = "該当せず";
	}

	//town_attach_district    (1=該当、0=該当せず)
	if ($row['town_attach_district'] == 1) {
		$row['town_attach_district'] = "該当";
	}else{
		$row['town_attach_district'] = "該当せず";
	}

	//zip_code_multi_town     (1=該当、0=該当せず)
	if ($row['zip_code_multi_town'] == 1) {
		$row['zip_code_multi_town'] = "該当";
	}else{
		$row['zip_code_multi_town'] = "該当せず";
	}

	//update_check            (0=変更なし、1=変更あり、2=廃止(廃止データのみ使用))
	if ($row['update_check'] == 0) {
		$row['update_check'] = "変更なし";
	}elseif($row['update_check'] == 1) {
		$row['update_check'] = "変更あり";
	}else{
		$row['update_check'] = "廃止(廃止データのみ使用)";
	}

	//update_reason           (0=変更なし、1=市政・区政・町政・分区・政令指定都市施行、2=住居表示の実施、
	//3=区画整理、4=郵便区調整等、5=訂正、6=廃止(廃止データのみ使用))
	if ($row['update_reason'] == 0) {
		$row['update_reason'] = "変更なし";
	}elseif($row['update_reason'] == 1) {
		$row['update_reason'] = "市政・区政・町政・分区・政令指定都市施行";
	}elseif($row['update_reason'] == 2) {
		$row['update_reason'] = "住居表示の実施";
	}elseif($row['update_reason'] == 3) {
		$row['update_reason'] = "区画整理";
	}elseif($row['update_reason'] == 4) {
		$row['update_reason'] = "郵便区調整等";
	}elseif($row['update_reason'] == 5) {
		$row['update_reason'] = "訂正";
	}else{
		$row['update_reason'] = "廃止(廃止データのみ使用)";
	}



	print <<<EOM
		<tr>
			<td>{$row['public_group_code']}</td>
			<td>{$row['zip_code_old']}</td>
			<td>{$row['zip_code']}</td>
			<td>{$row['prefecture_kana']}</td>
			<td>{$row['city_kana']}</td>
			<td>{$row['town_kana']}</td>
			<td>{$row['prefecture']}</td>
			<td>{$row['city']}</td>
			<td>{$row['town']}</td>
			<td>{$row['town_double_zip_code']}</td>
			<td>{$row['town_multi_address']}</td>
			<td>{$row['town_attach_district']}</td>
			<td>{$row['zip_code_multi_town']}</td>
			<td>{$row['update_check']}</td>
			<td>{$row['update_reason']}</td>
		</tr>
EOM;




	//mb_convert_variables("UTF-8", "SJIS", $row[]);

}

print "</table>";

}else{
	print "</table>";
	print "データがありません";
}


//全件表示
print  <<<EOM
		<br><br><h2>■全件表示</h2>
		<table border = "1">
			<tr>
				<th>全国地方公共団体コード</th>
				<th>旧郵便番号</th>
				<th>郵便番号</th>
				<th>都道府県名(半角カタカナ)</th>
				<th>市区町村名(半角カタカナ)  </th>
				<th>町域名(半角カタカナ)</th>
				<th>都道府県名(漢字)</th>
				<th>市区町村名(漢字)</th>
				<th>町域名(漢字)</th>
				<th>一町域で複数の郵便番号か</th>
				<th>小字毎に番地が起番されている町域か</th>
				<th>丁目を有する町域名か</th>
				<th>一郵便番号で複数の町域か</th>
				<th>更新確認</th>
				<th>更新理由</th>
			</tr>


EOM;


$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE 1");
while ($row = mysql_fetch_array($result)) {
	//town_double_zip_code    (1=該当、0=該当せず)
	if ($row['town_double_zip_code'] == 1) {
		$row['town_double_zip_code'] = "該当";
	}else{
		$row['town_double_zip_code'] = "該当せず";
	}

	//town_multi_address      (1=該当、0=該当せず)
	if ($row['town_multi_address'] == 1) {
		$row['town_multi_address'] = "該当";
	}else{
		$row['town_multi_address'] = "該当せず";
	}

	//town_attach_district    (1=該当、0=該当せず)
	if ($row['town_attach_district'] == 1) {
		$row['town_attach_district'] = "該当";
	}else{
		$row['town_attach_district'] = "該当せず";
	}

	//zip_code_multi_town     (1=該当、0=該当せず)
	if ($row['zip_code_multi_town'] == 1) {
		$row['zip_code_multi_town'] = "該当";
	}else{
		$row['zip_code_multi_town'] = "該当せず";
	}

	//update_check            (0=変更なし、1=変更あり、2=廃止(廃止データのみ使用))
	if ($row['update_check'] == 0) {
		$row['update_check'] = "変更なし";
	}elseif($row['update_check'] == 1) {
		$row['update_check'] = "変更あり";
	}else{
		$row['update_check'] = "廃止(廃止データのみ使用)";
	}

	//update_reason           (0=変更なし、1=市政・区政・町政・分区・政令指定都市施行、2=住居表示の実施、
	//3=区画整理、4=郵便区調整等、5=訂正、6=廃止(廃止データのみ使用))
	if ($row['update_reason'] == 0) {
		$row['update_reason'] = "変更なし";
	}elseif($row['update_reason'] == 1) {
		$row['update_reason'] = "市政・区政・町政・分区・政令指定都市施行";
	}elseif($row['update_reason'] == 2) {
		$row['update_reason'] = "住居表示の実施";
	}elseif($row['update_reason'] == 3) {
		$row['update_reason'] = "区画整理";
	}elseif($row['update_reason'] == 4) {
		$row['update_reason'] = "郵便区調整等";
	}elseif($row['update_reason'] == 5) {
		$row['update_reason'] = "訂正";
	}else{
		$row['update_reason'] = "廃止(廃止データのみ使用)";
	}

	print <<<EOM
		<tr>
			<td>{$row['public_group_code']}</td>
			<td>{$row['zip_code_old']}</td>
			<td>{$row['zip_code']}</td>
			<td>{$row['prefecture_kana']}</td>
			<td>{$row['city_kana']}</td>
			<td>{$row['town_kana']}</td>
			<td>{$row['prefecture']}</td>
			<td>{$row['city']}</td>
			<td>{$row['town']}</td>
			<td>{$row['town_double_zip_code']}</td>
			<td>{$row['town_multi_address']}</td>
			<td>{$row['town_attach_district']}</td>
			<td>{$row['zip_code_multi_town']}</td>
			<td>{$row['update_check']}</td>
			<td>{$row['update_reason']}</td>
		</tr>
EOM;

	//mb_convert_variables("UTF-8", "SJIS", $row[]);

}

print "</table>";


mysql_close($link);
?>


</body>
</html>
