<?php

namespace vue\etablissements;

use vue\VueGenerique;
use modele\metier\Etablissement;

/**
 * Description Page de saisie/modification d'un établissement donné
 * @author prof
 * @version 2018
 */
class VueSaisieEtablissement extends VueGenerique {

    /** @var Etablissement établissement à afficher */
    private $unEtablissement;

    /** @var string ="creer" ou = "modifier" en fonction de l'utilisation du formulaire */
    private $actionRecue;

    /** @var string ="validerCreer" ou = "validerModifier" en fonction de l'utilisation du formulaire */
    private $actionAEnvoyer;

    /** @var string à afficher en tête du tableau */
    private $message;

    /** @var Array tableau des civilités */
    private $tabCivilite = array("Monsieur", "Madame", "Mademoiselle");

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <form method="POST" action="index.php?controleur=etablissements&action=<?= $this->actionAEnvoyer ?>">
            <br>
            <table width="85%" cellspacing="0" cellpadding="0" class="tabNonQuadrille">

                <tr class="enTeteTabNonQuad">
                    <td colspan="3"><strong><?= $this->message ?></strong></td>
                </tr>

                <?php
                // En cas de création, l'id est accessible à la saisie           
                if ($this->actionRecue == "creer") {
                    // On a le souci de ré-afficher l'id tel qu'il a été saisi
                    ?>
                    <tr class="ligneTabNonQuad">
                        <td> Id*: </td>
                        <td><input type="text" value="<?= $this->unEtablissement->getId() ?>" name="id" size ="10" maxlength="8"></td>
                    </tr>
                    <?php
                } else {
                    // sinon l'id est dans un champ caché 
                    ?>
                    <tr>
                        <td><input type="hidden" value="<?= $this->unEtablissement->getId(); ?>" name="id"></td><td></td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="ligneTabNonQuad">
                    <td> Nom*: </td>
                    <td><input type="text" value="<?= $this->unEtablissement->getNom() ?>" name="nom" size="50" 
                               maxlength="45" onkeypress="return verif(event);"></td>
                </tr>
                <script type="text/javascript">
                    function verif(evt) {
                        var keyCode = evt.which ? evt.which : evt.keycode;
                        var accept = "aqwzsxedcrfvtgbyhnjuikolpmAQWZSXEDCRFVTGBYHNJUIKOLPM";
                        if (accept.indexOf(String.fromCharCode(keyCode)) >= 0 || (evt.keyCode ==8) ){
                            return true;
                        } else {
                            return false;
                        }
                    }
                </script>
                <tr class="ligneTabNonQuad">
                    <td> Adresse*: </td>
                    <td><input type="text" value="<?= $this->unEtablissement->getAdresse() ?>" name="adresseRue" 
                               size="50" maxlength="45"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Code postal*: </td>
                    <td><input type="text" value="<?= $this->unEtablissement->getCdp() ?>" name="codePostal" 
                               size="7" maxlength="5"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Ville*: </td>
                    <td><input type="text" value="<?= $this->unEtablissement->getVille() ?>" name="ville" size="40" 
                               maxlength="35"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Téléphone*: </td>
                    <td><input type="tel" placeholder="0123456789"value="<?= $this->unEtablissement->getTel() ?>" name="tel" size ="20" 
                               maxlength="10">Numéro ne dépassant pas 10 chiffres</td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> E-mail: </td>
                    <td><input type="text" value="<?= $this->unEtablissement->getEmail() ?>" name=
                               "adresseElectronique" size ="75" maxlength="70"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Type*: </td>
                    <td>
                        <?php
                        if ($this->unEtablissement->getTypeEtab() == 1) {
                            $checked1 = "checked=\"checked\"";
                            $checked0 = "";
                        } else {
                            $checked0 = "checked=\"checked\"";
                            $checked1 = "";
                        }
                        ?> 
                        <input type="radio" name="type" value="1" <?= $checked1 ?>>  
                        Etablissement Scolaire
                        <input type="radio" name="type" value="0" <?= $checked0 ?>>
                        Autre
                    </td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td colspan="2" ><strong>Responsable:</strong></td>            
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Civilité*: </td>
                    <td> <select name="civiliteResponsable">
                            <?php
                            for ($i = 0; $i < 3; $i = $i + 1) {
                                $selected = "";
                                if ($this->tabCivilite[$i] == $this->unEtablissement->getCiviliteResp()) {
                                    $selected = "selected=\"selected\"";
                                }
                                ?>
                                <option <?= $selected ?>><?= $this->tabCivilite[$i] ?></option>
                                <?php
                            }
                            ?>
                        </select>&nbsp; &nbsp; &nbsp; &nbsp; Nom*: 
                        <input type="text" value="<?= $this->unEtablissement->getNomResp() ?>" 
                               name="nomResponsable" size="26" maxlength="25" onkeyup="this.value=this.value.toUpperCase()">
                        &nbsp; &nbsp; &nbsp; &nbsp; Prénom: 
                        <input type="text"  value="<?= $this->unEtablissement->getPrenomResp() ?>" 
                               name="prenomResponsable" size="26" maxlength="25">
                    </td>
                </tr>
            </table>

            <table align="center" cellspacing="15" cellpadding="0">
                <tr>
                    <td align="right"><input type="submit" value="Valider" name="valider">
                    </td>
                    <td align="left"><input type="reset" value="Annuler" name="annuler">
                    </td>
                </tr>
            </table>
            <a href="index.php?controleur=etablissements&action=liste">Retour</a>
        </form>
        <?php
        include $this->getPied();
    }

    public function setUnEtablissement(Etablissement $unEtablissement) {
        $this->unEtablissement = $unEtablissement;
    }


    public function setActionRecue(string $action) {
        $this->actionRecue = $action;
    }

    public function setActionAEnvoyer(string $action) {
        $this->actionAEnvoyer = $action;
    }

    public function setMessage(string $message) {
        $this->message = $message;
    }

}
