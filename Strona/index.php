<?php
 error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
 /* po tym komentarzu będzie kod do dynamicznego ładowania stron */
?>
<!DOCTYPE html>
<html lang="UTF-8">
<head>
<link rel="stylesheet" href="css/style.css">
<meta name="description" content="Projekt 1">
<meta name="keywords" content="HTML5, CSS3, JavaScript">
<meta name="author" content="Weronika Szulc">
<script src="js/kolorujtlo.js" type="text/javascript"></script>
<script src="js/timedate.js" type="text/javascript"></script>
<script src="js/jquery-3.7.1.min.js" type="text/javascript"></script>
<title>Złote Maliny</title>
</head>
<body class="bb" onload="startclock()">
<!-- css klasy formatowanie potrzebuje .[nazwa_klasy] -->
<header>
<p>Złote Maliny</p>
</header>
<div class="main">
<div class="left_side">
<h1> Nawigacja </h1>
<ul>
    <li><a href="index.php?page=glowna">Strona główna</a></li>
    <li><a href="index.php?page=historia">Historia nagrody</a></li>
    <li><a href="index.php?page=nom2025">Nominacje z 2025 roku</a></li>
    <li><a href="index.php?page=nom2024">Nominacje z 2024 roku</a></li>
    <li><a href="index.php?page=nom2023">Nominacje z 2023 roku</a></li>
    <li><a href="index.php?page=nom2022">Nominacje z 2022 roku</a></li>
	<li><a href="index.php?page=filmy">Filmy</a></li>
    <li><a href="index.php?page=eksperymenty">Pole boju</a></li>
</ul>
</div>
<!-- WSTAW ZDJĘCIA PODPISANE PARAGRAFAMI LINKUJĄCYM ŹRÓDŁA -->
<!-- citization po prawej: style="text-align:right" -->
<div class="right_side">
<?php
    if (!isset($_GET['page']) || $_GET['page'] == '' || $_GET['page'] == 'glowna') {
        $strona = 'html/glowna.html';
    } else if ($_GET['page'] == 'historia') {
        $strona = 'html/historia.html';
    } else if ($_GET['page'] == 'nom2025') {
        $strona = 'html/nom_2025.html';
    } else if ($_GET['page'] == 'nom2024') {
        $strona = 'html/nom_2024.html';
    } else if ($_GET['page'] == 'nom2023') {
        $strona = 'html/nom_2023.html';
    } else if ($_GET['page'] == 'nom2022') {
        $strona = 'html/nom_2022.html';
    } else if ($_GET['page'] == 'eksperymenty') {
        $strona = 'html/eksperymenty_js.html';
    } else if ($_GET['page'] == 'filmy') {
        $strona = 'html/filmy.html';
    } else {
        // Domyślnie lub gdy nie znaleziono strony
        $strona = 'html/glowna.html';
    }

    // Sprawdzenie istnienia i dołączenie pliku
    if (file_exists($strona)) {
        include($strona);
    } else {
        echo "<h2>Nie znaleziono strony: $strona</h2>";
    }
?>
</div>
</div>
<footer>
<p>Autor strony: Weronika Szulc</p>
<p>Tło wykonane z pomocą <a href="https://pl.m.wikipedia.org/wiki/Plik:Fleur-de-lis-fill.svg">plików Wikipedii</a></p>
	<div id="zegarek"></div>
	<div id="data"></div>
</footer>
<?php
	$nr_indeksu='175508';
	$nrGrupy='ISI3';

	echo 'Weronika Szulc ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br /><br />';
?>

</body>
</html>