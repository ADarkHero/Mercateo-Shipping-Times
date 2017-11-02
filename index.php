<!DOCTYPE HTML>
<!--
	Multiverse by Pixelarity
	pixelarity.com | hello@pixelarity.com
	License: pixelarity.com/license
-->
<html>
	<head>
		<title>Lieferzeiten Mercateo</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<h1><a href="csv/lieferzeiten.csv"><strong>Lieferzeiten</strong> für Mercateo</a></h1>
						<nav>
							<ul>
								<li><a href="csv/lieferzeiten.csv" class="icon fa-info-circle">Öffne .csv</a></li>
							</ul>
						</nav>
					</header>

				<!-- Main -->
					<div id="main">
					
						<?php
							error_reporting(0);
						
							//SKUs für die jeweiligen Tage an Lieferzeit
							//Erstes Feld ist leer, damit Array Position 1 -> 1 Tag Lieferzeit usw.
							$sku = array("", "2000061433", "2000061429", "2000000001", 
							"2000339770", "2000030510", "2000429223", 
							"2000036901", "2000025711", "2018813196", 
							"2000085656", "2000061491", "2000000825", 
							"2000000155", "2000000061", "2000000189");
							
							//Link zur Mercateo Lieferzeitenabfrage
							$mercateo_base_link = "http://www.mercateo.com/deltareport.jsp?CatalogID=1955&SKU=";
							
							//Öffne neue Datei
							$file = 'csv/lieferzeiten.csv';
							$current = file_get_contents($file);
							
							//Schreibe Datum
							$date = date("d.m.y");

							//Daten sollen nur geschrieben werden, wenn Datum noch nicht vorkam
							$new_stat = false;
							if(stristr($current, $date) === FALSE){
								$new_stat = true;
							}	
							$current .= $date;
							$current .= "; ";
							
							//Liest Lieferzeiten von Mercateo aus
							for($i = 1; $i < count($sku); $i++){						
								//Generiere eigenen HTML Code
								echo '<article class="thumb">'; 
								
								$mercateo_link = $mercateo_base_link.$sku[$i];	
								
								//Liest den HTML-Code von Mercateo ein
								$html = file_get_contents($mercateo_link);
								
								$lieferzeiten = strstr($html, 'Lieferzeit real: ');
								$lieferzeiten = strstr($lieferzeiten, 'Mercateo', true);
								
								//Reale Lieferzeit berechnen
								$lieferzeit_real = strstr($lieferzeiten, 'Versprochene', true);
								$lieferzeit_real = substr($lieferzeit_real, 40, -1);
								$lieferzeit_real = strstr($lieferzeit_real, 'Tage', true);
								$lieferzeit_real = preg_replace("/[^0-9,.]/", "", $lieferzeit_real);
								
								$current .= $lieferzeit_real."; ";
								
								//Generiere HTML-Code
								echo '<a href="http://www.mercateo.com/images/contentmanagement/delivery-charts/1955-'.$i.'.gif" class="image"><img src="http://www.mercateo.com/images/contentmanagement/delivery-charts/1955-'.$i.'.gif" alt="" /></a>';
								echo '<h2>Versprochene: '.$i.' Tage<br />';
								if($lieferzeit_real <= $i){
									echo '<span style="color: #00ff00">Reale: '.$lieferzeit_real.' Tage</span></h2>';
								}
								else if($lieferzeit_real <= $i+3){
									echo '<span style="color: #aa0000">Reale: '.$lieferzeit_real.' Tage</span></h2>';
								}
								else{
									echo '<span style="color: #ff0000">Reale: '.$lieferzeit_real.' Tage</span></h2>';
								}
								echo '</article>';
							}
							
							//Schreibe Zeilenende in Datei
							$current .= "\n";
							
							//Schreibe Daten in csv
							if($new_stat){
								file_put_contents($file, $current);
							}
								


							
								
							
						?>
					</div>

			</div>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.poptrox.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>