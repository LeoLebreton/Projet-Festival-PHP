<?php

/* 
 * Tester le contenu des variables super-globales
 * exemple d'URL de test : 
 * http://localhost/sites/2SLAM/FestivalPHP2018_2019_prof/test/divers/TestsPHP.php?action=tester
 */
?>
<h3>Test de la variable globale $_SERVER</h3>
cas 1 - PHP_SELF : <?= $_SERVER['PHP_SELF'] ?> <br/>
cas 2 - QUERY_STRING : <?= $_SERVER['QUERY_STRING'] ?> <br/>
cas 3 - SERVER_NAME : <?= $_SERVER['SERVER_NAME'] ?> <br/>
cas 4 - DOCUMENT_ROOT : <?= $_SERVER['DOCUMENT_ROOT'] ?> <br/>
cas 4 - constante __DIR__ : <?= __DIR__ ?> <br/>
