<?php

// 接続情報
include_once("dbcecu.php");
// インプット値
$charaname = (string) filter_input (INPUT_POST, 'charaname');
$row = null;
$show = array ();
try {
	// DB接続
	$pdo = new PDO ( "mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password );
	$pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	$pdo->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
	// SQL発行
	$stmt = $pdo->prepare ("SELECT NAME FROM kuroneko_chara WHERE NAME LIKE ?");
	$name = "%{$charaname}%";
	$stmt->bindParam(1, $name);
	$stmt->execute ();
	// 結果の取得
	$row = $stmt->fetchAll(PDO::FETCH_NUM);
} catch (Exception $e) {
	$show [] = "error";
	echo json_encode ($show);
}
// DB切断
$pdo = null;

foreach ( $row as $i ) {
	foreach ($i as $j) {
		$show [] = $j;
	}
}
echo json_encode ($show);
?>