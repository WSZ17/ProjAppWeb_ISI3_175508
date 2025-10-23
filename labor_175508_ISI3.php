<?php

	$nr_indeksu='175508';
	$nrGrupy='ISI3';

	echo 'Weronika Szulc ' . $nr_indeksu . ' grupa ' . $nrGrupy . '<br /><br />';
	echo 'Zastosowanie metody include()<br />';
	
	echo '<br />';
	
	#echo "A $color $fruit";

	include 'vars.php';

	echo "A $color $fruit";
	
	$a = 5;
	$b = 3;
	$i = 1;
	
	echo '<br />';
	
	echo 'Zastosowanie funkcji if()<br />';
	if ($a > $b)
		echo "a is bigger than b<br />";

	echo '<br />';

	echo 'Zastosowanie funkcji else()<br />';
	if ($a > $b) {
		echo "a is greater than b<br />";
	} else {
		echo "a is NOT greater than b<br />";
	}
	
	echo '<br />';
	
	echo 'Zastosowanie funkcji elseif()<br />';
	if ($a > $b) {
		echo "a is bigger than b<br />";
	} elseif ($a == $b) {
		echo "a is equal to b<br />";
	} else {
		echo "a is smaller than b<br />";
	}
	
	echo '<br />';
	
	echo 'Zastosowanie funkcji switch()<br />';
	switch ($i) {
    case 0:
        echo "i equals 0<br />";
        break;
    case 1:
        echo "i equals 1<br />";
        break;
    case 2:
        echo "i equals 2<br />";
        break;
		
	}
	
	echo '<br />';

	echo 'Zastosowanie funkcji while()<br />';
	echo 'Pierwszy sposób<br />';
	while ($i <= 10) {
		echo $i++;  
	}
	echo '<br />';
	echo 'Drugi sposób<br />';
	while ($i <= 10):
		echo $i;
		$i++;
	endwhile;

	echo '<br />';
	
	echo 'Zastosowanie funkcji for()<br />';
	echo 'Pierwszy sposób<br />';

	for ($i = 1; $i <= 10; $i++) {
		echo $i;
	}
	echo '<br />';
	echo 'Drugi sposób<br />';
	for ($i = 1; ; $i++) {
		if ($i > 10) {
			break;
		}
		echo $i;
	}
	echo '<br />';
	echo 'Trzeci sposób<br />';
	$i = 1;
	for (; ; ) {
		if ($i > 10) {
			break;
		}
		echo $i;
		$i++;
	}
	echo '<br />';
	echo 'Czwarty sposób<br />';
	for ($i = 1, $j = 0; $i <= 10; $j += $i, print $i, $i++);
	
	echo '<br />';
	
	$name = 'Me';
	
	echo 'Zastosowanie funkcji $_GET<br />';
	/*http://localhost/labor_175508_ISI3.php/?name=Me*/
	echo 'Hello ' . htmlspecialchars($_GET["name"]) . '!';
	
	echo '<br />';
	
	echo 'Zastosowanie funkcji $_POST<br />';
	
	echo 'Hello ' . htmlspecialchars($_POST["name"]) . '!';
	
	echo '<br />';
	
	echo 'Zastosowanie funkcji $_SESSION<br />';
?>