<?php
namespace modele\metier;

/**
 * Description of Representation
 * @author btssio
 */
class Representation {
    /**
     * identifiant de la Representation ("xxxxR")
     * @var String
     */
    private $id;
    /**
     * identifiant du groupe
     * @var Groupe
     */
    private $idGroupe;
    /**
     * identifiant du lieu
     * @var Lieu
     */
    private $idLieu;
    /**
     * date de la représentation
     * @var date
     */
    private $date;
    /**
     * heure de début de la représentation
     * @var Integer
     */
    private $heureDebut;
    /**
     * heure de fin de la représentation
     * @var Integer
     */
    private $heureFin;

    function __construct($id, Groupe $idGroupe, Lieu $idLieu, $date, $heureDebut, $heureFin) {
        $this->id = $id;
        $this->idGroupe = $idGroupe;
        $this->idLieu = $idLieu;
        $this->date = $date;
        $this->heureDebut = $heureDebut;
        $this->heureFin = $heureFin;
    }

    function getId() {
        return $this->id;
    }

    function getIdGroupe() {
        return $this->idGroupe;
    }

    function getIdLieu() {
        return $this->idLieu;
    }

    function getDate() {
        return $this->date;
    }

    function getHeureDebut() {
        return $this->heureDebut;
    }

    function getHeureFin() {
        return $this->heureFin;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setIdGroupe(Groupe $idGroupe) {
        $this->idGroupe = $idGroupe;
    }

    function setIdLieu(Lieu $idLieu) {
        $this->idLieu = $idLieu;
    }

    function setDate($date) {
        $this->date = $date;
    }

    function setHeureDebut($heureDebut) {
        $this->heureDebut = $heureDebut;
    }

    function setHeureFin($heureFin) {
        $this->heureFin = $heureFin;
    }
}
