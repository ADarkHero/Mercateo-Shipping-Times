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
						
							//SKUs for every shipping day [1]->1 cay shipping time; [2]->2 cay shipping time etc.
							//[0] is empty, so [1] equals one day of shipping time
							$sku = array("", "2000061433", "2000061429", "2000000001", 
							"2000029922", "18661909", "2000429223", 
							"2000253631", "2000025711", "2000036586", 
							"2000085656", "2000260159", "2000000825", 
							"2000000155", "2000000061");
							
							//Link to the shipping query from mercateo
							$mercateo_base_link = "http://www.mercateo.com/deltareport.jsp?CatalogID=1955&SKU=";
							
							//Opens a file for tracking the shipping times
							$file = 'csv/lieferzeiten.csv';
							$current = file_get_contents($file);
							
							//Write current date
							$date = date("d.m.y");

							//Data should only be written once per day
							$new_stat = false;
							if(stristr($current, $date) === FALSE){
								$new_stat = true;
							}	
							$current .= $date;
							$current .= "; ";
							
							//Reads shipping times from mercateo
							for($i = 1; $i < count($sku); $i++){						
								//Generate HTML-code
								echo '<article class="thumb">'; 
								
								$mercateo_link = $mercateo_base_link.$sku[$i];	
								
								//Read HTML from mercateo
								$html = file_get_contents($mercateo_link);
								
								$lieferzeiten = strstr($html, 'Lieferzeit real: ');
								$lieferzeiten = strstr($lieferzeiten, 'Mercateo', true);
								
								//Calculate real shipping times
								$lieferzeit_real = strstr($lieferzeiten, 'Versprochene', true);
								$lieferzeit_real = substr($lieferzeit_real, 40, -1);
								$lieferzeit_real = strstr($lieferzeit_real, 'Tage', true);
								$lieferzeit_real = preg_replace("/[^0-9,.]/", "", $lieferzeit_real);
								
								
								//I don't know what I'm doing here, but it works.
								//Future Hero -> Pls do anything about this
								$lieferzeit_versprochen = strstr($lieferzeiten, 'Betrachtungszeitraum', true);
								$lieferzeit_versprochen = substr($lieferzeit_versprochen, 100, -1);
								$lieferzeit_versprochen = strstr($lieferzeit_versprochen, 'Tage', true);
								$lieferzeit_versprochen = preg_replace("/[^0-9,.]/", "", $lieferzeit_versprochen);
								
								if ($lieferzeit_versprochen == null){
									$lieferzeit_versprochen = "1 Tag";
								}
								else{
									$lieferzeit_versprochen = $lieferzeit_versprochen." Tage";
								}
								
								$current .= $lieferzeit_real."; ";
								
								//Generate rest of the HTML
								echo '<a href="http://www.mercateo.com/images/contentmanagement/delivery-charts/1955-'.$i.'.gif" class="image"><img src="http://www.mercateo.com/images/contentmanagement/delivery-charts/1955-'.$i.'.gif" alt="" /></a>';
								echo '<h2>Versprochene: '.$lieferzeit_versprochen.'<br />';
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
							
							//Write endline
							$current .= "\n";
							
							//Write stuff to csv
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