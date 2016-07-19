<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>●課題6_1, ソートの実現</title>
<style>
	div#pagenation {
	   position: relative;
	   overflow: hidden;
	}
	div#pagenation ul {
	    position:relative;
	    left:50%;
	    float:left;
	    list-style: none;
	}
	div#pagenation li {
	    position:relative;
	    left:-50%;
	    float:left;
	}
	div#pagenation li a {
	    border:1px solid #CECECE;
	    margin: 0 3px;
	    padding:3px 7px;
	    display: block;
	    text-decoration:none;
	    color: #666666;
	    background: #fff;
	}
	div#pagenation li.active {
	    border:solid 1px #666666;
	    color: #FFFFFF;
	    background: #3399FF;
	    margin: 0 3px;
        padding: 3px 7px;
	}
	div#pagenation li a:hover {
	    border:solid 1px #666666;
	    color: #FFFFFF;
	    background: #3399FF;
	}
</style>
<script type="text/javascript"><!--
	//一斉削除・削除解除
	function allcheck( tf ) {
		// チェックボックスの数
		var ElementsCount = document.sakujoform.elements.length;
		for( i=0 ; i<ElementsCount ; i++ )
		{
			// ON・OFFを切り替え
			document.sakujoform.elements[i].checked = tf;
		}
	}

	//削除コーション表示
	function disp(){
		// 削除確認
		if(window.confirm('本当に削除しますか？'))
		{
			//「はい」選択で削除処理
			document.sakujoform.submit();
		}
		else
		{
			//「いいえ」選択で削除せず警告ダイアログを表示
			window.alert('キャンセルされました');
			return false;
		}
	}
// -->
</script>
</head>
<body>
<h1>●課題6_1, ソートの実現</h1>
<br>
<a href = "kadai4_1_post.php">新規挿入する</a><br>
<br>
<form method="post" action="kadai4_2_search.php">
		検索カラム：
		<select name="kadai_search">
			<option value="public_group_code" >全国地方公共団体コード</option>
			<option value="zip_code_old" >旧郵便番号</option>
			<option value="zip_code" >郵便番号</option>
			<option value="prefecture_kana" >都道府県名(半角カタカナ)</option>
			<option value="city_kana" >市区町村名(半角カタカナ)</option>
			<option value="town_kana" >町域名(半角カタカナ)</option>
			<option value="prefecture" >都道府県名(漢字)</option>
			<option value="city" >市区町村名(漢字)</option>
			<option value="town" >町域名(漢字)</option>
			<option value="town_double_zip_code" >一町域で複数の郵便番号か</option>
			<option value="town_multi_address" >小字毎に番地が起番されている町域か</option>
			<option value="town_attach_district" >丁目を有する町域名か</option>
			<option value="zip_code_multi_town" >一郵便番号で複数の町域か</option>
			<option value="update_check" >更新確認</option>
			<option value="update_reason" >更新理由</option>
		</select>
		検索テキスト：<input type="text" name ="name">
		<input type="submit" value="検索"><br>
		<br>
</form>
<br>
<?php
require_once( 'db.php' );
require_once( 'kadai6_1_pager.php' );

//昇順・降順設定ファイル読込

if (isset($_GET["s"]) && isset($_GET["q"]))
{
	require_once( 'kadai6_1_sort.php' );

}
else
{
	//ソート初期値（全国地方公共団体コード・昇順表示）
	$sort = "public_group_code";
	$sortby = "ASC";
	$flag = 1;
	//$q = 1;
}


//現在のURLをクッキーに保存
$setURL = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
// Cookieに値を保存する
setcookie("cookie_key", $setURL);
$_COOKIE["cookie_key"] = $setURL;
print "現在のCookie値は『". $_COOKIE["cookie_key"] ."』です。";



$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE 1");
//結果セットの行数を取得する
$rows = mysql_num_rows($result);



if(isset($_GET["page"]))
{
	//ページリンク押した場合GET値取得、偽の場合1
	$page = $_GET["page"];
	$obj->pager($page, $rows);
}
else
{
	$page = 1;
	$obj->pager($page, $rows);
}
//全てのページ数が表示するページ数より小さい場合、総ページを表示する数にする
if ($obj->total_page < $obj->show_nav)
{
	$obj->show_nav = $obj->total_page;
}


//総ページの半分
$obj->show_navh = floor($obj->show_nav / 2);
//現在のページをナビゲーションの中心にする
$loop_start = $obj->current_page - $obj->show_navh;
$loop_end = $obj->current_page + $obj->show_navh;
//現在のページが両端だったら端にくるようにする
if ($loop_start <= 0)
{
	$loop_start  = 1;
	$loop_end = $obj->show_nav;
}
if ($loop_end > $obj->total_page)
{
	$loop_start  = $obj->total_page - $obj->show_nav +1;
	$loop_end =  $obj->total_page;
}


//var_dump($rows,$obj->page_rec);

/*
 * DBのレコード数が表示レコード数を下回っていれば
 * ページリンク表示しない。
 */
if ($rows >= $obj->page_rec)
{

?>
	    <div id="pagenation">
	        <ul>
	            <?php
	            //2ページ移行だったら「一番前へ」を表示
	            if ( $obj->current_page > 2)
	            {
	            	//ソートのGET値があればページャのGET値に追加
	            	if (isset($_GET['s']) && isset($_GET['q']))
	            	{
	            		echo '<li class="prev"><a href="'. $obj->path .'1'. $pageSort .'">&laquo;</a></li>';
	            	}
	            	else
	            	{
	            		echo '<li class="prev"><a href="'. $obj->path .'1">&laquo;</a></li>';
	            	}
	            }
	            //最初のページ以外だったら「前へ」を表示
	            if ( $obj->current_page > 1)
	            {
	            	if (isset($_GET['s']) && isset($_GET['q']))
	            	{
	            		echo '<li class="prev"><a href="'. $obj->path . ($obj->current_page-1). $pageSort .'">&lsaquo;</a></li>';
	            	}
	            	else
	            	{
	            		echo '<li class="prev"><a href="'. $obj->path . ($obj->current_page-1).'">&lsaquo;</a></li>';
	            	}
	            }
	            for ($i=$loop_start; $i<=$loop_end; $i++)
				{
	                if ($i > 0 && $obj->total_page >= $i)
					{
	                    if($i == $obj->current_page)
	                    {
							echo '<li class="active">';
							echo $i;
							echo '</li>';
							setcookie("cookie_page", $i);
							$_COOKIE["cookie_page"] = $i;
	                    }
	                    else
	                    {
	                    	if (isset($_GET['s']) && isset($_GET['q']))
	                    	{
	                    		echo '<li>';
	                    		echo '<a href="'. $obj->path . $i . $pageSort.'">'.$i.'</a>';
	                    		echo '</li>';
	                    	}
	                    	else
	                    	{
		                    	echo '<li>';
		                    	echo '<a href="'. $obj->path . $i.'">'.$i.'</a>';
		                    	echo '</li>';
	                    	}
	                    }
	                }
	            }
	            //最後のページ以外だったら「次へ」を表示
	            if ( $obj->current_page < $obj->total_page)
	            {
	            	if (isset($_GET['s']) && isset($_GET['q']))
	            	{
	            		echo '<li class="next"><a href="'. $obj->path . ($obj->current_page+1). $pageSort.'">&rsaquo;</a></li>';
	            	}
	            	else
	            	{
	            		echo '<li class="next"><a href="'. $obj->path . ($obj->current_page+1).'">&rsaquo;</a></li>';
	            	}
	            }
	            //最後から２ページ前だったら「一番最後へ」を表示
	            if ( $obj->current_page < $obj->total_page - 1)
	            {
	            	if (isset($_GET['s']) && isset($_GET['q']))
	            	{
	            		echo '<li class="next"><a href="'. $obj->path . $obj->total_page. $pageSort.'">&raquo;</a></li>';
	            	}
	            	else
	            	{
	            		echo '<li class="next"><a href="'. $obj->path . $obj->total_page.'">&raquo;</a></li>';
	            	}
	            }
	            ?>
	        </ul>
	    </div>

<?php
}
if ($rows >= $obj->page_rec)
{
	print "現在、". $_COOKIE["cookie_page"] . " / ".$obj->total_page ."ページ目です。<br>";
}
//表示件数
$limit=10;
//ページ-1×表示件数（何ページ目かを設定）
$offset = ($page - 1)*$limit;




/*
//現在のURLをクッキーに保存
$setURL = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
// Cookieに値を保存する
setcookie("cookie_key", $setURL);
print "現在のCookie値は『". $_COOKIE["cookie_key"] ."』です。";
*/

/********************************************************************************************************************
 * テーブル結合
 * town_double_zip_code(一町域で複数の郵便番号か)→town_double_T(名前変テーブル)のtown_double_K(名前変カラム)
 * town_multi_address(小字毎に番地が起番されている町域か)→town_multi_T(名前変テーブル)のtown_multi_K(名前変カラム)
 * town_attach_district(丁目を有する町域名か)→town_attach_T(名前変テーブル)のtown_attach_K(名前変カラム)
 * zip_code_multi_town(一郵便番号で複数の町域か)→zip_code_multi_T(名前変テーブル)のzip_code_multi_K(名前変カラム)
 * update_check(更新確認)→update_check_T(名前変テーブル)のupdate_check_K(名前変カラム)
 * update_reason(更新理由)→update_reason_T(名前変テーブル)のupdate_reason_K(名前変カラム)
 *********************************************************************************************************************/

$sql = "SELECT  `public_group_code` ,  `zip_code_old` ,  `zip_code` ,  `prefecture_kana` ,  `city_kana` ,
`town_kana` ,  `prefecture` ,  `city` ,  `town` , `town_double_zip_code` ,  `town_multi_address` ,
`town_attach_district` ,  `zip_code_multi_town` ,  `update_check` ,  `update_reason` ,
`kadai_matsui_ziplist`.`town_double_zip_code` ,  `kadai_matsui_ziplist`.`town_multi_address` ,
 town_double_T.`show_content` AS town_double_K , town_multi_T.`show_content` AS town_multi_K ,
 town_attach_T.`show_content` AS town_attach_K , zip_code_multi_T.`show_content` AS zip_code_multi_K ,
 update_check_T.`show_content` AS update_check_K , update_reason_T.`show_content` AS update_reason_K
FROM  `kadai_matsui_ziplist`
LEFT JOIN  `kadai_matsui_town_code_mst` AS town_double_T ON
`kadai_matsui_ziplist`.`town_double_zip_code` = town_double_T.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS town_multi_T ON
`kadai_matsui_ziplist`.`town_multi_address` = town_multi_T.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS town_attach_T ON
`kadai_matsui_ziplist`.`town_attach_district` = town_attach_T.`code_key_index`
LEFT JOIN  `kadai_matsui_town_code_mst` AS zip_code_multi_T ON
`kadai_matsui_ziplist`.`zip_code_multi_town` = zip_code_multi_T.`code_key_index`
LEFT JOIN  `kadai_matsui_update_check_code_mst` AS update_check_T ON
`kadai_matsui_ziplist`.`update_check` = update_check_T.`code_key_index`
LEFT JOIN  `kadai_matsui_update_reason_code_mst` AS update_reason_T ON
`kadai_matsui_ziplist`.`update_reason` = update_reason_T.`code_key_index`
 ORDER BY `{$sort}` {$sortby} LIMIT {$offset},{$limit}";

$result = mysql_query("$sql");


?>
	<table border = "1">
	<form name="sakujoform" method="post" action="kadai4_4_delete.php">
		<input type="button" value="一斉チェック" onclick="allcheck(true);" />
		<input type="button" value="一斉解除" onclick="allcheck(false);" />
      	<input type="button" value="削除" onclick="disp()">
      	<br>
      	<br>
		<tr>
			<th>削除</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=1">
					全国地方公共団体コード<?php if (isset($_GET["q"])){ if ($_GET["q"]==1){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=2">
				旧郵便番号<?php if (isset($_GET["q"])){ if ($_GET["q"]==2){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=3">
					郵便番号<?php if (isset($_GET["q"])){ if ($_GET["q"]==3){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=4">
					都道府県名(半角カタカナ)<?php if (isset($_GET["q"])){ if ($_GET["q"]==4){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=5">
					市区町村名(半角カタカナ)<?php if (isset($_GET["q"])){ if ($_GET["q"]==5){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=6">
					町域名(半角カタカナ)<?php if (isset($_GET["q"])){ if ($_GET["q"]==6){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=7">
					都道府県名(漢字)<?php if (isset($_GET["q"])){ if ($_GET["q"]==7){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=8">
					市区町村名(漢字)<?php if (isset($_GET["q"])){ if ($_GET["q"]==8){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=9">
					町域名(漢字)<?php if (isset($_GET["q"])){ if ($_GET["q"]==9){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=10">
					一町域で複数の郵便番号か<?php if (isset($_GET["q"])){ if ($_GET["q"]==10){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=11">
					小字毎に番地が起番されている町域<?php if (isset($_GET["q"])){ if ($_GET["q"]==11){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=12">
					丁目を有する町域名か<?php if (isset($_GET["q"])){ if ($_GET["q"]==12){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=13">
					一郵便番号で複数の町域か<?php if (isset($_GET["q"])){ if ($_GET["q"]==13){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=14">
					更新確認<?php if (isset($_GET["q"])){ if ($_GET["q"]==14){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php if($rows >= $obj->page_rec){ print $obj->path .$_COOKIE["cookie_page"] ."&s=".$flag; }else{print "?s=".$flag;}?>&q=15">
					更新理由<?php if (isset($_GET["q"])){ if ($_GET["q"]==15){ print $triangle;} }?>
				</a>
			</th>
		</tr>

<?php
//DBデータをループで取得
while ($row = mysql_fetch_array($result))
{
	print <<<EOM
		<tr>
			<td><input type="checkbox" name="sakujo[]" value="{$row['zip_code']}"></td>
			<td>{$row['public_group_code']}</td>
			<td>{$row['zip_code_old']}</td>
			<td><a href = "kadai4_3_post.php?zip_code={$row['zip_code']}">{$row['zip_code']}</a></td>
			<td>{$row['prefecture_kana']}</td>
			<td>{$row['city_kana']}</td>
			<td>{$row['town_kana']}</td>
			<td>{$row['prefecture']}</td>
			<td>{$row['city']}</td>
			<td>{$row['town']}</td>
			<td>{$row['town_double_K']}</td>
			<td>{$row['town_multi_K']}</td>
			<td>{$row['town_attach_K']}</td>
			<td>{$row['zip_code_multi_K']}</td>
			<td>{$row['update_check_K']}</td>
			<td>{$row['update_reason_K']}</td>
		</tr>

EOM;
}

?>

</form></table>

<?php
mysql_close($link);
?>


</body>
</html>
