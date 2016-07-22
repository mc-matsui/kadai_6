<?php
function createCsv($csv_sql)
{
	// ファイル名
	$file_path = "export.csv";
	$csv = "";
	// CSVに出力するタイトル行
	$export_csv_title = array("全国地方公共団体コード", "旧郵便番号", "郵便番号",
			"都道府県名(半角カタカナ)", "市区町村名(半角カタカナ)" ,"町域名(半角カタカナ)" ,
			"都道府県名(漢字)" , "市区町村名(漢字)" , "町域名(漢字)" ,
			"一町域で複数の郵便番号か" ,"小字毎に番地が起番されている町域か" , "丁目を有する町域名か" ,
			"一郵便番号で複数の町域か" , "更新確認" , "更新理由"
	);
	//タイトルがある場合は1行目に挿入
	if( is_array($export_csv_title) && !empty($export_csv_title) )
	{
		$csv .= implode(",", $export_csv_title) . "\n";
	}
	// CSVに出力する内容
	$res_export = mysql_query("$csv_sql");

	//DBエラー判定
	if (!$res_export)
	{
		return false;
	}

	while ($row = mysql_fetch_assoc($res_export))
	{
		$csv .= implode(",", $row) . "\n";
	}
	//CSV出力
	if( $csv )
	{
		mb_convert_variables('SJIS', 'UTF-8', $csv);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $file_path);
		echo $csv;
		exit();
	}
	else
	{
		return false;
	}
}