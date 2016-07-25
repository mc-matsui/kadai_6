<?php
require_once( 'db.php' );
require_once( 'kadai6_2_pager.php' );
require_once( 'kadai6_2_csv.php' );
//require_once( 'kadai6_3_csv_upload.php' );
//昇順・降順設定ファイル読込
if (isset($_GET["s"]) && isset($_GET["q"]))
{
	require_once( 'kadai6_2_sort.php' );
}
else
{
	//ソート初期値（全国地方公共団体コード・昇順表示）
	$sort = "public_group_code";
	$sortby = "ASC";
	$flag = 1;
	//$q = 1;
}

$result = mysql_query("SELECT * FROM `kadai_matsui_ziplist` WHERE 1");
//結果セットの行数を取得する
$rows = mysql_num_rows($result);

if(isset($_GET["page"]))
{
	//ページリンク押した場合GET値取得、偽の場合1
	$page = $_GET["page"];
	//ページ情報をクッキーに保存
	$cookie_offset = $page;
	setcookie("cookie_offset", $cookie_offset);
	$_COOKIE["cookie_offset"] = $cookie_offset;
	$obj->pager($page, $rows);
}
else
{
	$page = 1;
	$obj->pager($page, $rows);
}

//表示件数
$limit=10;

if (isset($_COOKIE["cookie_offset"]))
{
	$page = $_COOKIE["cookie_offset"];
}

//ページ-1×表示件数（何ページ目かを設定）
$offset = ($page - 1)*$limit;

//ソート設定ファイルの昇順降順のクッキーの値がセットされているかチェック
if (isset($_COOKIE["cookie_keyS"]) && isset($_COOKIE["cookie_keyQ"]) && isset($_COOKIE["cookie_keyT"]) && isset($_COOKIE["cookie_keyA"]) && isset($_COOKIE["cookie_keyC"]))
{
	$sort = $_COOKIE["cookie_keyS"];
	$sortby = $_COOKIE["cookie_keyQ"];
	$sannkau = $_COOKIE["cookie_keyT"];
	$triangle = $_COOKIE["cookie_keyA"];
	$flag = $_COOKIE["cookie_keyC"];
}

//ソートの設定
if($rows >= $obj->page_rec)
{
	$sortlink = $obj->path .$page ."&s=".$flag;
}
else
{
	$sortlink = "?s=".$flag;
}


//CSVダウンロードチェック
if (isset($_POST["down_csv"]))
{
	if (isset($_POST["all_down_csv"]))
	{
		$csv_sql = "SELECT * FROM `kadai_matsui_ziplist`";
		createCsv($csv_sql);
	}
	else
	{
		$csv_sql = "SELECT * FROM `kadai_matsui_ziplist` ORDER BY `{$sort}` {$sortby} LIMIT {$offset},{$limit}";
		createCsv($csv_sql);
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>● 課題6_3, アップロード</title>
<link rel="stylesheet" type="text/css" href="kadai.css">
<script type="text/javascript"><!--
	//一斉削除・削除解除
	function allcheck( tf )
	{
		// チェックボックスの数
		var ElementsCount = document.sakujoform.elements.length;
		for( i=0 ; i<ElementsCount ; i++ )
		{
			// ON・OFFを切り替え
			document.sakujoform.elements[i].checked = tf;
		}
	}

	//削除コーション表示
	function disp()
	{
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
<h1>● 課題6_3, アップロード</h1>
<br>
<a href = "kadai4_1_post.php">新規挿入する</a><br>
<br>

<!-- 検索 -->
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
	検索キーワード：<input type="text" name ="name">
	<input type="submit" value="検索"><br>
	<br>
</form>
<!-- 検索 -->
<br>
<!-- CSVアップロード -->
<form name="csv_up_form" method="post" action="kadai6_3_csv_upload.php" enctype="multipart/form-data">
	CSVアップロード：
	<input type="file" name="csvfile" ><br>
	<input type="submit" name="upload_csv" value="CSVアップロード" >
	<p class="csv_check">※拡張子が『.csv』のファイルのみ対応しています。</p>
</form>
<!-- CSVアップロード -->
<br>
<!-- CSVダウンロード -->
<form name="csv_form" method="post">
	全件：
	<input type="checkbox" name="all_down_csv" value="0" >
	<input type="submit" name="down_csv" value="CSVダウンロード" >
	<p class="csv_check">※CSVファイルに全件ダウンロードする場合はチェックを入れて下さい</p>
</form>
<!-- CSVダウンロード -->
<br>
<?php
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
	            //ページ情報がクッキーに設定されているか判定
	            if (isset($_COOKIE["cookie_offset"]))
	            {
	            	//2ページ移行だったら「一番前へ」を表示
	            	if ( $_COOKIE["cookie_offset"] > 2)
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
	            	if ( $_COOKIE["cookie_offset"] > 1)
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
	            }
	            else
	            {
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
	            }
				//ページ情報がクッキーに設定されているか判定
	            if (isset($_COOKIE["cookie_offset"]))
	            {
	            	for ($i=$loop_start; $i<=$loop_end; $i++)
	            	{
						//クッキーに保存されたページ情報の一致判定
		            	if($i == $_COOKIE["cookie_offset"])
		            	{
	            			echo '<li class="active">';
	            			echo $i;
	            			echo '</li>';
		            	}
		            	else
		            	{
		            		if ($i > 0 && $obj->total_page >= $i)
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
	            }
	            else
	            {
		            for ($i=$loop_start; $i<=$loop_end; $i++)
					{
		                if ($i > 0 && $obj->total_page >= $i)
						{
		                    if($i == $obj->current_page)
		                    {
								echo '<li class="active">';
								echo $i;
								echo '</li>';
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
	            }
	            //ページ情報がクッキーに設定されているか判定
	            if (isset($_COOKIE["cookie_offset"]))
	            {
		            //最後のページ以外だったら「次へ」を表示
		            if ( $_COOKIE["cookie_offset"] < $obj->total_page)
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
		            if ( $_COOKIE["cookie_offset"] < $obj->total_page - 1)
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
	            }
	            else
	            {
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

	            }
	            ?>
	        </ul>
	    </div>
<?php
}

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
		<input type="button" value="全件チェック" onclick="allcheck(true);" />
		<input type="button" value="全件解除" onclick="allcheck(false);" />
      	<input type="button" value="削除" onclick="disp()">
      	<br>
      	<br>
		<tr>
			<th>削除</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=1">
					全国地方公共団体コード<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==1){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=2">
					旧郵便番号<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==2){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=3">
					郵便番号<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==3){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=4">
					都道府県名(半角カタカナ)<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==4){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=5">
					市区町村名(半角カタカナ)<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==5){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=6">
					町域名(半角カタカナ)<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==6){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=7">
					都道府県名(漢字)<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==7){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=8">
					市区町村名(漢字)<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==8){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=9">
					町域名(漢字)<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==9){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=10">
					一町域で複数の郵便番号か<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==10){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=11">
					小字毎に番地が起番されている町域<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==11){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=12">
					丁目を有する町域名か<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==12){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=13">
					一郵便番号で複数の町域か<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==13){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=14">
					更新確認<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==14){ print $triangle;} }?>
				</a>
			</th>
			<th>
				<a href="<?php print $sortlink; ?>&q=15">
					更新理由<?php if (isset($_COOKIE["cookie_keyT"])) { if ($_COOKIE["cookie_keyT"]==15){ print $triangle;} }?>
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