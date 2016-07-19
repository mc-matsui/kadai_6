<?php

/*
$sortlist = array(
	1 => "public_group_code",
	2 => "zip_code_old",
	3 => "zip_code",
	4 => "prefecture_kana",
	5 => "city_kana",
	6 => "town_kana",
	7 => "prefecture",
	8 => "city",
	9 => "town",
	10 => "town_double_zip_code",
	11 => "town_multi_address",
	12 => "town_attach_district",
	13 => "zip_code_multi_town",
	14 => "update_check",
	15 => "update_reason",
);

foreach ($sortlist as $key => $value)
{
	switch ($_GET["q"])
	{
		case $key:
			$q = $key;
			$sort = "$value";
			break;
	}
	var_dump($sort);
}
*/


switch ($_GET["s"])
{
	case 1:
		$s=1;                //ページャに持たせるGET値
		$sortby = "ASC";     //昇順
		$flag = 2;           //降順切り替えのためフラグを2
		$triangle = "▲";    //昇順の印
		break;
	case 2:
		$s=2;                //ページャに持たせるGET値
		$sortby = "DESC";    //降順
		$flag = 1;           //昇順切り替えのためフラグを1
		$triangle = "▼";    //降順の印
		break;
}


switch ($_GET["q"])
{
	case 1:
		$q = 1;                       //ページャに持たせるGET値
		$sort = "public_group_code";  //コード番号1のDBカラムj
		break;
	case 2:
		$q = 2;
		$sort = "zip_code_old";
		break;
	case 3:
		$q = 3;
		$sort = "zip_code";
		break;
	case 4:
		$q = 4;
		$sort = "prefecture_kana";
		break;
	case 5:
		$q = 5;
		$sort = "city_kana";
		break;
	case 6:
		$q = 6;
		$sort = "town_kana";
		break;
	case 7:
		$q = 7;
		$sort = "prefecture";
		break;
	case 8:
		$q = 8;
		$sort = "city";
		break;
	case 9:
		$q = 9;
		$sort = "town";
		break;
	case 10:
		$q = 10;
		$sort = "town_double_zip_code";
		break;
	case 11:
		$q = 11;
		$sort = "town_multi_address";
		break;
	case 12:
		$q = 12;
		$sort = "town_attach_district";
		break;
	case 13:
		$q = 13;
		$sort = "zip_code_multi_town";
		break;
	case 14:
		$q = 14;
		$sort = "update_check";
		break;
	case 15:
		$q = 15;
		$sort = "update_reason";
		break;
}

//ページャに持たせるソートGET値
$pageSort = "&s=".$s ."&q=".$q;


?>

