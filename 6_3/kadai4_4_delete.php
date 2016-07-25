<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>● 課題4_4, レコードを削除するページを作成する(DELETE)</title>
</head>
<body>

<h1>● 課題4_4, レコードを削除するページを作成する(DELETE)</h1>
<br>
<?php
//DB接続
require_once( 'db.php' );

if (isset($_POST["sakujo"]))
{
	//件数の配列を初期化（$cnt→成功、$cnterr→失敗）
	$cnt = array();
	$cnterr = array();

	//DB削除(郵便番号の値を取得してループ)
	foreach ($_POST["sakujo"] as $zip_code)
	{
			$sql = "DELETE FROM  `kadai_matsui_ziplist` WHERE  `zip_code` =  '$zip_code'";
			$result = mysql_query("$sql");

			//mysql_query成功判定
			if ($result) {
				//$cnt += mysql_affected_rows();
				//削除成功をカウント
				$cnt[] = $zip_code;
				$total = count($cnt);
			}else {
				//削除失敗をカウント
				$cnterr[] = $zip_code;
				$err = count($cnterr);

			}

			//array_push($cnt, $zip_code);
			//$cnt[] = $zip_code;

	}
	//$total = count($cnt);


	$rst = mysql_query($sql, $link);

	//DB削除チェック
	if ($rst)
	{
		print $total."件削除しました。<br><br>";
	}
	else
	{
		print $err."件削除できませんでした。<br><br>";
	}
}
else
{
	print "削除するリストを選択してください!!<br><br>";
	print "削除できませんでした。<br><br>";
}


print "<a href = \"kadai6_3.php\">リストページに戻る</a>";


mysql_close($link);
?>


</body>
</html>
