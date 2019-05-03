<?php

namespace vue\etablissements;

use vue\VueGenerique;
use modele\metier\Etablissement;

/**
 * Page de suppression d'un établissement donné
 * @author prof
 * @version 2018
 */
class VueSupprimerEtablissement extends VueGenerique {

    /** @var Etablissement identificateur de l'établissement à afficher */
    private $unEtablissement;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <br><center>Voulez-vous vraiment supprimer l'établissement <?= $this->unEtablissement->getNom() ?> ?
            <h3><br>
                <a href="index.php?controleur=etablissements&action=validerSupprimer&id=<?= $this->unEtablissement->getId() ?>">Oui</a>
                &nbsp; &nbsp; &nbsp; &nbsp;
                <a href="index.php?controleur=etablissements">Non</a></h3>
        </center>
        <?php
        include $this->getPied();
    }

    function setUnEtablissement(Etablissement $unEtablissement) {
        $this->unEtablissement = $unEtablissement;
    }

}
