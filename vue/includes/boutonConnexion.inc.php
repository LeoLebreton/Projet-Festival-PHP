<?php
/**
 * Inclus dans debut.inc.php (première partie commune aux pages HTML)
 * Code permettant, en fonction de l'état de la session :
 * - soit d'afficher un bouton "Se connecter" et de signaler que les liens des onglets
 * ne doivent pas être fonctionnels ($lienOngletActif=false)
 * - soit d'afficher un bouton "Se déconnecter", suivi de l'identité de l'utilisateur connecté
 * et de signaler que les liens des onglets doivent être fonctionnels ($lienOngletActif=true)
 * @author prof
 * @version 2017 ; adaptation 2018 (MVC - objet)
 */

if (!$this->getEstConnecte()) {
    // Aucun utilisateur n'est actuellement authentifié pour cette session
    ?>
    <div class="entete_bouton" >
        <form id="frmIdentification" method="POST" action="index.php?controleur=authentification&action=saisirIdentification">
            <input type="submit" value="Se connecter"/>
        </form>
    </div>
    <?php
} else {
    // un utilisateur est actuellement authentifié pour cette session, son identité a été transmise à la vue par le contrôleur
    ?>
    <div class="entete_bouton" >
        <form id="frmIdentification" method="POST" action="index.php?controleur=authentification&action=seDeconnecter">
            <input type="submit" value="Se déconnecter"/>
        </form>
    </div>
    <div id="entete_identite"><?= $this->getIdentite() ?></div>
    <?php
}
