<?php
/**
 * Wyświetla produkty pobrane z bazy danych w formie sklepu (Zadanie 2) [cite: 180]
 */
function PokazProduktySklep($link) {
    $output = '<h2>Sklep - Produkty</h2>';
    
    // Pobieramy produkty zgodnie z Twoją strukturą z admin.php [cite: 19]
    $query = "SELECT * FROM produkty WHERE status_dostepnosci = 1 AND ilosc_sztuk > 0 ORDER BY id DESC";
    $result = mysqli_query($link, $query);

    $output .= '<table border="1" cellpadding="10" style="width:100%; border-collapse: collapse; background-color: #d2b48c; text-align: center;">
                <tr style="background-color: #a0522d; color: white;">
                    <th>Zdjęcie</th><th>Tytuł</th><th>Cena Brutto</th><th>Akcja</th>
                </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        // Obliczamy cenę brutto (netto + VAT) zgodnie z instrukcją [cite: 107]
        $cena_brutto = $row['cena_netto'] + ($row['cena_netto'] * ($row['podatek_vat'] / 100));
        
        $output .= '<tr>
            <td><img src="'.$row['zdjecie_link'].'" width="100"></td>
            <td style="font-weight: bold;">'.$row['tytul'].'</td>
            <td>'.number_format($cena_brutto, 2).' zł</td>
            <td>
                <a href="index.php?id=koszyk&action=add&id_prod='.$row['id'].'" 
                   style="padding: 5px 10px; background: #6C1D1C; color: white; text-decoration: none; border-radius: 4px;">
                   Dodaj do koszyka
                </a>
            </td>
        </tr>';
    }
    $output .= '</table>';
    return $output;
}

/**
 * Dodaje produkt do sesji
 */
function DodajDoKoszyka($id_prod, $ile_sztuk = 1) {
    // 1. Inicjalizacja licznika, jeśli nie istnieje
    if (!isset($_SESSION['count'])) {
        $_SESSION['count'] = 0;
    }

    //Sprawdzenie, czy produkt już jest w koszyku i tylko zwiększa ilość, jeżeli tak
    for ($i = 0; $i < $_SESSION['count']; $i++) {
        if (isset($_SESSION[$i . '_1']) && $_SESSION[$i . '_1'] == $id_prod) {
            $_SESSION[$i . '_2'] += $ile_sztuk;
            return; 
        }
    }

    //Dodanie nowego produktu jeżeli go jednak nie ma w koszyku
    $nr = $_SESSION['count'];
    
    $_SESSION[$nr . '_0'] = $nr;
    $_SESSION[$nr . '_1'] = $id_prod;
    $_SESSION[$nr . '_2'] = $ile_sztuk;
    $_SESSION[$nr . '_3'] = time();

    $_SESSION['count']++;
}

/**
 * Usuwa produkt z sesji 
 */
function UsunZKoszyka($nr) {
    unset($_SESSION[$nr.'_0']);
    unset($_SESSION[$nr.'_1']);
    unset($_SESSION[$nr.'_2']);
    unset($_SESSION[$nr.'_3']);
}

/**
 * Wyświetla koszyk i sumuje wartości Brutto 
 */
function PokazKoszyk($link) {
    $suma_brutto = 0;
    $output = '<h2>Twój Koszyk</h2>';
    $output .= '<table border="1" style="width:100%; border-collapse: collapse; text-align: center; background: #f9f9f9;">
                <tr><th>Produkt</th><th>Cena Brutto</th><th>Ilość</th><th>Wartość</th><th>Akcja</th></tr>';

    if (!isset($_SESSION['count'])) return "<p>Koszyk jest pusty.</p>";

    $jest_cos = false;
    for ($i = 0; $i <= $_SESSION['count']; $i++) {
        if (isset($_SESSION[$i.'_1'])) {
            $jest_cos = true;
            $id = $_SESSION[$i.'_1'];
            $ile = $_SESSION[$i.'_2'];

            $res = mysqli_query($link, "SELECT tytul, cena_netto, podatek_vat FROM produkty WHERE id = $id LIMIT 1");
            if ($row = mysqli_fetch_assoc($res)) {
                $cena_b = $row['cena_netto'] + ($row['cena_netto'] * ($row['podatek_vat'] / 100));
                $wartosc = $cena_b * $ile;
                $suma_brutto += $wartosc;

                $output .= '<tr>
                    <td>'.$row['tytul'].'</td>
                    <td>'.number_format($cena_b, 2).' zł</td>
                    <td>'.$ile.'</td>
                    <td>'.number_format($wartosc, 2).' zł</td>
                    <td><a href="index.php?id=koszyk&action=remove&item_id='.$i.'">Usuń</a></td>
                </tr>';
            }
        }
    }

    if (!$jest_cos) return "<p>Koszyk jest pusty.</p>";

    $output .= '<tr><td colspan="3" align="right"><strong>SUMA BRUTTO:</strong></td><td colspan="2"><strong>'.number_format($suma_brutto, 2).' zł</strong></td></tr>';
    $output .= '</table><br><a href="index.php?id=sklep">Powrót do sklepu</a>';
    return $output;
}

?>