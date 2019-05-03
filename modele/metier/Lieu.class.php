<?php
namespace modele\metier;

/**
 * Description of Lieu
 * Lieu où se déroule le festival
 * @author btssio
 */
class Lieu {
    /**
     * identifiant du lieu ("xxxxL")
     * @var string
     */
    private $id;
    /**
     * nom du lieu
     * @var string
     */
    private $nom;
    /**
     * adresse du lieu
     * @var string 
     */
    private $adresse;
    /**
     * code postal du lieu
     * @var string
     */
    private $codePostal;
    /**
     * ville du lieu
     * @var string
     */
    private $ville;
    /**
     * capacite du lieu
     * @var int 
     */
    private $capacite;
    function __construct($id, $nom, $adresse, $codePostal, $ville, $capacite) {
        $this->id = $id;
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->codePostal = $codePostal;
        $this->ville = $ville;
        $this->capacite = $capacite;
    }
    function getId() {
        return $this->id;
    }
    function getNom() {
        return $this->nom;
    }
    function getAdresse() {
        return $this->adresse;
    }
    function getCodePostal() {
        return $this->codePostal;
    }
    function getVille() {
        return $this->ville;
    }
    function getCapacite() {
        return $this->capacite;
    }
    function setId($id) {
        $this->id = $id;
    }
    function setNom($nom) {
        $this->nom = $nom;
    }
    function setAdresse($adresse) {
        $this->adresse = $adresse;
    }
    function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;
    }
    function setVille($ville) {
        $this->ville = $ville;
    }
    function setCapacite($capacite) {
        $this->capacite = $capacite;
    }
}
