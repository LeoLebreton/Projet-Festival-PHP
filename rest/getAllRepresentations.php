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

// utilisation de la couche DAO pour récupérer les représentations dans la BDD
$lesRepresentations = RepresentationDAO::getAll();

// génération du XML
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
//echo "<representations>\n";

// BOUCLE SUR LES REPRESENTATIONS 
foreach ($lesRepresentations as $uneRepresentation) {
    echo "\t<representation>\n";

    echo "\t\t<id>" . $uneRepresentation->getId() . "</id>\n";
    echo "\t\t<groupe>" . $uneRepresentation->getIdGroupe()->getNom() . "</groupe>\n";
    echo "\t\t<lieu>" . $uneRepresentation->getIdLieu()->getNom() . "</lieu>\n";
    echo "\t\t<date>" . $uneRepresentation->getDate() . "</date>\n";
    echo "\t\t<heure_debut>" . $uneRepresentation->getHeureDebut() . "</heure_debut>\n";
    echo "\t\t<heure_fin>" . $uneRepresentation->getHeureFin() . "</heure_fin>\n";

    echo "\t</representation>\n";
}
//echo "</representations>";
    Bdd::deconnecter();
    Session::arreter();
?>