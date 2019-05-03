<?php

// renvoie l'ensemble des groupes sous forme de flux XML
//header("Content-type: text/xml");

/**
 * getAllGroupesXML.php
 */
use modele\dao\RepresentationDAO;
use modele\dao\LieuDAO;
use modele\dao\GroupeDAO;
use modele\dao\Bdd;
use controleur\Session;

//require_once __DIR__ . '/../includes/autoload.php';
require_once __DIR__ . '/../includes/autoload.inc.php';

Session::demarrer();
Bdd::connecter();

// utilisation de la couche DAO pour récupérer les groupes dans la BDD
$lesGroupes = GroupeDAO::getAll();

// génération du XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo "<groupes>\n";

// BOUCLE SUR LES GROUPES 
foreach ($lesGroupes as $unGroupe) {
    echo "\t<groupe>\n";

    echo "\t\t<id>" . $unGroupe->getId() . "</id>\n";
    echo "\t\t<nom>" . $unGroupe->getNom() . "</nom>\n";
    echo "\t\t<identite>" . $unGroupe->getIdentite() . "</identite>\n";
    echo "\t\t<adresse>" . $unGroupe->getAdresse() . "</adresse>\n";
    echo "\t\t<nb-pers>" . $unGroupe->getNbPers() . "</nb-pers>\n";
    echo "\t\t<nom-pays>" . $unGroupe->getNomPays() . "</nom-pays>\n";

    echo "\t</groupe>\n";
}
echo "</groupes>";
    Bdd::deconnecter();
    Session::arreter();
?>

