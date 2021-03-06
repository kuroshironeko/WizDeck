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

// 精霊データの取得
$charaData = filter_input ( INPUT_POST, "charaData", FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

// マナプラスの取得
$manaOnOff = (bool) filter_input ( INPUT_POST, "mana", FILTER_VALIDATE_BOOLEAN);

// エラー処理
if ($charaData == null || empty($charaData)) {
	// 異常終了
	$url = "$serverName/WizDeck/main/error.php?status=abend";
	header("Location: {$url}");
	exit;
} else {
	$cnt = $initNum;
	foreach ($charaData as $param) {
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
foreach ($charaData as $i ) {
	if (strlen ( $i ) != 0) {
		$inputData [] = $i;
	}
}

// DB接続
$adb = new Adb ();
// キャラデータの取得
$outData = $adb->getCharaDataListByName( $inputData );

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
		// IDのセット
		$charaParamBean->setCharaId($record[0][25]);

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
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="description" content="">
<meta name="author" content="">
<title>黒ウィズ　デッキ作成</title>

<link type="text/css" rel="stylesheet" href="../css/jquery-ui.min.css" />

<!-- Bootstrap core CSS -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<link href="../css/ie10-viewport-bug-workaround.css" rel="stylesheet">

<!-- Custom styles -->
<link type="text/css" rel="stylesheet" href="../css/common.css" />

<!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
<script src="../js/ie-emulation-modes-warning.js"></script>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

</head>
<body>

<body>
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<h1>黒ウィズ　デッキ作成シミュレータ</h1>
				<h2>結果</h2>
				<div class="table-responsive">
<?php

// 該当精霊が居なかったらエラー
if (count ( $charaParamList ) == 0) {
	echo $errorNoHitStr;
	// 警告終了
	$url = "$serverName/WizDeck/main/error.php?status=nohit";
	header("Location: {$url}");
	return;
} else {
	echo  "<table class='table table-bordered table-condensed'>";
	echo 	"<tr>";
	for ( $i = 1; $i <=  sizeof($charaParamList); $i++) {
		// No
		echo "<td>No$i</td>";
		$charaIds = $charaIds.$charaParamList[$i-1]->getCharaId();
		if ($i != sizeof($charaParamList)) {
			$charaIds = $charaIds."-";
		} else {
			if ($manaOnOff) {
				$charaIds = $charaIds."m";
			}
		}
	}
	echo "</tr>";
	echo 	"<tr>";
	foreach ( $charaParamList as $dataBean ) {
		if ($dataBean == null) {
			echo "<td>img</td>";
		} else {
			// 画像
			if (!$dummyFlg) {
				echo "<td><img src='" . $dataBean->getImg () . "' height='300'></td>";
			} else {
				echo "<td><img src='../img/dummy.jpg'></td>";
			}
		}
	}
	echo "</tr>";
	echo "<tr>";
	foreach ( $charaParamList as $dataBean ) {
		$cnt = 0;
		// 名前
		echo "<td id='param" . $cnt . "Name'>" . $dataBean->getName () . "</td>";
	}
	echo "</tr>";
	echo "<tr>";
	foreach ( $charaParamList as $dataBean ) {
		$class = "";
		$manaEfe = "";
		if ($dataBean->isHpUpFlg()) {
			$class = "empha ";
		}
		if ($manaOnOff) {
			$class = $class."firstFlash";
			$manaEfe = "<span class='secondFlash mana'>+200</span>";
		}
		// HP
		echo "<td id='param".$cnt."Hp'>HP:<span class='$class'>".$dataBean->getUpHp()."</span>".$manaEfe."</td>";
	}
	echo "</tr>";
	echo "<tr>";
	foreach ( $charaParamList as $dataBean ) {
		$class = "";
		$manaEfe = "";
		if ($dataBean->isAtkUpFlg()) {
			$class = "empha ";
		}
		if ($manaOnOff) {
			$class = $class."firstFlash";
			$manaEfe = "<span class='secondFlash mana'>+200</span>";
		}
		// 攻撃
		echo "<td id='param".$cnt."Atk'>攻撃:<span class='$class'>".$dataBean->getUpAtk()."</span>".$manaEfe."</td>";
	}
	echo "</tr>";
	echo "<tr>";
	foreach ( $charaParamList as $dataBean ) {
		if ($dataBean->isCostDwnFlg()) {
			$disp = "<span class='empha'>".$dataBean->getCost ()."</span>";
		}else {
			$disp = $dataBean->getCost ();
		}
		// コスト
		echo "<td id='param" . $cnt . "Cos'>コスト:" . $disp . "</td>";
	}
}
?>
				</tr>
			</table>
			</div>
			<!-- Twitterボタン -->
			作成したデッキをツイートしよう！<br>
			<a href="https://twitter.com/share" class="twitter-share-button" data-url=<?=$serverName."/WizDeck/main/share.php?id=$charaIds"; ?> data-text="黒ウィズデッキシミュレータ｜デッキを公開します！" data-size="large">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
			<br><br>
			<a href="index.html" class="btn btn-default">戻る</a><br>
		</div>
	</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="../js/vendor/jquery.min.js"><\/script>');</script>
<script src="../js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../js/ie10-viewport-bug-workaround.js"></script>
<!-- Custom js -->
<script src="../js/common.js"></script>

</body>
</html>