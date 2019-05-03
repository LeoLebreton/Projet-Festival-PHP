<?php

namespace vue\typesChambres;

use vue\VueGenerique;
use modele\metier\TypeChambre;

/**
 * Description Page de suppression d'un type de chambre donné
 * @author prof
 * @version 2018
 */
class VueSupprimerTypeChambre extends VueGenerique {

    /** @var TypeChambre type de chambre à modifier */
    private $unTypeChambre;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <br><center>Voulez-vous vraiment supprimer le type de chambre 
        <?= $this->unTypeChambre->getId() ?> <?= $this->unTypeChambre->getLibelle() ?> ?
            <h3><br>
                <a href="index.php?controleur=typesChambres&action=validerSupprimer&id=<?= $this->unTypeChambre->getId() ?>">
                    Oui</a>
                &nbsp; &nbsp; &nbsp; &nbsp;
                <a href="index.php?controleur=typesChambres">Non</a></h3></center>
        <?php
        include $this->getPied();
    }

    public function getUnTypeChambre(): TypeChambre {
        return $this->unTypeChambre;
    }

    public function getActionRecue() {
        return $this->actionRecue;
    }

    public function getActionAEnvoyer() {
        return $this->actionAEnvoyer;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setUnTypeChambre(TypeChambre $unTypeChambre) {
        $this->unTypeChambre = $unTypeChambre;
    }

    public function setActionRecue($actionRecue) {
        $this->actionRecue = $actionRecue;
    }

    public function setActionAEnvoyer($actionAEnvoyer) {
        $this->actionAEnvoyer = $actionAEnvoyer;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

}
