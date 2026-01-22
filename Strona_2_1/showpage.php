<?php
/* ============================================================
   Plik: showpage.php
   Funkcja odpowiedzialna za wyświetlanie treści podstron
   pobieranych z bazy danych MySQL
   ============================================================ */

/* Dołączenie pliku konfiguracyjnego (połączenie z bazą danych) */
include('cfg.php');

function PokazPodstrone($id)
{
	/* Użycie globalnej zmiennej połączenia z bazą danych */
    global $link;
	
	/* Zabezpieczenie parametru ID przed SQL Injection */
    $id_clear = mysqli_real_escape_string($link, $id);
	
	/* Zapytanie pobierające jedną podstronę o podanym ID */
    $query = "SELECT * FROM page_list WHERE id='$id_clear' LIMIT 1";
    
	/* Wykonanie zapytania SQL */
	$result = mysqli_query($link, $query);
	
	/* Pobranie wyniku zapytania jako tablicy */
    $row = mysqli_fetch_array($result);
	
	/* Obsługa sprawdzania, czy strona istnieje */
    if(empty($row['id']))
    {
        $web = '[nie_znaleziono_strony]';
    }
    else
    {
        $web = $row['page_content'];
    }
	
	/* Zwrócenie treści pliku do wyświetlenia */
    return $web;
}
?>
