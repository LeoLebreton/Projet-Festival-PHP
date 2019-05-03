<?php

/* 
 * Tester la lecture du contenu d'un fichier .i;i
 */
$ficIni = __DIR__."/sample.ini";
?>
<h3>Test de la fonction parse_ini</h3>
<h4>Nom du fichier : <?= $ficIni ?></h4>

<?php

define('BIRD', 'Dodo bird');

// Analyse sans sections
$ini_array = parse_ini_file("$ficIni");
var_dump($ini_array);

// Analyse avec sections
//$ini_array = parse_ini_file($ficIni, true);
//var_dump($ini_array);

$paramCnxBdd = parse_ini_file(__DIR__."/../../includes/parametres.ini");
var_dump($paramCnxBdd);
?>

