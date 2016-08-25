<!DOCTYPE html>
<html>
<head>

<link type="text/css" rel="stylesheet" href="../css/jquery-ui.min.css" />
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link type="text/css" rel="stylesheet" href="../css/common.css" />
<script type="text/javascript" src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<meta charset="UTF-8">
<title>エラー</title>

<body>


<div class="container">
	<h1>黒猫のウィズ　デッキ作成ツール</h1>
<?php



// エラー文字（戻る）
$errorBack = "<a href='index.html' class='alert-link'>こちら</a>からデッキを作成して下さい。";
// エラー文字
$errorStr = "<span class='glyphicon glyphicon-exclamation-sign lead' aria-hidden='true'></span>
		<span class='lead'>エラーが発生しました。</span><br>";
// エラー文字（入力なし）
$errorNoStr = $errorStr."精霊の名前を1体以上入力して下さい。<br>".$errorBack;
// エラーメッセージ
$errorMsg = "";


$status = filter_input ( INPUT_GET, "status", FILTER_DEFAULT);

if ($status == "abend") {
	$errorMsg = $errorStr.$errorBack;
} else if ($status == "noone") {
	$errorMsg = $errorNoStr;
}


?>

		<br>
		<div class="alert alert-danger" role="alert">
		<?= $errorMsg ?>
		</div>

	</div>
</body>

</html>