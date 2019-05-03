<?php

namespace vue\groupes;

use vue\VueGenerique;
use modele\metier\Groupe;

/**
 * Page de suppression d'un établissement donné
 * @author prof
 * @version 2018
 */
class VueSupprimerGroupe extends VueGenerique {

    /** @var Groupe identificateur du groupe à afficher */
    private $unGroupe;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <br><center>Voulez-vous vraiment supprimer le groupe <?= $this->unGroupe->getNom() ?> ?
            <h3><br>
                <a href="index.php?controleur=groupes&action=validerSupprimer&id=<?= $this->unGroupe->getId() ?>">Oui</a>
                &nbsp; &nbsp; &nbsp; &nbsp;
                <a href="index.php?controleur=groupes">Non</a></h3>
        </center>
        <?php
        include $this->getPied();
    }

    function setUnGroupe(Groupe $unGroupe) {
        $this->unGroupe = $unGroupe;
    }

}