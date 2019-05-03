<?php
/**
 * Description Page à afficher en cas de demande d'action non autorisée (utilisateur non connecté)
 * @author prof
 * @version 2018
 */
namespace vue\accueil;

use vue\VueGenerique;

class VueActionNonAutorisee extends VueGenerique {        

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <br>
        <h2>Vous devez vous authentifier</h2>
        <?php
        include $this->getPied();
    }

}
