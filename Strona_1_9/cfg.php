<?php
	/*dane dostępu do bazy danych*/
	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$baza = 'moja_strona';
	/* konto administratora do panelu CSM */
	$login = 'szulc.w@o2.pl';
    $pass = 'ZXCASDqwe123';
	
	/* połaczenie do bazy danych MySQL */
	$link = mysqli_connect($dbhost,$dbuser,$dbpass);
	if (!$link) echo '<b>przerwane polaczenie</b>';
	if(!mysqli_select_db($link,$baza)) echo 'nie wybrano bazy';

?>