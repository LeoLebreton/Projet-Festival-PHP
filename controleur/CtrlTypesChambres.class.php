<?php

/**
 * Contrôleur de gestion des types de chambres
 * @author prof
 * @version 2018
 */

namespace controleur;

use modele\metier\TypeChambre;
use modele\dao\TypeChambreDAO;
use modele\dao\Bdd;
use vue\typesChambres\VueListeTypesChambres;
use vue\typesChambres\VueSaisieTypeChambre;
use vue\typesChambres\VueSupprimerTypeChambre;

class CtrlTypesChambres extends ControleurGenerique {

    /** controleur= typeChambre & action= defaut
     * par défaut, afficher la liste des types de chambres      */
    public function defaut() {
        $this->liste();
    }

    /** controleur= typeChambre & action= liste
     * Afficher la liste des types de chambres       */
    function liste() {
        $laVue = new VueListeTypesChambres();
        $this->vue = $laVue;
        // On récupère un tableau composé d'objets de type TypeChambre lus dans la BDD
        Bdd::connecter();
        $laVue->setLesTypesChambresAvecNbAttributions(TypeChambreDAO::getAllWithNbAttributions());
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - types de chambres");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= typeschambres & action=modifier & id = n° type de chambre
     * Afficher le formulaire de modification d'un type de chambre     */
    public function modifier() {
        $idTypeChambre = $_GET["id"];
        $laVue = new VueSaisieTypeChambre();
        $this->vue = $laVue;
        // Lire dans la BDD les données du type de chambre à modifier
        Bdd::connecter();
        $laVue->setUnTypeChambre(TypeChambreDAO::getOneById($idTypeChambre));
        $laVue->setActionRecue("modifier");
        $laVue->setActionAEnvoyer("validerModifier");
        $laVue->setMessage("Modifier le type chambre : " . $laVue->getUnTypeChambre()->getId());
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - types de chambres");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= typesChambres & action=validerModifier
     * modifier un type de chambre dans la base de données d'après la saisie    */
    public function validerModifier() {
        Bdd::connecter();
        /* @var TypeChambre $unTypeChambre  : récupération du contenu du formulaire et instanciation d'un type de chambre */
        $unTypeChambre = new TypeChambre($_REQUEST['id'], $_REQUEST['libelle']);

        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de modification (paramètre n°1 = false)
        $this->verifierDonneesTypeChambre($unTypeChambre, false);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer les modifications pour l'établissement
            TypeChambreDAO::update($unTypeChambre->getId(), $unTypeChambre);
            // revenir à la liste des établissements
            header("Location: index.php?controleur=typesChambres&action=liste");
        } else {
            // s'il y a des erreurs, 
            // réafficher le formulaire de modification
            $laVue = new VueSaisieTypeChambre();
            $this->vue = $laVue;
            $laVue->setUnTypeChambre($unTypeChambre);
            $laVue->setActionRecue("modifier");
            $laVue->setActionAEnvoyer("validerModifier");
            $laVue->setMessage("Modifier le type de chambre : " . $laVue->getUnTypeChambre()->getLibelle()
                    . " (" . $laVue->getUnTypeChambre()->getLibelle() . ")");
            $this->vue->setTitre("Festival - types de chambres");
            $this->vue->setVersion($this->version);
            parent::controlerVueAutorisee();
            $this->vue->afficher();
        }
    }

    /** controleur= typeschambres & action=creer
     * Afficher le formulaire de création d'un type de chambre     */
    public function creer() {
        $laVue = new VueSaisieTypeChambre();
        $this->vue = $laVue;
        // Lire dans la BDD les données du type de chambre à modifier
        Bdd::connecter();
        $laVue->setUnTypeChambre(new TypeChambre("", ""));
        $laVue->setActionRecue("creer");
        $laVue->setActionAEnvoyer("validerCreer");
        $laVue->setMessage("Créer un nouveau type chambre");
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - types de chambres");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= typesChambres & action=validerCreer
     * modifier un type de chambre dans la base de données d'après la saisie    */
    public function validerCreer() {
        Bdd::connecter();
        /* @var TypeChambre $unTypeChambre  : récupération du contenu du formulaire et instanciation d'un type de chambre */
        $unTypeChambre = new TypeChambre($_REQUEST['id'], $_REQUEST['libelle']);

        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de modification (paramètre n°1 = false)
        $this->verifierDonneesTypeChambre($unTypeChambre, true);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer les modifications pour l'établissement
            TypeChambreDAO::insert($unTypeChambre);
            // revenir à la liste des établissements
            header("Location: index.php?controleur=typesChambres&action=liste");
        } else {
            // s'il y a des erreurs, 
            // réafficher le formulaire de saisie
            $laVue = new VueSaisieTypeChambre();
            $this->vue = $laVue;
            $laVue->setUnTypeChambre($unTypeChambre);
            $laVue->setActionRecue("creer");
            $laVue->setActionAEnvoyer("validerCreer");
            $laVue->setMessage("Créer un nouveau type chambre");
            $this->vue->setTitre("Festival - types de chambres");
            $this->vue->setVersion($this->version);
            parent::controlerVueAutorisee();
            $this->vue->afficher();
        }
    }

    /** controleur= typesChambres & action=supprimer & id=identifiant_type chambre
     * Supprimer un type de chambre d'après son identifiant     */
    public function supprimer() {
        $idTC = $_GET["id"];
        $laVue = new VueSupprimerTypeChambre();
        $this->vue = $laVue;
        // Lire dans la BDD les données de l'établissement à supprimer
        Bdd::connecter();
        $laVue->setUnTypeChambre(TypeChambreDAO::getOneById($idTC));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - types de chambres");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= typesChambres & action= validerSupprimer & id = n° type de chambre
     * supprimer un type de chambre dans la base de données après confirmation   */
    public function validerSupprimer() {
        Bdd::connecter();
        if (!isset($_GET["id"])) {
            // pas d'identifiant fourni
            GestionErreurs::ajouter("Il manque l'identifiant du type de chambre à supprimer");
        } else {
            // suppression de l'établissement d'après son identifiant
            TypeChambreDAO::delete($_GET["id"]);
        }
        // retour à la liste des établissements
        header("Location: index.php?controleur=typesChambres&action=liste");
    }

    /**
     * Vérification de la saisie des données du formulaire
     * @param TypeChambre $unTypeChambre
     * @param bool $creation
     */
    private function verifierDonneesTypeChambre(TypeChambre $unTypeChambre, bool $creation) {
        // Vérification des champs obligatoires.
        // Dans le cas d'une création, on vérifie aussi l'id
        if ($creation && $unTypeChambre->getId() == "" || $unTypeChambre->getLibelle() == "") {
            GestionErreurs::ajouter('Chaque champ suivi du caractère * est obligatoire');
        }
        // En cas de création, vérification du format de l'id et de sa non existence
        if ($creation && $unTypeChambre->getId() != "") {
            // Si l'id est constitué d'autres caractères que de lettres non accentuées 
            // et de chiffres, une erreur est générée
            if (!estAlphaNumerique($unTypeChambre->getId())) {
                GestionErreurs::ajouter("L'identifiant doit comporter uniquement des lettres non accentuées et des chiffres");
            } else {
                if (TypeChambreDAO::isAnExistingId($unTypeChambre->getId())) {
                    GestionErreurs::ajouter("Le type de chambre " . $unTypeChambre->getId() . " existe déjà");
                }
            }
        }
        if ($unTypeChambre->getLibelle() != "" && TypeChambreDAO::isAnExistingLibelle(true, $unTypeChambre->getId(), $unTypeChambre->getLibelle())) {
            GestionErreurs::ajouter("Le type de chambre " . $unTypeChambre->getLibelle() . " existe déjà");
        }
    }

}
