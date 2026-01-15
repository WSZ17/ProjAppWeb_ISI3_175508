<?php
session_start(); // To MUSI być w linii nr 1
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

include('cfg.php');
include('showpage.php');
include('contact.php');
include('cart.php'); // Dołączamy plik z funkcjami koszyka

// --- LOGIKA OBSŁUGI KOSZYKA (Zadanie 1) ---
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'add' && isset($_GET['id_prod'])) {
        DodajDoKoszyka((int)$_GET['id_prod']);
        header("Location: index.php?id=koszyk"); // Przekierowanie wymusza odświeżenie sesji
        exit;
    }
    if ($_GET['action'] == 'remove' && isset($_GET['item_id'])) {
        UsunZKoszyka((int)$_GET['item_id']);
        header("Location: index.php?id=koszyk");
        exit;
    }
    if ($_GET['action'] == 'update' && isset($_GET['item_id']) && isset($_POST['new_qty'])) {
        $idx = (int)$_GET['item_id'];
        $_SESSION[$idx.'_2'] = (int)$_POST['new_qty']; // Bezpośrednia edycja ilości
        header("Location: index.php?id=koszyk");
        exit;
    }
}
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
			<li><a href="index.php?id=sklep">Sklep</a></li>
			<li><a href="index.php?id=koszyk">Koszyk</a></li>
		</ul>
		</div>
		<div class="right_side">
			<?php
			// Pobieramy ID z adresu URL
			$id_strony = isset($_GET['id']) ? $_GET['id'] : 1;

			// 1. Sprawdzamy specjalne przypadki (Sklep i Koszyk)
			if ($id_strony === 'sklep') {
				echo PokazProduktySklep($link); // Wyświetla listę produktów do kupienia
			} 
			elseif ($id_strony === 'koszyk') {
				echo PokazKoszyk($link); // Wyświetla zawartość koszyka
			} 
			// 2. Obsługa kontaktu
			elseif ($id_strony === 'contact') {
				echo PokazKontakt();
			} 
			// 3. Obsługa zapomnianego hasła
			elseif ($id_strony === 'forgot_pass') {
				echo PrzypomnijHaslo();
			} 
			// 4. Standardowe podstrony z bazy danych
			else {
				$tresc_strony = PokazPodstrone($id_strony);
				echo $tresc_strony;
			}
			?>
		</div>
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