<?php
require_once "../../includes/fonctionsUtilitaires.inc.php";
$ch1 = "AzeRty44";
$ch2 = "AzeRty_44";
$ch3 = "";
?>
<h3>Test unitaire de la fonction estAlphaNumerique</h3>
cas 1 - chaine alpha : <?= $ch1 ?> : <?= var_dump(estAlphaNumerique($ch1)) ?><br/>
cas 2 - chaine non alpha : <?= $ch2 ?> : <?= var_dump(estAlphaNumerique($ch2)) ?><br/>
cas 3 - chaine vide : <?= $ch3 ?> : <?= var_dump(estAlphaNumerique($ch3)) ?><br/>


