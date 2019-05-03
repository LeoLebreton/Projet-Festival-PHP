<?php

/**
 * Description of ControleurGenerique
 * Contrôleur abstrait, servant de modèle aux contrôleurs de chaque module
 * Chaque contrôleur doit redéfinir la méthode "defaut" appelée en cas d'absence 
 * de paramètre action 
 * ET une méthode par action
 * @author prof
 * @version 2018
 */

namespace controleur;

use vue\VueGenerique;

abstract class ControleurGenerique {

    /** @var VueGenerique Association OneToOne Controleur -> Vue */
    protected $vue;

    /** @var string identification de la version de l'application à afficher */
    protected $version;

    /**
     * Action par défaut. Devra être implémentée par chaque contrôleur
     */
    public abstract function defaut();

    /**
     * Code commun à tous les contrôleurs : préparer une vue autorisée, avec un utilisateur authentifié
     * Prérequis : l'attribut $vue du contrôleur a été instancié
     *      - activer les onglets et l'action demandée, 
     *      - fournir à la vue l'identité de l'utilisateur pour affichage
     */
    protected function controlerVueAutorisee() {
        $this->vue->setLienOngletActif(true);
        $this->vue->setEstConnecte(true);
        $utilisateurConnecte = SessionAuthentifiee::getUtilisateur();
        $this->vue->setIdentite($utilisateurConnecte->getCivilite() . " " . $utilisateurConnecte->getPrenom() . " " . $utilisateurConnecte->getNom());
    }

    /**
     * Code commun à tous les contrôleurs : préparer une vue non autorisée, sans utilisateur authentifié
     * Prérequis : l'attribut $vue du contrôleur a été instancié
     */
    protected function controlerVueNonAutorisee() {
        $this->vue->setLienOngletActif(false);
        $this->vue->setEstConnecte(false);
        $this->vue->setIdentite("");
    }

    // ACCESSEURS 
    public function setVue(VueGenerique $vue) {
        $this->vue = $vue;
    }

    public function setVersion(string $version) {
        $this->version = $version;
    }

    /**
     * Ce gestionnaire est une méthode magique qui est appelée lorsqu'une méthode inexistante
     * est invoquée sur une instance de cette classe
     * @param string $name : nom de la méthode
     * @param type $arguments : ses paramètres effectifs
     * @throws \Exception
     */
    function __call($name, $arguments) {
//        controleur\GestionErreurs::ajouter("Fonctionnalité indisponible");
//        header("Location: index.php");
        throw new \Exception("Fonctionnalité manquante :$name:");
    }

}
