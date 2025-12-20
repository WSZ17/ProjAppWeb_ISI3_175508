<?php
 error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
 /* po tym komentarzu będzie kod do dynamicznego ładowania stron */
 include('cfg.php');
 include('showpage.php');
 include('contact.php');
?>
<!DOCTYPE html>
<html lang="UTF-8">
<head>
	<link rel="stylesheet" href="css/style.css">
	<meta name="description" content="Projekt 1.8">
	<meta name="keywords" content="HTML5, CSS3, JavaScript">
	<meta name="author" content="Weronika Szulc">
	<!-- skrypt do zegara -->
	<script src="js/timedate.js" type="text/javascript"></script>
	<title>Złote Maliny</title>
</head>
<!-- startclock potrzebne do wykonania funkcji z zegarem -->
<body class="bb" onload="startclock()">
	<header>
		<p>Złote Maliny</p>
	</header>
	<div class="main">
		<div class="left_side">
		<h1> Nawigacja </h1>
		<ul>
			<!-- lista do wybierania stron -->
			<li><a href="index.php?id=1">Strona główna</a></li>
			<li><a href="index.php?id=2">Historia nagrody</a></li>
			<li><a href="index.php?id=3">Nominacje z 2025 roku</a></li>
			<li><a href="index.php?id=4">Nominacje z 2024 roku</a></li>
			<li><a href="index.php?id=5">Nominacje z 2023 roku</a></li>
			<li><a href="index.php?id=6">Nominacje z 2022 roku</a></li>
			<li><a href="index.php?id=7">Filmy</a></li>
		</ul>
		</div>
		<div class="right_side">
			<!-- Kod PHP pozwalający na zmiany zawartości divu klasy "right_side", co pozwala na dynamiczne wczytywanie stron z pomocą PHP i użycia bazy danych MySQL, używa kodu z shopage.php -->
			<?php
			  
			  	if (isset($_GET['id']))
              {
                  $id_strony = $_GET['id'];
                  if ($id_strony === 'contact') {
                      echo PokazKontakt();
                  } elseif ($id_strony === 'forgot_pass') {
                      echo PrzypomnijHaslo();
                  } else {
                      $tresc_strony = PokazPodstrone($id_strony);
                      echo $tresc_strony;
                  }
              }
              else
              {
                  $tresc_strony = PokazPodstrone(1); 
                  echo $tresc_strony;
              }
			?>
		</div>
	</div>
	<footer>
		<p>Autor strony: Weronika Szulc</p>
		<p>Tło wykonane z pomocą <a href="https://pl.m.wikipedia.org/wiki/Plik:Fleur-de-lis-fill.svg">plików Wikipedii</a></p>
		<!-- div wykorzystujący kod pliku timedate.js w folderze js -->
		<div id="zegarek"></div>
		<div id="data"></div>
		<!-- odnośnik do funkcji PokazKontakt() w pliku   -->
		<a href="index.php?id=contact">Kontakt</a>
		<br />
		<a href="index.php?id=forgot_pass">Przypomnienie hasła</a>
	</footer>
		<!-- Niewielki kod wyświetlający autora strony w stopce -->
		<?php
			$nr_indeksu='175508';
			$nrGrupy='ISI3';

			echo 'Weronika Szulc ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br /><br />';
            
		?>
</body>
</html>