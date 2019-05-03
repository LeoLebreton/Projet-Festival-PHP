<?php

namespace vue;

use controleur\GestionParametres;

/**
 * Implémentation d'une classe vue pour ce projet
 * Toutes les vues en héritent
 * @author prof
 * @version 2018
 */
abstract class VueGenerique {

    /** @var string titre de la vue (dans debut.inc.php) */
    private $titre;

    /** @var string chemin d'accès vers le fichier d'inclusion pour l'entête  */
    private $entete;

    /** @var string chemin d'accès vers le fichier d'inclusion pour la partie centrale */
    private $centre;

    /** @var string chemin d'accès vers le fichier d'inclusion pour le pied de page */
    private $pied;

    /** @var string n° de version de l'application (dans debut.inc.php) */
    private $version;

    /** @var bool statut des liens des onglets ; =true => actif ; =false => inactif (dans debut.inc.php) */
    private $lienOngletActif;

    /** @var bool un utilisateur est-il authentifié sur cette session */
    private $estConnecte;
    
    /** @var string identité de l'utilisateur connecté (à afficher à coté du bouton) */
    private $identite;
    
    public function __construct() {
        // initialisation des valeurs par défaut
        $this->setTitre('Festival');
        $this->setEntete(GestionParametres::racine() . 'vue/includes/debut.inc.php');
        $this->setPied(GestionParametres::racine() . 'vue/includes/fin.inc.php');
    }

    /**
     *  Afficher la vue signifie l'inclure au flux de sortie HTML
     */
    public abstract function afficher();

    // ACCESSEURS ET MUTATEURS


    function getTitre(): string {
        return $this->titre;
    }

    function getEntete(): string {
        return $this->entete;
    }

    function getCentre(): string {
        return $this->centre;
    }

    function getPied(): string {
        return $this->pied;
    }

    function getVersion(): string {
        return $this->version;
    }

    function getLienOngletActif(): bool {
        return $this->lienOngletActif;
    }

    public function getEstConnecte() : bool {
        return $this->estConnecte;
    }

    public function getIdentite() : string {
        return $this->identite;
    }

    function setTitre(string $titre) {
        $this->titre = $titre;
    }

    function setEntete(string $entete) {
        $this->entete = $entete;
    }

    function setCentre(string $centre) {
        $this->centre = $centre;
    }

    function setPied(string $pied) {
        $this->pied = $pied;
    }

    function setVersion(string $version) {
        $this->version = $version;
    }

    function setLienOngletActif(bool $lienOngletActif) {
        $this->lienOngletActif = $lienOngletActif;
    }
    
    public function setEstConnecte(bool $estConnecte) {
        $this->estConnecte = $estConnecte;
    }

    public function setIdentite(string $identite) {
        $this->identite = $identite;
    }

    

}
