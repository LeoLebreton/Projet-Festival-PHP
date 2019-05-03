<?php

namespace vue\authentification;

use vue\VueGenerique;

/**
 * Description Page d'authentification d'un utilisateur
 * @author prof
 * @version 2018
 */
class VueSAuthentifier extends VueGenerique {

    /** @var string login à afficher */
    private $login;
    /** @var string mot de passe */
    private $mdp;

    /** @var string à afficher en tête du formulaire */
    private $message;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <form method="POST" action="index.php?controleur=authentification&action=authentifier">
            <br>
            <table width="30%" cellspacing="0" cellpadding="0" class="tabNonQuadrille">

                <tr class="enTeteTabNonQuad">
                    <td colspan="2"><strong><?= $this->message ?></strong></td>
                </tr>

                <tr class="ligneTabNonQuad">
                    <td> Login*: </td>
                    <td><input type="text" value="<?= $this->login ?>" name="login" size ="25" maxlength="30"></td>
                </tr>
                <tr class="ligneTabNonQuad">
                    <td> Mdp*: </td>
                    <td><input type="password" value="<?= $this->mdp ?>" name="mdp" size="25" maxlength="30"></td>
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
            <a href="index.php">Retour</a>
        </form>
        <?php
        include $this->getPied();
    }

    public function getLogin() {
        return $this->login;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    public function setMessage($message) {
        $this->message = $message;
    }



}
