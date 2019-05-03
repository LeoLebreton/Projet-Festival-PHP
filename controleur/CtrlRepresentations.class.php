<?php

/**
 * Contrôleur de gestion des offres d'hébergement
 * @author prof
 * @version 2018
 */

namespace controleur;

use modele\dao\RepresentationDAO;
use modele\dao\AttributionDAO;
use modele\dao\GroupeDAO;
use modele\dao\LieuDAO;
use modele\dao\Bdd;
use modele\metier\Representation;
use modele\metier\Groupe;
use modele\metier\Lieu;
use vue\representation\VueConsultationRepresentation;
use vue\representation\VueSaisieRepresentation;
use vue\representation\VueSupprimmerRepresentation;

class CtrlRepresentations extends ControleurGenerique {

    /** controleur= representations & action= defaut
     * Afficher la liste des representations      */
    public function defaut() {
        $this->consulter();
    }

    /** controleur= offres & action= consulter
     * Afficher la liste des representations       */
    function consulter() {
        $laVue = new VueConsultationRepresentation();
        $this->vue = $laVue;
        // La vue a besoin de la liste des representations
        Bdd::connecter();
        $this->vue->setLesRepresentations(RepresentationDAO::getAll());
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - représentations");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }
        /** controleur= representations & action=creer
     * Afficher le formulaire d'ajout d'une reprensentation     */
    public function creer() {
        $laVue = new VueSaisieRepresentation();
        $this->vue = $laVue;
        $laVue->setActionRecue("creer");
        $laVue->setActionAEnvoyer("validerCreer");
        $laVue->setMessage("Nouvelle représentation");
        // En création, on affiche un formulaire vide
        $idGroupe = new Groupe("", "", "", "", "", "", "");
        $idLieu = new Lieu("", "", "", "", "", "");
        /* @var Representation $uneRepresentation */
        $uneRepresentation = new Representation("", $idGroupe, $idLieu, "", "", "");
        $laVue->setUneRepresentation($uneRepresentation);
        parent::controlerVueAutorisee();

        Bdd::connecter();
        $lesGroupes = GroupeDAO::getAll();
        $this->vue->setLesGroupes($lesGroupes);
        $lesLieux = LieuDAO::getAll();
        $this->vue->setLesLieux($lesLieux);

        $this->vue->setTitre("Festival - représentations");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }
    /** controleur= representations & action=validerCreer
     * ajouter d'une representation dans la base de données d'après la saisie    */
    public function validerCreer() {
        Bdd::connecter();
        /* @var Representation $uneRepresentation  : récupération du contenu du formulaire et instanciation d'une representation */
        $uneRepresentation = new Representation($_REQUEST['id'], GroupeDAO::getOneById($_REQUEST['groupe']), LieuDAO::getOneById($_REQUEST['lieu']), $_REQUEST['date'], $_REQUEST['heureDebut'], $_REQUEST['heureFin']);
        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de création (paramètre n°1 = true)
        $this->verifierDonneesRep($uneRepresentation, true);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer la representation
            RepresentationDAO::insert($uneRepresentation);
            // revenir à la liste des representation
            header("Location: index.php?controleur=representations&action=consulter");
        } else {
            // s'il y a des erreurs,
            // réafficher le formulaire de création
            $laVue = new VueSaisieRepresentation();
            $this->vue = $laVue;
            $laVue->setActionRecue("creer");
            $laVue->setActionAEnvoyer("validerCreer");
            $laVue->setMessage("Nouvelle Représentation");
            $lesGroupes = GroupeDAO::getAll();
            $this->vue->setLesGroupes($lesGroupes);
            $lesLieux = LieuDAO::getAll();
            $this->vue->setLesLieux($lesLieux);
            $laVue->setUneRepresentation($uneRepresentation);
            parent::controlerVueAutorisee();
            $laVue->setTitre("Festival - représentations");
            $laVue->setVersion($this->version);
            $this->vue->afficher();
        }
    }
    /** controleur= representations & action=modifier $ id=identifiant d'une Representation à modifier
     * Afficher le formulaire de modification d'une Representation     */
    public function modifier() {
        $idRep = $_GET["id"];
        $laVue = new VueSaisieRepresentation();
        $this->vue = $laVue;
        // Lire dans la BDD les données du Groupe à modifier
        Bdd::connecter();
        /* @var Groupe $leGroupe */
        $laRepresentation = RepresentationDAO::getOneById($idRep);
        $this->vue->setUneRepresentation($laRepresentation);
        $lesGroupes = GroupeDAO::getAll();
        $this->vue->setLesGroupes($lesGroupes);
        $lesLieux = LieuDAO::getAll();
        $this->vue->setLesLieux($lesLieux);
        $laVue->setActionRecue("modifier");
        $laVue->setActionAEnvoyer("validerModifier");
        $laVue->setMessage("Modifier la representation : " . $laRepresentation->getId() .".");
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - representation");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= representations & action=validerModifier
     * modifier une representation dans la base de données d'après la saisie    */
    public function validerModifier() {
        Bdd::connecter();
        /* @var Representation $uneRep  : récupération du contenu du formulaire et instanciation d'une Representation */
        $uneRep = new Representation($_REQUEST['id'], GroupeDAO::getOneById($_REQUEST['groupe']), LieuDAO::getOneById($_REQUEST['lieu']),
                $_REQUEST['date'], $_REQUEST['heureDebut'], $_REQUEST['heureFin']);
        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de modification (paramètre n°1 = false)
        $this->verifierDonneesRep($uneRep, false);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer les modifications pour un groupe
            RepresentationDao::update($uneRep->getId(), $uneRep);
            // revenir à la liste des Groupes
            header("Location: index.php?controleur=Representations&action=consulter");
        } else {
            // s'il y a des erreurs,
            // réafficher le formulaire de modification
            $laVue = new VueSaisieRepresentation();
            $this->vue = $laVue;
            $laVue->setUneRepresentation($uneRep);
            $lesGroupes = GroupeDAO::getAll();
            $this->vue->setLesGroupes($lesGroupes);
            $lesLieux = LieuDAO::getAll();
            $this->vue->setLesLieux($lesLieux);
            $laVue->setActionRecue("modifier");
            $laVue->setActionAEnvoyer("validerModifier");
            $laVue->setMessage("Modifier la Représentation : " . $uneRep->getId());
            parent::controlerVueAutorisee();
            $laVue->setTitre("Festival - Representation");
            $laVue->setVersion($this->version);
            $this->vue->afficher();
        }
    }

    /** controleur= Representations & action=supprimer & id=identifiant_Representation
     * Supprimer uneRepresentation d'après son identifiant     */
    public function supprimer() {
        $idRep = $_GET["id"];
        $this->vue = new VueSupprimmerRepresentation();
        // Lire dans la BDD les données du groupe à supprimer
        Bdd::connecter();
        $this->vue->setUneRepresentation(RepresentationDAO::getOneById($idRep));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - Representation");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= Representations & action= validerSupprimer
     * supprimer une Representation dans la base de données après confirmation   */
    public function validerSupprimer() {
        Bdd::connecter();
        if (!isset($_GET["id"])) {
            // pas d'identifiant fourni
            GestionErreurs::ajouter("Il manque l'identifiant du groupe à supprimer");
        } else {
            // suppression du groupe d'après son identifiant
            RepresentationDAO::delete($_GET["id"]);
        }
        // retour à la liste des Groupes
        header("Location: index.php?controleur=representations&action=consulter");
    }

    /**
     * Vérification des données du formulaire de saisie
     * @param Representation $uneRep à vérifier
     * @param bool $creation : =true si formulaire de création d'un nouveau groupe ; =false sinon
     */
    private function verifierDonneesRep(Representation $uneRep, bool $creation) {
        // Vérification des champs obligatoires.
        // Dans le cas d'une création, on vérifie aussi l'id
        if (($creation && $uneRep->getId() == "") || $uneRep->getIdGroupe()->getNom() == "" || $uneRep->getIdLieu()->getNom() == ""  || $uneRep->getDate() == "" || $uneRep->getHeureDebut() == "" ||
                $uneRep->getHeureFin() == "") {
            GestionErreurs::ajouter('Chaque champ suivi du caractère * est obligatoire');
        }
        // En cas de création, vérification du format de l'id et de sa non existence
        if ($creation && $uneRep->getId() != "") {
            // Si l'id est constitué d'autres caractères que de lettres non accentuées
            // et de chiffres, une erreur est générée
            if (!estAlphaNumerique($uneRep->getId())) {
                GestionErreurs::ajouter("L'identifiant doit comporter uniquement des lettres non accentuées et des chiffres");
            } else {
                if (RepresentationDAO::isAnExistingId($uneRep->getId())) {
                    GestionErreurs::ajouter("La Representation " . $uneRep->getId() . " existe déjà");
                }
            }
        }
        // Vérification qu'un groupe de même nom n'existe pas déjà (id + nom si création)
        if (RepresentationDAO::isAnExistingId($creation, $uneRep->getId())) {
            GestionErreurs::ajouter("La Representation " . $uneRep->getId() . " existe déjà");
        }

        // Vérification que le groupe existe
        if (GroupeDAO::getOneById($uneRep->getIdGroupe()->getNom() === null)) {
          GestionErreurs::ajouter("Ce groupe n'existe pas");
        }

        // Vérification que le lieu existence
        if (LieuDAO::getOneById($uneRep->getIdLieu()->getNom() === null )) {
          GestionErreurs::ajouter("Ce lieu n'existe pas");
        }

        // Vérification Représentation n'existe pas déjà au même endroit et même moment
        if (RepresentationDAO::isAnExistingCreneau($creation, $uneRep)) {
          GestionErreurs::ajouter("Le créneau horaire demandé est déjà réservé");
        }

        //Vérification que le groupe ne se produit pas au même moment
        if (RepresentationDAO::isAnExistingGroupRepres($creation, $uneRep)) {
          GestionErreurs::ajouter("Le groupe ".$uneRep->getIdGroupe()->getNom()." est déjà en représentation");
        }
    }
}
