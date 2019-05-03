<?php


namespace vue\representation;

use vue\VueGenerique;
use modele\metier\Representation;
/**
 * Description of VueSupprimmerGroupe
 *
 * @author btssio
 */
class VueSupprimmerRepresentation extends VueGenerique{

    /** @var Representation identificateur de la representation à supprimmer */
    private $uneRepresentation;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
            <br><center>Voulez-vous vraiment supprimer la representation <?= $this->uneRepresentation->getId() ?> ?
            <h3><br>
                <a href="index.php?controleur=representations&action=validerSupprimer&id=<?= $this->uneRepresentation->getId() ?>">Oui</a>
                &nbsp; &nbsp; &nbsp; &nbsp;
                <a href="index.php?controleur=representations">Non</a></h3>
        </center>
        <?php
        include $this->getPied();
    }

    function setUneRepresentation(Representation $uneRepresentation) {
        $this->uneRepresentation = $uneRepresentation;
    }

}
