<?php

require_once( 'db.php' );

//CSVダウンロードチェック
if (isset($_POST["upload_csv"]))
{
	setlocale(LC_ALL, 'ja_JP.SJIS');
	uploadCsv();
}

/**
 * CSVをアップロード処理
 * MySQLにデータ挿入して重複チェック
 */

function uploadCsv()
{

	//setlocale(LC_ALL, 'ja_JP.UTF-8');
	//アップロードされたファイルか
	if (is_uploaded_file($_FILES["csvfile"]["tmp_name"]))
	{
		$file_tmp_name = $_FILES["csvfile"]["tmp_name"];
		$file_name = $_FILES["csvfile"]["name"];

		//拡張子を判定
		if (pathinfo($file_name, PATHINFO_EXTENSION) != 'csv')
		{
			$err_msg = 'CSVファイルのみ対応しています。';
			print $err_msg;
			return false;
		}
		else
		{
			//ファイルをdataディレクトリに移動
			if (move_uploaded_file($file_tmp_name, "./data/" . $file_name))
			{

				chmod("./data/" . $file_name, 0644);	//後で削除できるように権限を644に
				$file = './data/'.$file_name;			//dataフォルダの中にCSVファイルを格納
				$fp   = fopen($file, "r");				//読み込みでファイルを開く
				$cnterr = array();						//重複件数の配列を初期化
				$err = "";								//重複エラーの変数を初期化

				//配列に変換する
				while (($data = fgetcsv($fp, 0, ",")) !== FALSE)
				{
					mb_convert_variables('utf-8', 'sjis-win', $data);
					//DBに挿入
					$sql = "INSERT INTO `kadai_matsui_ziplist`
						VALUES (
						'$data[0]','$data[1]','$data[2]',
						'$data[3]','$data[4]','$data[5]',
						'$data[6]','$data[7]','$data[8]',
						'$data[9]','$data[10]','$data[11]',
						'$data[12]','$data[13]','$data[14]')";
					$result = mysql_query("$sql");

					//var_dump($result);

					//何件重複しているか判定
					if (!$result)
					{
						$cnterr[] = $data;
						$err = count($cnterr);
					}
				}

				//DB削除チェック
				if ($err != 0)
				{
					print $err."件重複してます。<br><br>";
				}

				print $file_name . "をアップロードしました。";
				fclose($fp);
				//ファイルの削除
				unlink('./data/'.$file_name);
			}
			else
			{
				$err_msg = "ファイルをアップロードできません。";
				print $err_msg;
				return false;
			}
		}
	}
	else
	{
		$err_msg = "ファイルが選択されていません。";
		print $err_msg;
		return false;
	}

}

mysql_close($link);
print "<br><a href = \"kadai6_3.php\">リストページに戻る</a>";

// 					mb_convert_variables('UTF-8', 'SJIS', $asins);
