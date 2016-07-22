<?php
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

//昇順・降順情報をクッキーに保存
$cookieValueS = $sort;
setcookie("cookie_keyS", $cookieValueS);
$_COOKIE["cookie_keyS"] = $cookieValueS;

//カラムGET値をクッキーに保存
$sannkau = $_GET["q"];
$cookieValueT = $sannkau;
setcookie("cookie_keyT", $cookieValueT);
$_COOKIE["cookie_keyT"] = $cookieValueT;

//昇順・降順マーク『▲▼』をクッキーに保存
$cookieValueA = $triangle;
setcookie("cookie_keyA", $cookieValueA);
$_COOKIE["cookie_keyA"] = $cookieValueA;

//フラグ情報をクッキーに保存
$cookieValueC = $flag;
setcookie("cookie_keyC", $cookieValueC);
$_COOKIE["cookie_keyC"] = $cookieValueC;



//昇順・降順対象のカラム情報をクッキーに保存
$cookieValueQ = $sortby;
setcookie("cookie_keyQ", $cookieValueQ);
$_COOKIE["cookie_keyQ"] = $cookieValueQ;



//ページャに持たせるソートGET値
$pageSort = "&s=".$s ."&q=".$q;

