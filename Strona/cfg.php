<?php

	$dbhost = 'localhost';
	$dbuser = 'root';
	$dbpass = '';
	$baza = 'moja_strona';
	$login = 'admin_root@localhost.com';
    $pass = 'ZXCASDqwe123';
	
	$link = mysqli_connect($dbhost,$dbuser,$dbpass);
	if (!$link) echo '<b>przerwane polaczenie</b>';
	if(!mysqli_select_db($link,$baza)) echo 'nie wybrano bazy';

?>