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
				<?php require 'preProcess.php';?>
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
				<br>
				<a href="index.html" class="btn btn-default">デッキを作成する</a>
			</div>
		</div>
	</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="../js/vendor/jquery.min.js"><\/script>')</script>
<script src="../js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../js/ie10-viewport-bug-workaround.js"></script>
<!-- Custom js -->
<script src="../js/common.js"></script>

</body>
</html>