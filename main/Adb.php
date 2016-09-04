<?php
class Adb {

	// 名前から精霊のListを返す
	public function getCharaDataListByName($names) {

		// 接続情報
		include_once("dbcecu.php");
		$row = null;
		$charaDataList = array ();
		try {
			// DB接続
			$pdo = new PDO ( "mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password );
			$pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$pdo->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );

			foreach ( $names as $i ) {
				// SQL発行
				$stmt = $pdo->prepare ( "SELECT NAME, IMG, HP, ATK, ATTRIBUTE, TRIBE, COST, AS1, SS1, AS2, SS2,
					POTENTIAL1, POTENTIAL2, POTENTIAL3, POTENTIAL4, POTENTIAL5, POTENTIAL6, POTENTIAL7,
					POTENTIAL8, POTENTIAL9, POTENTIAL10, AWAKE1, AWAKE2, AWAKE3, AWAKE4, CHARA_ID FROM kuroneko_chara
					WHERE NAME = ?" );
				$stmt->bindParam ( 1, $i );
				$stmt->execute ();
				// 結果の取得
				$row = $stmt->fetchAll ( PDO::FETCH_NUM );
				$charaDataList [] = $row;
			}
		} catch ( Exception $e ) {
			echo "Connection failed: " . $e->getMessage ();
		}
		// DB切断
		$pdo = null;
		return $charaDataList;
	}


	// IDから精霊のListを返す
	public function getCharaDataListById($charaIds) {
		// 接続情報
		include_once("dbcecu.php");
		$row = null;
		$charaDataList = array ();
		try {
			// DB接続
			$pdo = new PDO ( "mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password );
			$pdo->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$pdo->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );

			foreach ( $charaIds as $i ) {
				// SQL発行
				$stmt = $pdo->prepare ( "SELECT NAME, IMG, HP, ATK, ATTRIBUTE, TRIBE, COST, AS1, SS1, AS2, SS2,
					POTENTIAL1, POTENTIAL2, POTENTIAL3, POTENTIAL4, POTENTIAL5, POTENTIAL6, POTENTIAL7,
					POTENTIAL8, POTENTIAL9, POTENTIAL10, AWAKE1, AWAKE2, AWAKE3, AWAKE4 FROM kuroneko_chara
					WHERE CHARA_ID = ?" );
				$stmt->bindParam ( 1, $i );
				$stmt->execute ();
				// 結果の取得
				$row = $stmt->fetchAll ( PDO::FETCH_NUM );
				$charaDataList [] = $row;
			}
		} catch ( Exception $e ) {
			echo "Connection failed: " . $e->getMessage ();
		}
		// DB切断
		$pdo = null;
		return $charaDataList;

	}
}

?>
