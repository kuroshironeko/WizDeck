<?php
require 'Adb.php';
require 'CharaParamBean.php';

// 画像データのダミーフラグ
$dummyFlg = true;

// サーバ名取得
$serverName = "http://".$_SERVER['SERVER_NAME'];
// 精霊のMAXサイズ
$spiritsNum = 5;
// 初期値
$initNum = 0;
// エラー文字（戻る）
$errorBack = "<a href='index.html' class='alert-link'>こちら</a>からデッキを作成して下さい。";
// エラー文字
$errorStr = "エラーが発生しました。<br>".$errorBack;
// エラー文字（入力なし）
$errorNoStr = "エラーが発生しました。<br>精霊の名前を1体以上入力して下さい。<br>".$errorBack;

// 精霊IDの取得
$charaData = filter_input ( INPUT_GET, "id", FILTER_DEFAULT );
// IDの配列
$charaIdArr = array();

// マナプラスの取得
$manaOnOff = false;
if (substr($charaData, -1) == "m") {
	$manaOnOff = true;
	$charaData = substr($charaData, 0, strlen($charaData)-1);
}

$charaIdArr = explode("-", $charaData);

// エラー処理
if ($charaIdArr == null || empty($charaIdArr)) {
	// 異常終了
	$url = "$serverName/WizDeck/main/error.php?status=abend";
	header("Location: {$url}");
	exit;
} else {
	$cnt = $initNum;
	foreach ($charaIdArr as $param) {
		if (strlen($param) == 0) {
			$cnt++;
		}
	}
	if ($cnt == $spiritsNum) {
		// 精霊未入力
		$url = "$serverName/WizDeck/main/error.php?status=noone";
		header("Location: {$url}");
		exit;
	}
}

// 空文字のデータを削除
$inputData = array ();
foreach ($charaIdArr as $i ) {
	if (strlen ( $i ) != 0) {
		$inputData [] = $i;
	}
}

// DB接続
$adb = new Adb ();
// キャラデータの取得
$outData = $adb->getCharaDataListById($inputData);

// キャラ自身の能力値リスト（セルフバフのみ）
$charaParamList = array();
// 全体UPリスト
$upAllParamList = array();
// キャラID
$charaIds = "";

foreach ($outData as $record) {
	// DBに該当する精霊が存在すれば計算
	if ($record != null) {
		// 個々の情報を格納するBean
		$charaParamBean = new CharaParamBean();
		// 名前のセット
		$charaParamBean->setName($record[0][0]);
		// 画像のセット
		$charaParamBean->setImg($record[0][1]);
		// HPのセット
		$charaParamBean->setUpHP($record[0][2]);
		// 攻撃のセット
		$charaParamBean->setUpAtk($record[0][3]);
		// コストのセット
		$charaParamBean->setCost($record[0][6]);
		// 属性のセット
		$charaParamBean->setAttri($record[0][4]);
		// 種族のセット
		$charaParamBean->setTribe($record[0][5]);

		// マナプラス加算
		if ($manaOnOff) {
			// HPのセット
			$charaParamBean->setUpHP($charaParamBean->getUpHP() + 200);
			$charaParamBean->setHpUpFlg(true);
			// 攻撃のセット
			$charaParamBean->setUpAtk($charaParamBean->getUpAtk() + 200);
			$charaParamBean->setAtkUpFlg(true);
		}


		// 覚醒能力の設定
		for ($i = 0; $i < 10; $i++) {
			// ","が含まれるかチェック
			if (strpos($record[0][$i + 11], ",") === false ) {
				break;
			}
			// 覚醒能力を","で区切る
			$upNum = explode(",", $record[0][$i + 11]);
			// 全体UP能力なら全体UPリストに格納
			if ($upNum != null && count($upNum) == 3) {
				$upAllParamList[] = $upNum;
			} else {
				switch ($upNum [0]) {
					case "HP" :
						$charaParamBean->setUpHP($charaParamBean->getUpHP() + $upNum [1]);
						$charaParamBean->setHpUpFlg(true);
						break;
					case "ATK" :
						$charaParamBean->setUpAtk($charaParamBean->getUpAtk() + $upNum [1]);
						$charaParamBean->setAtkUpFlg(true);
						break;
					case "COST" :
						$charaParamBean->setCost($charaParamBean->getCost() - $upNum [1]);
						$charaParamBean->setCostDwnFlg(true);
						break;
					case "BOOST" :
						$charaParamBean->setBoost($charaParamBean->getBoost() + $upNum [1]);
						break;
					case "FAST" :
						$charaParamBean->setFast($charaParamBean->getFast() + $upNum [1]);
						break;
					case "九死" :
						$charaParamBean->setCc($charaParamBean->getCc() + $upNum [1]);
						break;
					case "DROP" :
						$charaParamBean->setDrop($charaParamBean->getDrop() + $upNum [1]);
						break;
					case "EXP" :
						$charaParamBean->setExp($charaParamBean->getExp() + $upNum [1]);
						break;
					case "GOLD" :
						$charaParamBean->setGold($charaParamBean->getGold() + $upNum [1]);
						break;
					default :
						;
						break;
				}
			}
		}
		$charaParamList [] = $charaParamBean;
	}
}


// 全体UP能力値整理
foreach ($upAllParamList as $up) {
	// "/"が含まれる場合分割する（例：雷/火属性のHP100UP→雷属性のHP100UP+火属性のHP100UP）
	if (strpos($up[0], "/") !== false) {
		$tmpArr = explode("/", $up[0]);
		$upAllParamList[] = array($tmpArr[0], $up[1], $up[2]);
		$upAllParamList[] = array($tmpArr[1], $up[1], $up[2]);
	}
}
// 全体UP能力値の加算
foreach ($charaParamList as $dataBean) {
	foreach ($upAllParamList as $param) {
		// 主属性か種族が一致すれば能力を加算
		if ($param[0] == mb_substr($dataBean->getAttri(), 0, 1, "UTF-8") || $param[0] == $dataBean->getTribe()) {
			if ($param[1] == "HP") {
				$dataBean->setUpHP($dataBean->getUpHP() + $param[2]);
				$dataBean->setHpUpFlg(true);
			} else {
				$dataBean->setUpATK($dataBean->getUpATK() + $param[2]);
				$dataBean->setAtkUpFlg(true);
			}
			// 複色UPは2倍
		} else if (str_replace("+", "/", $param[0]) === $dataBean->getAttri()) {
			if ($param[1] == "HP") {
				$dataBean->setUpHP($dataBean->getUpHP() + ($param[2] * 2));
				$dataBean->setHpUpFlg(true);
			} else {
				$dataBean->setUpATK($dataBean->getUpATK() + ($param[2] * 2));
				$dataBean->setAtkUpFlg(true);
			}
		}
	}
}


?>