<?php
	include_once './includes/common.php'; 
//	$session->change_page('');

/*
https://dev.to/adrai/the-progressive-guide-to-jquery-internationalization-i18n-using-i18next-3dc3
*/

?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="icon" type="image/png" href="favicon.ico" />
    	<meta name="author" content="Colin Boullard"/>
    	<title>Klod Online</title>
    	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
    	<link rel="stylesheet" media="screen" type="text/css" title="Design" href="css/website.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="./js/dict.js"></script>
		<script src="./js/jquery.translate.js"></script>
		<script src="./js/main.js"></script>
		<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
	</head>
	<body>
  	<div id="pics_bg_barb"><div id="pics_bg_civ"><div id="page">

		<div id="header">
        	<a href="."><img src="./img/banniere3.png" class="logo" alt="logo"></a>
			<div id="title_header" class="trn">__KLOD_MANTRA__</div>	
        </div>

        <div class="body_item">


	        <div id="main_menu">
				<a id="presentation" class="main trn">__PRESENTATION__</a>
				<a id="worlds" class="main trn">__WORLDS__</a>

				<a id="login" class="main trn">__LOGIN__</a>

			</div>


		</div>

	        <div id="main">
				<?php
	        		include_once './pages/presentation.php'; 
	        		
	        		if ($session->id()!=0) { echo '<script>ChangeLoginMenu("'.$session->name().'")</script>'; }
				?>
			</div>


		
		<div class="body_item"><div id="footer">

			C.Boullard - <span class="trn">__ORIGINAL_CONTENT__</span>

			<div id="flags">
				<a id="fr" class="lang"><img class="flag" src="./img/fr_flag.png"></a>
				<a id="en" class="lang"><img class="flag" src="./img/eng_flag.png"></a>
			</div>

		</div></div>

	</div></div></div>
	</body>
</html>
