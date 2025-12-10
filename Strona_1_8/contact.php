<?php
include ('cfg.php');

/* ===== Import przestrzeni nazw PHPMailer ===== */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

/* ===== Dołączenie plików biblioteki PHPMailer ===== */
require_once 'phpmailer\PHPMailer-master\PHPMailer-master\src\Exception.php';
require_once 'phpmailer\PHPMailer-master\PHPMailer-master\src\PHPMailer.php';
require_once 'phpmailer\PHPMailer-master\PHPMailer-master\src\SMTP.php';

/* ==================================================
   Funkcja PokazKontakt()
   - wyświetla formularz kontaktowy do wysłania e-maila
   - obsługuje wysyłkę wiadomości po submit
   ================================================== */

function PokazKontakt(){
	
	/* HTML formularza kontaktowego */
    $wynik = '
        <h2 class="heading">Formularz Kontaktowy</h2>
        <form method="post" action="index.php?id=contact">
            <div class="form-group">
                <label for="email"> Twój adres E-mail:</label>
                <input type="email" id="email" name="email" required class="form-control">
            </div>
            <div class="form-group">
                <label for="temat">Temat:</label>
                <input type="text" id="temat" name="temat" required class="form-control">
            </div>
            <div class="form-group">
                <label for="tresc">Treść Wiadomości:</label>
                <textarea id="tresc" name="tresc" rows="8" required class="form-control"></textarea>
            </div>
            <button type="submit" name="kontakt_submit" class="form-submit-btn">Wyślij Wiadomość</button>
        </form>
    ';
	
	/* Jeśli formularz został wysłany */
    if (isset($_POST['kontakt_submit'])) {
        $odbiorca = 'szulc.w@o2.pl';
        return WyslijMailKontakt($odbiorca);
    }
	
	/* Wyświetlenie formularza */
    return $wynik;
}


/* ==================================================
   Funkcja WyslijMailKontakt()
   - wysyła e-mail przy użyciu PHPMailer
   ================================================== */
function WyslijMailKontakt($odbiorca) {

	/* Dane konta e-mail nadawcy */
    $prawdziwy_nadawca = 'szulc.w@o2.pl'; 
    $haslo_o2 = '****'; //

	/* Sprawdzenie czy wszystkie pola formularza są wypełnione */
    if (empty($_POST['temat']) || empty($_POST['tresc']) || empty($_POST['email'])) 
	{
        echo '<p style="color: red;">[nie_wypelniles_pola]: Musisz wypełnić wszystkie pola formularza.</p>'; 
        
		return PokazKontakt(); 
    } 
	else {
		
		/* Utworzenie obiektu PHPMailer */
        $mail = new PHPMailer(true);

        try {
			/* Konfiguracja SMTP */
            $mail->isSMTP();
            $mail->Host       = 'poczta.o2.pl';
            $mail->SMTPAuth   = true;
            $mail->Username   = $prawdziwy_nadawca;
            $mail->Password   = $haslo_o2;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

			/* Dane nadawcy i odbiorcy */
            $mail->setFrom($prawdziwy_nadawca, 'Formularz Kontaktowy');
            $mail->addAddress($odbiorca);
            $mail->addReplyTo($_POST['email']); // adres nadawcy formularza

			/* Treść wiadomości */
            $mail->isHTML(false);
            $mail->Subject = $_POST['temat'];
            $mail->Body    = $_POST['tresc'];
            
			/* Opcje SSL (wyłączona weryfikacja certyfikatu) */
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
			
			/* Wysłanie wiadomości */
            $mail->send();
			
            return '<p style="color: green;">[wiadomosc_wyslana]: Twoja wiadomość została wysłana pomyślnie.</p>';
            
        } catch (Exception $e) {
			/* Obsługa błędów wysyłki */
            return '<p style="color: red;">Błąd wysyłki: ' . $mail->ErrorInfo . '</p>';
        }
    }
}

/* ==================================================
   Funkcja PrzypomnijHaslo()
   - wysyła hasło administratora na e-mail
   ================================================== */

function PrzypomnijHaslo() {
	
	/* Dane administratora z cfg.php */
    global $login, $pass;
    
	/* Dane konta e-mail nadawcy */
    $prawdziwy_nadawca = 'szulc.w@o2.pl'; 
    $haslo_o2 = '****';

	/* Formularz przypomnienia hasła stworzony z pomocą HTML */
    $form_haslo = '
        <h2 class="heading">Przypomnienie Hasła</h2>
        <form method="post" action="index.php?id=forgot_pass">
            <div class="form-group">
                <label for="email_admin">E-mail Konta Administratora:</label>
                <input type="email" id="email_admin" name="email_admin" required class="form-control">
            </div>
            <button type="submit" name="przypomnij_submit" class="form-submit-btn">Wyślij Hasło</button>
        </form>
    ';

	/* Jeśli formularz został wysłany */
    if (isset($_POST['przypomnij_submit'])) {
        $email_admin = $_POST['email_admin'];

		/* Sprawdzenie czy e-mail zgadza się z loginem admina */
        if ($email_admin === $login) {
            
			$odbiorca = $email_admin;
            $mail = new PHPMailer(true);

            try {
				/* Konfiguracja SMTP */
                $mail->isSMTP();
                $mail->Host       = 'poczta.o2.pl';
                $mail->SMTPAuth   = true;
                $mail->Username   = $prawdziwy_nadawca;
                $mail->Password   = $haslo_o2;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port       = 465;
                $mail->CharSet    = 'UTF-8';
				
				/* Dane wiadomości */
                $mail->setFrom($prawdziwy_nadawca, 'System Przypominania Hasel');
                $mail->addAddress($odbiorca);

                $mail->isHTML(false); 
                $mail->Subject = 'Przypomnienie hasła do Panelu CMS';
                $mail->Body    = "Twoje hasło do panelu admina to: " . $pass . "\n\n(Uwaga: ta metoda jest niebezpieczna. Zmień hasło po zalogowaniu.)";
				
				/* Opcje SSL */
                $mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
                    )
                );
				
				/* Wysłanie e-maila */
                $mail->send();
				
                return '<p style="color: green;">Hasło zostało wysłane na podany adres e-mail: ' . htmlspecialchars($email_admin) . '.</p>';
                
            } catch (Exception $e) {
                return '<p style="color: red;">Błąd wysyłki hasła: ' . $mail->ErrorInfo . '</p>';
            }
        } else {
			
			/* Nieprawidłowy e-mail admina */
            return '<p style="color: red;">Podany adres e-mail nie jest powiązany z kontem administratora.</p>' . $form_haslo;
        }
    }

	/* Wyświetlenie formularza */
    return $form_haslo;
}
?>
