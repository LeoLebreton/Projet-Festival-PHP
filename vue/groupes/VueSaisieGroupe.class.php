<?php

namespace vue\groupes;

use vue\VueGenerique;
use modele\metier\Groupe;

/**
 * Description Page de saisie/modification d'un établissement donné
 * @author prof
 * @version 2018
 */
class VueSaisieGroupe extends VueGenerique {

    /** @var Groupe groupe à afficher */
    private $unGroupe;

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
        <form method="POST" action="index.php?controleur=groupes&action=<?= $this->actionAEnvoyer ?>">
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
                        <td><input type="text" value="<?= $this->unGroupe->getId() ?>" name="id" size ="10" maxlength="8"></td>
                    </tr>
                    <?php
                } else {
                    // sinon l'id est dans un champ caché
                    ?>
                    <tr>
                        <td><input type="hidden" value="<?= $this->unGroupe->getId(); ?>" name="id"></td><td></td>
                    </tr>
                    <?php
                }
                ?>
                <tr class="ligneTabNonQuad">
                    <td> Nom*: </td>
                    <td><input type="text" value="<?= $this->unGroupe->getNom() ?>" name="nom" size="50"
                               maxlength="45" ></td><!--onkeypress="return verif(event);"-->
                </tr>
              <!--  <script type="text/javascript">
                    function verif(evt) {
                        var keyCode = evt.which ? evt.which : evt.keycode;
                        var accept = "aqwzsxedcrfvtgbyhnjuikolpmAQWZSXEDCRFVTGBYHNJUIKOLPM";
                        if (accept.indexOf(String.fromCharCode(keyCode)) >= 0 || (evt.keyCode ===8) ){
                            return true;
                        } else {
                            return false;
                        }-->
                    }
                </script>
                <tr class="ligneTabNonQuad">
                    <td> Identité du Responsable*: </td>
                    <td><input type="text" value="<?= $this->unGroupe->getIdentite() ?>" name="identiteResponsable" size="50"
                               maxlength="45" ></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Adresse Postale*: </td>
                    <td><input type="text" value="<?= $this->unGroupe->getAdresse() ?>" name="adressePostale" size="50"
                               maxlength="45" ></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Nombre de personnes*: </td>
                    <td><input type="text" value="<?= $this->unGroupe->getNbPers() ?>" name="nombrePersonnes"
                               size="50" maxlength="45"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Nom du pays*: </td>
                    <td><input type="text" value="<?= $this->unGroupe->getNomPays() ?>" name="nomPays"
                               size="7" maxlength="50"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Ville*: </td>
                    <td><input type="text" value="<?= $this->unGroupe->getHebergement() ?>" name="ville" size="40"
                               maxlength="35"></td>
                </tr>


                <tr class="ligneTabNonQuad">
                    <td> Hébergement*: </td>
                    <td>
                        <?php
                        if ($this->unGroupe->getHebergement() == 1) {
                            $checked1 = "checked=\"checked\"";
                            $checked0 = "";
                        } else {
                            $checked0 = "checked=\"checked\"";
                            $checked1 = "";
                        }
                        ?>
                        <input type="radio" name="hebergement" value="1" <?= $checked1 ?>>
                        Oui
                        <input type="radio" name="hebergement" value="0" <?= $checked0 ?>>
                        Non
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
            <a href="index.php?controleur=groupes&action=liste">Retour</a>
        </form>
        <?php
        include $this->getPied();
    }

    public function setUnGroupe(Groupe $unGroupe) {
        $this->unGroupe = $unGroupe;
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
