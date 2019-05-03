<?php
namespace modele\metier;

/**
 * Description of Utilisateur
 *
 * @author prof
 */
class Utilisateur {
    private $id;
    private $civilite;
    private $nom;
    private $prenom;
    private $email;
    private $login;
    private $mdp;
    
    function __construct($id, $civilite, $nom, $prenom, $email, $login, $mdp) {
        $this->id = $id;
        $this->civilite = $civilite;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->login = $login;
        $this->mdp = $mdp;
    }
    public function __toString() {
        return get_class($this). "{ id=". $this->id 
                . " - civilite=". $this->civilite . " prenom=". $this->prenom . " nom=" . $this->nom
                . "- email=". $this->email . " - login=". $this->login . "- mdp= ". $this->mdp . "}";
    }

    function getId() {
        return $this->id;
    }

    function getCivilite() {
        return $this->civilite;
    }

    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getEmail() {
        return $this->email;
    }

    function getMdp() {
        return $this->mdp;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCivilite($civilite) {
        $this->civilite = $civilite;
    }

    function setNom($nom) {
        $this->nom = $nom;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    function getLogin() {
        return $this->login;
    }

    function setLogin($login) {
        $this->login = $login;
    }


}
