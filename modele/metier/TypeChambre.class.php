<?php
namespace modele\metier;

/**
 * Description of TypeChambre
 * Classification des chambres en fonction de leur capacité
 * @author prof
 */
class TypeChambre {
    /**
     *
     * @var string 
     */
    private $id;
    /**
     *
     * @var string 
     */
    private $libelle;
    
    function __construct(string $id, string $libelle) {
        $this->id = $id;
        $this->libelle = $libelle;
    }

    public function getId() {
        return $this->id;
    }

    public function getLibelle() {
        return $this->libelle;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setLibelle($libelle) {
        $this->libelle = $libelle;
    }



}
