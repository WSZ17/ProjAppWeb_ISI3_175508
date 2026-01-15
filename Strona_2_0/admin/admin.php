<!DOCTYPE html>
<html lang="UTF-8">
<head>
	<link rel="stylesheet" href="../css/style.css">
	<meta name="description" content="Projekt 1">
	<meta name="keywords" content="HTML5, CSS3, JavaScript">
	<meta name="author" content="Weronika Szulc">
	<title>Panel CMS</title>
</head>
<body class="bb">
	<header>
		<p>Panel CSM</p>
	</header>
	<div class="main">
		<div class="right_side">
<?php
/* ============================================================
   admin.php
   Prosty panel CMS:
   - logowanie administratora (sesja)
   - lista podstron
   - dodawanie, edycja i usuwanie podstron
   - dodawanie kategorii
   ============================================================ */

/* Uruchomienie sesji użytkownika */
session_start();

/* Dołączenie pliku konfiguracyjnego (dane logowania, baza danych) */

include '../cfg.php';

/* ============================================================
   Funkcja FormularzLogowania()
   - wyświetla formularz logowania administratora
   ============================================================ */
function FormularzLogowania() {
	
	/* HTML formularza logowania */
    $wynik = '
        <div class="logowanie">
            <div class="logowanie">
                <form method="post" name="LoginForm" enctype="multipart/form-data" action="'.htmlspecialchars($_SERVER['REQUEST_URI']).'">
                    <table class="logowanie">
                        <tr><td class="log4_t">[email]</td><td><input type="text" name="login_email" class="logowanie" /></td></tr>
                        <tr><td class="log4_t">[hasło]</td><td><input type="password" name="login_pass" class="logowanie" /></td></tr>
                        <tr><td>&nbsp;</td><td><input type="submit" name="xl_submit" class="logowanie" value="zaloguj" /></td></tr>
                    </table>
                </form>
            </div>
        </div>
    ';
	
	/* Zwrócenie formularzu logowania */
    return $wynik;
}

/* Zmienna przechowująca komunikat błędu logowania */
$error_message = '';


/* ============================================================
   Obsługa logowania – tylko gdy użytkownik NIE jest zalogowany
   ============================================================ */
if (!isset($_SESSION['logged_in'])){
	
	/* Sprawdzenie czy formularz logowania został wysłany */
    if((isset($_POST['xl_submit']))){
		
		/* Pobranie i oczyszczenie danych z formularza */
        $input_login = isset($_POST['login_email']) ? trim($_POST['login_email']) : '';
        $input_pass = isset($_POST['login_pass']) ? trim($_POST['login_pass']) : '';
        
		/* Porównanie danych z wartościami z cfg.php */
		if ($input_login === $login && $input_pass === $pass) {
			
			/* Poprawne dane – zapis do sesji */
            $_SESSION['logged_in'] = true;
			
			/* Odświeżenie strony (usunięcie POST) */
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();

        } else {
			/* Błąd logowania */
            $error_message = '<p style="color: red;">Błąd logowania: nieprawidłowy e-mail lub hasło.</p>';
        }
    }
}

/* ============================================================
   Panel CMS – tylko dla zalogowanego administratora
   ============================================================ */
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
	
	/* Odczyt parametrów akcji */
    $action = isset($_GET['action']) ? $_GET['action'] : '';
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
	
	/* Zmienna na komunikaty */
    $content = '';

	/* ================= EDYCJA PODSTRONY ================= */
    if (isset($_POST['edit_submit'])) {
		
		/* Pobranie danych z formularza */
        $id_update = isset($_POST['id_edycji']) ? (int)$_POST['id_edycji'] : 0;
        $new_title = mysqli_real_escape_string($link, $_POST['page_title']);
        $new_content = mysqli_real_escape_string($link, $_POST['page_content']);
        $new_status = isset($_POST['status']) ? 1 : 0;
		
		/* Zapytanie UPDATE */
        $query_update = "UPDATE page_list SET 
            page_title = '$new_title', 
            page_content = '$new_content', 
            status = $new_status 
            WHERE id = $id_update LIMIT 1";
			
			
        if (mysqli_query($link, $query_update)) {
            $content = '<p style="color: green;">Podstrona ID ' . $id_update . ' została pomyślnie zaktualizowana.</p>';
            $action = '';
        } else {
            $content = '<p style="color: red;">Błąd aktualizacji: ' . mysqli_error($link) . '</p>';
        }
    }
	
	/* ================= DODAWANIE PODSTRONY ================= */
    if (isset($_POST['add_submit'])) {
		
        $new_title = mysqli_real_escape_string($link, $_POST['page_title']);
        $new_content = mysqli_real_escape_string($link, $_POST['page_content']);
        $new_status = isset($_POST['status']) ? 1 : 0;
        
		/* Zapytanie INSERT */
        $query_insert = "INSERT INTO page_list (page_title, page_content, status) 
                         VALUES ('$new_title', '$new_content', $new_status)";
        
        if (mysqli_query($link, $query_insert)) {
            $content = '<p style="color: green;">Nowa podstrona została pomyślnie dodana (ID: ' . mysqli_insert_id($link) . ').</p>';

            $action = '';
			
        } else {
            $content = '<p style="color: red;">Błąd dodawania: ' . mysqli_error($link) . '</p>';
        }
    }
	
	/* ================= WYBÓR AKCJI ================= */
    if ($action === 'edit' && $id > 0) {
		
        echo $content;
        echo EdytujPodstrone($link, $id);
     
    }elseif ($action === 'add') {
		
        echo DodajNowaPodstrone();
		
    }elseif ($action === 'delete' && $id > 0) {
		
        echo UsunPodstrone($link, $id); 
        ListaPodstron($link);
	
	/* ======== OBSŁUGA AKCJI ZWIĄZANYCH Z KATEGORIAMI ======== */
	
	} elseif ($action === 'categories') {
		$sub_action = isset($_GET['sub_action']) ? $_GET['sub_action'] : '';
		$cat_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

		if ($sub_action === 'edit' && $cat_id > 0) {
		echo EdytujKategorie($link, $cat_id); // Funkcja obsługuje POST i wyświetla formularz
		echo ZarzadzajKategoriami($link); // Dodatkowo wyświetlamy listę po edycji
		} elseif ($sub_action === 'add') {
			// Jeśli dodawanie, wyświetl formularz (który również obsłuży POST)
			echo DodajKategorie($link);
			// Następnie wyświetl listę, aby użytkownik widział dodaną kategorię
			echo ZarzadzajKategoriami($link);
		}
		 else {
		// Domyślne wyświetlenie listy i obsługa usuwania/edytowania z listy
			echo ZarzadzajKategoriami($link);
		}

	/* ======== OBSŁUGA AKCJII ZWIĄZANYCH Z PRODUKTAMI ======== */

	} elseif ($action === 'products') {
		$sub_action = isset($_GET['sub_action']) ? $_GET['sub_action'] : '';
		$prod_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
	
		if ($sub_action === 'add') {
			echo ZarzadzajProdukty($link, 'add');
		} elseif ($sub_action === 'edit' && $prod_id > 0) {
			echo ZarzadzajProdukty($link, 'edit', $prod_id);
		} elseif ($sub_action === 'delete' && $prod_id > 0) {
			echo ZarzadzajProdukty($link, 'delete', $prod_id);
		} else {
			echo PokazProdukty($link);
		}
	} else {
		
		/* Widok domyślny */
		echo 'Witaj w Panelu CMS!';
		echo $content;
        ListaPodstron($link);
	}
    
} else {
	
	/* Widok dla niezalogowanego użytkownika */
	echo $error_message; 
	echo FormularzLogowania();
}


/* ============================================================
	Funkcje do administracji podstron WWW wyświetlanych w index.php
   ============================================================ */

/* ============================================================
   Funkcja ListaPodstron()
   - wyświetla listę podstron z opcją edycji i usuwania
   ============================================================ */
function ListaPodstron($link) {
	
	echo '<p>';
    echo '<a href="admin.php?action=add" style="padding: 10px; background-color: #6C1D1C;; color: white; text-decoration: none; border-radius: 2px;">Dodaj nową podstronę</a> ';
    echo '<a href="admin.php?action=categories" style="padding: 10px; background-color: #6C1D1C; color: white; text-decoration: none; border-radius: 2px;">Zarządzaj Kategoriami</a>';
	echo '<a href="admin.php?action=products" style="padding: 10px; background-color: #6C1D1C; color: white; text-decoration: none; border-radius: 2px;">Zarządzaj produktami</a>';
    echo '</p>';	
	
	/* Pobranie listy podstron */
    $query = "SELECT id, page_title FROM page_list ORDER BY status DESC";
    $result = mysqli_query($link, $query); 

    echo "<ul>";
    while ($row = mysqli_fetch_array($result)) {
        echo "<li>";
        echo $row['id'] . ' ' . $row['page_title'];
        echo ' <a href="admin.php?action=edit&id=' . $row['id'] . '">Edytuj</a>';
        echo ' <a href="admin.php?action=delete&id=' . $row['id'] . '">Usuń</a>';
        echo "</li>";
    }
    echo "</ul>";
}

/* ============================================================
   Funkcja EdytujPodstrone()
   - formularz edycji istniejącej podstrony
   ============================================================ */
function EdytujPodstrone($link, $id)
{
    $id = (int)$id;
	
	/* Pobranie danych podstrony */
    $query_select = "SELECT * FROM page_list WHERE id = $id LIMIT 1"; 
    $result = mysqli_query($link, $query_select);
    $data = mysqli_fetch_array($result);
	
	
    if (!$data) {
        return '<p style="color: red;">Nie znaleziono podstrony o podanym ID.</p>';
    }
	
	/* Przygotowanie danych do formularza */
    $tytul = htmlspecialchars($data['page_title']);
    $tresc = htmlspecialchars($data['page_content']);
    $aktywny_checked = ($data['status'] == 1) ? 'checked' : '';
	
	/* Formularz edycji */
    $formularz = '
        <h2 class="heading">Edycja podstrony: ' . $tytul . '</h2>
        <form method="post" action="admin.php?action=edit&id=' . $id . '">
            <input type="hidden" name="id_edycji" value="' . $id . '">
            <table>
                <tr>
                    <td>Tytuł:</td>
                    <td><input type="text" name="page_title" value="' . $tytul . '" required /></td>
                </tr>
                <tr>
                    <td>Treść:</td>
                    <td><textarea name="page_content" rows="10" cols="50" required>' . $tresc . '</textarea></td>
                </tr>
                <tr>
                    <td>Aktywna:</td>
                    <td><input type="checkbox" name="status" value="1" ' . $aktywny_checked . ' /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="edit_submit" value="Zapisz zmiany" /></td>
                </tr>
            </table>
        </form>
    ';
	
	/* Zwrócenie formularza */
    return $formularz;
}

/* ============================================================
   Funkcja DodajNowaPodstrone()
   - formularz dodawania nowej podstrony
   ============================================================ */
function DodajNowaPodstrone() {
	
	/* Formularz do wypełnienia by stworzyć nową stronę */
    $wynik = '
        <h2 class="heading">Dodaj nową podstronę</h2>
        <form method="post" action="admin.php?action=add">
            <table>
                <tr>
                    <td>Tytuł:</td>
                    <td><input type="text" name="page_title" value="" required /></td>
                </tr>
                <tr>
                    <td>Treść:</td>
                    <td><textarea name="page_content" rows="10" cols="50" required></textarea></td>
                </tr>
                <tr>
                    <td>Aktywna:</td>
                    <td><input type="checkbox" name="status" value="1" checked /></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="add_submit" value="Zatwierdź" /></td>
                </tr>
            </table>
        </form>
    ';
	
    return $wynik;
}

/* ============================================================
   Funkcja UsunPodstrone()
   - usuwa podstronę z bazy danych
   ============================================================ */
function UsunPodstrone($link, $id) {
    
	$id = (int)$id;
    
	/* Zapytanie DELETE */
    $query_delete = "DELETE FROM page_list WHERE id = $id LIMIT 1"; 
    
    if (mysqli_query($link, $query_delete)) {
        return '<p style="color: green;">Podstrona ID ' . $id . ' została pomyślnie usunięta.</p>';
    } else {
        return '<p style="color: red;">Błąd usuwania: ' . mysqli_error($link) . '</p>';
    }
}

/* ============================================================
	Funkcje do administracji strony sklepowej
   ============================================================ */

function DodajKategorie($link) {
    $komunikat = '';

    if (isset($_POST['category_add_submit'])) {
        // Zabezpieczenie danych
        $nazwa = trim($_POST['category_name']); 
        $matka = (int)$_POST['category_parent'];

        if (empty($nazwa)) {
            $komunikat = '<p style="color: red;">Nazwa kategorii nie może być pusta!</p>';
        } else {
            // BEZPIECZNA WERSJA (Prepared Statement)
            // Użycie poprawnej nazwy tabeli: product_cathegories
            $query = "INSERT INTO product_cathegories (nazwa, matka) VALUES (?, ?)";
            
            $stmt = mysqli_prepare($link, $query);

            if ($stmt) {
                // Powiązanie parametrów ('s' dla string, 'i' dla integer)
                mysqli_stmt_bind_param($stmt, 'si', $nazwa, $matka);
                
                if (mysqli_stmt_execute($stmt)) {
                    $komunikat = '<p style="color: green;">Kategoria "' . htmlspecialchars($nazwa) . '" została pomyślnie dodana.</p>';
                    // Wyczyść pola formularza po sukcesie, aby uniknąć ponownego dodania
                    $_POST['category_name'] = '';
                    $_POST['category_parent'] = 0;
                } else {
                    $komunikat = '<p style="color: red;">Błąd dodawania kategorii: ' . mysqli_error($link) . '</p>';
                }

                mysqli_stmt_close($stmt);
            } else {
                $komunikat = '<p style="color: red;">Błąd przygotowania zapytania: ' . mysqli_error($link) . '</p>';
            }
        }
    }

    /* Formularz dodawania */
    $options = '';
    // Użycie poprawnej nazwy tabeli: product_cathegories
    $query_parents = "SELECT id, nazwa FROM product_cathegories WHERE matka = 0 ORDER BY nazwa";
    $result_parents = mysqli_query($link, $query_parents);

    $current_name = htmlspecialchars($_POST['category_name'] ?? '');
    $current_parent = (int)($_POST['category_parent'] ?? (isset($_GET['parent_id']) ? $_GET['parent_id'] : 0));

    if ($result_parents) {
        while ($row = mysqli_fetch_array($result_parents)) {
            $selected = ($row['id'] == $current_parent) ? ' selected' : '';
            $options .= '<option value="' . $row['id'] . '"' . $selected . '>' . htmlspecialchars($row['nazwa']) . '</option>';
        }
    } 

    $wynik = '
        <h2 class="heading">Dodaj nową kategorię</h2>
        ' . $komunikat . '
        <form method="post" action="admin.php?action=categories&sub_action=add">
            <table>
                <tr>
                    <td>Nazwa kategorii:</td>
                    <td><input type="text" name="category_name" required value="' . $current_name . '" /></td>
                </tr>
                <tr>
                    <td>Kategoria nadrzędna:</td>
                    <td>
                        <select name="category_parent">
                            <option value="0"' . ($current_parent == 0 ? ' selected' : '') . '>--- Kategoria Główna ---</option>
                            ' . $options . '
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="category_add_submit" value="Dodaj kategorię" /></td>
                </tr>
            </table>
        </form>
    ';
    return $wynik;
}

function ZarzadzajKategoriami($link) {
    $output = '';

    // 1. Obsługa usuwania
    if (isset($_GET['delete_cat_id'])) {
        $id_delete = (int)$_GET['delete_cat_id'];
        
        // Sprawdzenie, czy kategoria ma podkategorie (Prepared Statement)
        $check_query = "SELECT COUNT(*) AS count FROM product_cathegories WHERE matka = ? LIMIT 1";
        $stmt_check = mysqli_prepare($link, $check_query);
        
        if ($stmt_check) {
            mysqli_stmt_bind_param($stmt_check, 'i', $id_delete);
            mysqli_stmt_execute($stmt_check);
            $check_result = mysqli_stmt_get_result($stmt_check);
            $check_row = mysqli_fetch_assoc($check_result);
            mysqli_stmt_close($stmt_check);

            if ($check_row['count'] > 0) {
                $output .= '<p style="color: red;">Nie można usunąć kategorii: ma przypisane podkategorie!</p>';
            } else {
                // Usuwanie (Prepared Statement)
                $query_delete = "DELETE FROM product_cathegories WHERE id = ? LIMIT 1";
                $stmt_delete = mysqli_prepare($link, $query_delete);

                if ($stmt_delete) {
                    mysqli_stmt_bind_param($stmt_delete, 'i', $id_delete);
                    if (mysqli_stmt_execute($stmt_delete)) {
                        $output .= '<p style="color: green;">Kategoria ID ' . $id_delete . ' pomyślnie usunięta.</p>';
                    } else {
                        $output .= '<p style="color: red;">Błąd usuwania: ' . mysqli_error($link) . '</p>';
                    }
                    mysqli_stmt_close($stmt_delete);
                } else {
                    $output .= '<p style="color: red;">Błąd przygotowania zapytania usuwania: ' . mysqli_error($link) . '</p>';
                }
            }
        } else {
             $output .= '<p style="color: red;">Błąd sprawdzania podkategorii: ' . mysqli_error($link) . '</p>';
        }
    }

    // 2. Wyświetlanie listy (drzewa)
    $output .= '<h2>Lista kategorii</h2>';
    $output .= '<p><a href="admin.php?action=categories&sub_action=add" style="padding: 10px; background-color: #6C1D1C; color: white; text-decoration: none; border-radius: 2px;">Dodaj nową kategorię</a> <a href="admin.php" style="padding: 10px; background-color: #6C1D1C; color: white; text-decoration: none; border-radius: 2px;">Powrót do panelu głównego</a> </p>';
    $output .= '<ul style="list-style-type: none; padding-left: 0;">';

    // Zapytanie 1: Kategorie główne (matki = 0)
    $query_main = "SELECT * FROM product_cathegories WHERE matka = 0 ORDER BY nazwa";
    $result_main = mysqli_query($link, $query_main);

    if ($result_main) {
        $main_counter = 0;
        while ($category_main = mysqli_fetch_array($result_main)) {
            if ($main_counter >= 100) { break; }
            $main_counter++;
            $main_id = $category_main['id'];
            $main_name = htmlspecialchars($category_main['nazwa']);

            $output .= '<li>';
            $output .= '<strong>[ID: ' . $main_id . '] ' . $main_name . ' (GŁÓWNA)</strong>';
            $output .= ' <a href="admin.php?action=categories&sub_action=edit&id=' . $main_id . '">Edytuj</a>';
            $output .= ' <a href="admin.php?action=categories&delete_cat_id=' . $main_id . '" onclick="return confirm(\'Czy na pewno chcesz usunąć kategorię i wszystkie jej podkategorie?\')">Usuń</a>';
            $output .= ' <a href="admin.php?action=categories&sub_action=add&parent_id=' . $main_id . '">[+] Dodaj podkategorię</a>';
            
            // Pętla zagnieżdżona: Podkategorie (dzieci danej matki)
            $query_sub = "SELECT * FROM product_cathegories WHERE matka = $main_id ORDER BY nazwa";
            $result_sub = mysqli_query($link, $query_sub);
            $sub_counter = 0;

            if ($result_sub && mysqli_num_rows($result_sub) > 0) {
                $output .= '<ul style="list-style-type: circle; padding-left: 20px;">';
                while ($category_sub = mysqli_fetch_array($result_sub)) {
                    if ($sub_counter >= 100) { break; }
                    $sub_counter++;
                    $sub_id = $category_sub['id'];
                    $sub_name = htmlspecialchars($category_sub['nazwa']);

                    $output .= '<li>';
                    $output .= '[ID: ' . $sub_id . '] ' . $sub_name;
                    $output .= ' <a href="admin.php?action=categories&sub_action=edit&id=' . $sub_id . '">Edytuj</a>';
                    $output .= ' <a href="admin.php?action=categories&delete_cat_id=' . $sub_id . '" onclick="return confirm(\'Czy na pewno chcesz usunąć podkategorię?\')">Usuń</a>';
                    $output .= '</li>';
                }
                $output .= '</ul>';
            }
            $output .= '</li>';
        }
    } else {
        $output .= '<li><p style="color: red;">Błąd odczytu kategorii głównych: ' . mysqli_error($link) . '</p></li>';
    }

    $output .= '</ul>';
    
    return $output;
}

/**
 * Obsługuje zapis edytowanej kategorii do bazy danych lub wyświetla formularz edycji.
 */
function EdytujKategorie($link, $id) {
    $output = '';

    // 1. Obsługa formularza POST (zapis zmian)
    if (isset($_POST['category_edit_submit'])) {
        $category_name = trim($_POST['category_name']);
        $category_parent = (int)$_POST['category_parent'];
        $id_edycji = (int)$_POST['id_edycji'];

        if ($category_name !== '' && $id_edycji === $id) {
            
            // Sprawdzenie, czy kategoria nie jest ustawiana jako podkategoria samej siebie
            if ($category_parent === $id) {
                $output .= '<p style="color: red;">Błąd: Kategoria nie może być swoim własnym rodzicem!</p>';
            } else {
                // Przygotowanie i wykonanie zapytania UPDATE (Prepared Statement)
                $query_update = "UPDATE product_cathegories SET nazwa = ?, matka = ? WHERE id = ? LIMIT 1";
                $stmt_update = mysqli_prepare($link, $query_update);

                if ($stmt_update) {
                    mysqli_stmt_bind_param($stmt_update, 'sii', $category_name, $category_parent, $id);
                    if (mysqli_stmt_execute($stmt_update)) {
                        $output .= '<p style="color: green;">Kategoria ID ' . $id . ' pomyślnie zaktualizowana.</p>';
                    } else {
                        $output .= '<p style="color: red;">Błąd aktualizacji: ' . mysqli_error($link) . '</p>';
                    }
                    mysqli_stmt_close($stmt_update);
                } else {
                    $output .= '<p style="color: red;">Błąd przygotowania zapytania aktualizacji: ' . mysqli_error($link) . '</p>';
                }
            }
		} else {
            $output .= '<p style="color: red;">Błąd: Brak nazwy kategorii lub nieprawidłowe ID edycji.</p>';
        }
    }

    // 2. Pobranie aktualnych danych do formularza
    $query_select = "SELECT nazwa, matka FROM product_cathegories WHERE id = ? LIMIT 1";
    $stmt_select = mysqli_prepare($link, $query_select);
    $current_name = '';
    $current_parent = 0;

	if ($stmt_select) {
		mysqli_stmt_bind_param($stmt_select, 'i', $id);
		mysqli_stmt_execute($stmt_select);
        $result = mysqli_stmt_get_result($stmt_select);

		if ($row = mysqli_fetch_assoc($result)) {
            // Używamy danych z bazy LUB po udanym POST, aby zaktualizować formularz
			$current_name = isset($category_name) ? $category_name : $row['nazwa'];
			$current_parent = isset($category_parent) ? $category_parent : $row['matka'];
            
            // 3. Wyświetlenie formularza
			$output .= EdytujKategorieFormularz($link, $id, $current_name, $current_parent);
		} else {
			$output .= '<p style="color: red;">Błąd: Kategoria o ID ' . $id . ' nie istnieje.</p>';
		}
		mysqli_stmt_close($stmt_select);
	} else {
		$output .= '<p style="color: red;">Błąd pobierania danych: ' . mysqli_error($link) . '</p>';
	}

	return $output;
}

function EdytujKategorieFormularz($link, $id, $nazwa, $matka) {
    $nazwa = htmlspecialchars($nazwa);
    $options = '<option value="0"' . ($matka == 0 ? ' selected' : '') . '>--- Kategoria Główna ---</option>';

    // Pobierz wszystkie potencjalne kategorie nadrzędne (wykluczając edytowaną kategorię)
    $query_parents = "SELECT id, nazwa FROM product_cathegories WHERE id != $id ORDER BY nazwa";
    $result_parents = mysqli_query($link, $query_parents);

    if ($result_parents) {
        while ($row = mysqli_fetch_array($result_parents)) {
            $selected = ($row['id'] == $matka) ? ' selected' : '';
            $options .= '<option value="' . $row['id'] . '"' . $selected . '>' . htmlspecialchars($row['nazwa']) . '</option>';
        }
    }
    
    $formularz = '
        <h2 class="heading">Edycja kategorii: ' . $nazwa . ' (ID: ' . $id . ')</h2>
        <form method="post" action="admin.php?action=categories&sub_action=edit&id=' . $id . '">
            <input type="hidden" name="id_edycji" value="' . $id . '">
            <table>
                <tr>
                    <td>Nazwa kategorii:</td>
                    <td><input type="text" name="category_name" value="' . $nazwa . '" required /></td>
                </tr>
                <tr>
                    <td>Kategoria nadrzędna:</td>
                    <td>
                        <select name="category_parent">
                            ' . $options . '
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" name="category_edit_submit" value="Zapisz zmiany" /></td>
                </tr>
            </table>
        </form>
    ';
    return $formularz;
}

/* ============================================================
	Funkcje do dodawania i zarządzania produktami
   ============================================================ */

/* Funkcja pokazująca produkty */

function PokazProdukty($link) {
    $output = '<h2>Lista Produktów</h2>';
    $output .= '<p><a href="admin.php?action=products&sub_action=add" style="padding:10px; background:#1C6C1D; color:white; text-decoration:none;">Dodaj Produkt</a> <a href="admin.php" style="padding: 10px; background-color: #6C1D1C; color: white; text-decoration: none; border-radius: 2px;">Powrót do panelu głównego</a></p>';
    
    $query = "SELECT * FROM produkty ORDER BY id DESC";
    $result = mysqli_query($link, $query);

    $output .= '<table border="1" cellpadding="5" style="width:100%; border-collapse: collapse;">
                <tr style="background:#eee;"><th>ID</th><th>Zdjęcie</th><th>Tytuł</th><th>Cena Netto</th><th>Ilość</th><th>Status</th><th>Akcje</th></tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        // Warunki dostępności 
        $dzisiaj = date('Y-m-d');
        $czy_aktywny = ($row['status_dostepnosci'] == 1);
        $czy_jest_stan = ($row['ilosc_sztuk'] > 0);
        $czy_nie_wygasl = (empty($row['data_wygasniecia']) || $row['data_wygasniecia'] > $dzisiaj);

        if ($czy_aktywny && $czy_jest_stan && $czy_nie_wygasl) {
            $status_html = '<span style="color:green;">Dostępny</span>';
        } else {
            $status_html = '<span style="color:red;">Niedostępny</span>';
        }

        $output .= '<tr>
            <td>'.$row['id'].'</td>
            <td><img src="'.$row['zdjecie_link'].'" width="50"></td>
            <td>'.$row['tytul'].'</td>
            <td>'.$row['cena_netto'].' zł</td>
            <td>'.$row['ilosc_sztuk'].'</td>
            <td>'.$status_html.'</td>
            <td>
                <a href="admin.php?action=products&sub_action=edit&id='.$row['id'].'">Edytuj</a> | 
                <a href="admin.php?action=products&sub_action=delete&id='.$row['id'].'" onclick="return confirm(\'Usunąć?\')">Usuń</a>
            </td>
        </tr>';
    }
    $output .= '</table>';
    return $output;
}

/* Zarządzanie produktami */

function ZarzadzajProdukty($link, $mode, $id = 0) {
    $output = '';

    // --- USUWANIE --- 
    if ($mode === 'delete') {
        $query = "DELETE FROM produkty WHERE id = $id LIMIT 1";
        if (mysqli_query($link, $query)) {
            $output .= '<p style="color: green;">Produkt usunięty.</p>';
        }
        return $output . PokazProdukty($link);
    }

    // --- OBSŁUGA FORMULARZA (ADD / EDIT) --- 
    if (isset($_POST['product_submit'])) {
        $tytul = mysqli_real_escape_string($link, $_POST['tytul']);
        $opis = mysqli_real_escape_string($link, $_POST['opis']);
        $cena_netto = (float)$_POST['cena_netto'];
        $podatek_vat = (float)$_POST['podatek_vat'];
        $ilosc = (int)$_POST['ilosc_sztuk'];
        $status = isset($_POST['status']) ? 1 : 0;
        $kategoria = (int)$_POST['kategoria_id'];
        $gabaryt = mysqli_real_escape_string($link, $_POST['gabaryt']);
        $zdjecie = mysqli_real_escape_string($link, $_POST['zdjecie_link']);
        $data_wyg = $_POST['data_wygasniecia'];

        if ($mode === 'add') {
            $query = "INSERT INTO produkty (tytul, opis, cena_netto, podatek_vat, ilosc_sztuk, status_dostepnosci, kategoria_id, gabaryt_produktu, zdjecie_link, data_wygasniecia) 
                      VALUES ('$tytul', '$opis', $cena_netto, $podatek_vat, $ilosc, $status, $kategoria, '$gabaryt', '$zdjecie', '$data_wyg')";
        } else {
            $query = "UPDATE produkty SET tytul='$tytul', opis='$opis', cena_netto=$cena_netto, podatek_vat=$podatek_vat, ilosc_sztuk=$ilosc, 
                      status_dostepnosci=$status, kategoria_id=$kategoria, gabaryt_produktu='$gabaryt', zdjecie_link='$zdjecie', data_wygasniecia='$data_wyg' 
                      WHERE id = $id";
        }

        if (mysqli_query($link, $query)) {
            return '<p style="color: green;">Zapisano pomyślnie!</p>' . PokazProdukty($link);
        } else {
            $output .= '<p style="color: red;">Błąd: ' . mysqli_error($link) . '</p>';
        }
    }

    // --- WYŚWIETLANIE FORMULARZA ---
    $data = 
	[
	'tytul'=>'',
	'opis'=>'',
	'cena_netto'=>0,
	'podatek_vat'=>23,
	'ilosc_sztuk'=>0,
	'status_dostepnosci'=>1,
	'kategoria_id'=>0,
	'gabaryt_produktu'=>'',
	'zdjecie_link'=>'',
	'data_wygasniecia'=>''
	];
	
    if ($mode === 'edit') {
        $res = mysqli_query($link, "SELECT * FROM produkty WHERE id = $id");
        $data = mysqli_fetch_assoc($res);
    }

    $output .= '<h2>' . ($mode === 'add' ? 'Dodaj produkt' : 'Edytuj produkt') . '</h2>';
    $output .= '
    <form method="post">
        <table class="cms_table">
            <tr><td>Tytuł:</td><td><input type="text" name="tytul" value="'.$data['tytul'].'" required></td></tr>
            <tr><td>Opis:</td><td><textarea name="opis">'.$data['opis'].'</textarea></td></tr>
            <tr><td>Cena Netto:</td><td><input type="number" step="0.01" name="cena_netto" value="'.$data['cena_netto'].'"></td></tr>
            <tr><td>VAT (%):</td><td><input type="number" step="0.01" name="podatek_vat" value="'.$data['podatek_vat'].'"></td></tr>
            <tr><td>Ilość sztuk:</td><td><input type="number" name="ilosc_sztuk" value="'.$data['ilosc_sztuk'].'"></td></tr>
            <tr><td>Gabaryt:</td><td><input type="text" name="gabaryt" value="'.$data['gabaryt_produktu'].'"></td></tr>
            <tr><td>Link do zdjęcia:</td><td><input type="text" name="zdjecie_link" value="'.$data['zdjecie_link'].'"></td></tr>
            <tr><td>Data wygaśnięcia:</td><td><input type="date" name="data_wygasniecia" value="'.$data['data_wygasniecia'].'"></td></tr>
            <tr><td>Kategoria ID:</td><td><input type="number" name="kategoria_id" value="'.$data['kategoria_id'].'"></td></tr>
            <tr><td>Dostępny:</td><td><input type="checkbox" name="status" '.($data['status_dostepnosci'] ? 'checked' : '').'></td></tr>
            <tr><td></td><td><input type="submit" name="product_submit" value="Zapisz produkt"></td></tr>
        </table>
    </form>';
    
    return $output;
}


?>
		</div>
	</div>
</body>
</html>