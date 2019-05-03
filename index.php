<?php

/**
 * Contrôleur principal
 * reçoit au moins deux paramètres par l'URL :
 *  "controleur" : classe Controleur à instancier
 *  "action" : méthode à appeler
 * @author prof
 * @version 2018
 */
use controleur\GestionParametres;
use controleur\Session;
use controleur\SessionAuthentifiee;
//use \Exception;
use controleur\GestionErreurs;

require_once __DIR__ . "/includes/autoload.inc.php";

Session::demarrer();
// lecture des paramètres de l'application
GestionParametres::initialiser();
include GestionParametres::racine() . "includes/fonctionsUtilitaires.inc.php";
include GestionParametres::racine() . "includes/fonctionsDatesTimes.inc.php";

// analyse de l'URL (paramètres GET)
if (isset($_GET['controleur'])) {
    $controleur = $_GET['controleur'];
} else {
    // le contrôleur par défaut est CtrlAccueil
    $controleur = 'accueil';
}
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    // la méthode par défaut est defaut()
    $action = 'defaut';
}

// Contrôle de l'authentification
// seules la page d'accueil ainsi que la page d'authentification sont accessible hors connexion
if (!SessionAuthentifiee::estConnecte() && $controleur != 'accueil'&& $controleur != 'authentification') {
    $controleur = 'accueil';
    $action = 'refuser';
}


// Instanciation du contrôleur et appel de sa méthode d'action
try {
    // Construction du nom de la classe contrôleur
    $classeControleur = "controleur\Ctrl" . ucfirst($controleur);
    // Instanciation d'un obje contrôleur
    /* @var controleur\ControleurGenerique $ctrl  */
    $ctrl = new $classeControleur();
    $ctrl->setVersion("v. 2018-2019");
    // Appel de la méthode d'action de ce contrôleur
    $ctrl->$action();
} catch (\Exception $e) {
    // en cas d'erreur (contrôleur ou action inexistante)
    GestionErreurs::ajouter("Module indisponible :$controleur:- " . $e->getMessage());
    header("Location: index.php");
}


