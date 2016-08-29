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
	<div class="container">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<h1>黒ウィズ　デッキ作成シミュレータ</h1>
<?php



// エラー文字（戻る）
$errorBack = "<a href='index.html' class='alert-link'>こちら</a>からデッキを作成して下さい。";
// エラー文字
$errorStr = "<span class='glyphicon glyphicon-exclamation-sign lead' aria-hidden='true'></span>
		<span class='lead'>エラーが発生しました。</span><br>";
// エラー文字（該当なし）
$errorNoHitStr = "<span class='glyphicon glyphicon-exclamation-sign lead' aria-hidden='true'></span>
		<span class='lead'>該当する精霊が存在しませんでした。</span><br>".$errorBack;
// エラー文字（入力なし）
$errorNoStr = $errorStr."精霊の名前を1体以上入力して下さい。<br>".$errorBack;
// エラーメッセージ
$errorMsg = "";
// エラー種別
$errorType = "alert-danger";


$status = filter_input ( INPUT_GET, "status", FILTER_DEFAULT);

if ($status == "abend") {
	$errorMsg = $errorStr.$errorBack;
} else if ($status == "noone") {
	$errorMsg = $errorNoStr;
} else if ($status == "nohit") {
	$errorType = "alert-warning";
	$errorMsg = $errorNoHitStr;
} else {
	$errorMsg = $errorStr.$errorBack;
}


?>

				<br>
				<div class="alert <?=$errorType ?>">
				<?= $errorMsg ?>
				</div>
			</div>
		</div>
	</div>
</body>

</html>