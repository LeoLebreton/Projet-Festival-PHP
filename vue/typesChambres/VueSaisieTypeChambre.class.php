<?php

namespace vue\typesChambres;

use vue\VueGenerique;
use modele\metier\TypeChambre;

/**
 * Description Page de création/modification d'un type de chambre donné
 * @author prof
 * @version 2018
 */
class VueSaisieTypeChambre extends VueGenerique {

    /** @var TypeChambre type de chambre à modifier */
    private $unTypeChambre;

    /** @var string ="creer" ou = "modifier" en fonction de l'utilisation du formulaire */
    private $actionRecue;

    /** @var string ="validerCreer" ou = "validerModifier" en fonction de l'utilisation du formulaire */
    private $actionAEnvoyer;

    /** @var string à afficher en tête du tableau */
    private $message;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <form method="POST" action="index.php?controleur=typesChambres&action=<?= $this->actionAEnvoyer ?>">
            <br>
            <table width="40%" cellspacing="0" cellpadding="0" class="tabNonQuadrille">

                <tr class="enTeteTabNonQuad">
                    <td colspan="3"><strong><?= $this->message ?></strong></td>
                </tr>
                <?php
                // En cas de création, l'id est accessible sinon l'id est dans un champ
                // caché
                if ($this->actionRecue == "creer") {
                    // On a le souci de ré-afficher l'id tel qu'il a été saisi
                    ?>
                    <tr class="ligneTabNonQuad">
                        <td> Id*: </td>
                        <td><input type="text" value="<?= $this->getUnTypeChambre()->getId() ?>" name="id" size ="2"></td>
                    </tr>
                    <?php
                } else {
                    // sinon l'id est dans un champ caché 
                    ?>
                    <tr class="autreLigne">
                        <td><input type="hidden" value="<?= $this->getUnTypeChambre()->getId() ?>" name="id"></td><td></td>
                    </tr>
                    <?php
                }
                ?>

                <tr class="ligneTabNonQuad">
                    <td> Libellé*: </td>
                    <td><input type="text" value="<?= $this->getUnTypeChambre()->getLibelle() ?>" name="libelle" size="30" maxlength="25"></td>
                </tr>
            </table>
            <table align="center" cellspacing="15" cellpadding="0">
                <tr>
                    <td align="right"><input type="submit" value="Valider" name="valider">
                    </td>
                    <td align="left"><input type="reset" value="Annuler" name="annuler"> </td>
                </tr>
            </table>
            <a href="index.php?controleur=typesChambres&action=liste">Retour</a>
        </form>        
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
