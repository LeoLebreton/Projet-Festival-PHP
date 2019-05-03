<?php

/**
 * Contrôleur de gestion des établissements
 * @author prof
 * @version 2018
 */

namespace controleur;

use controleur\GestionErreurs;
use modele\dao\EtablissementDAO;
use modele\dao\AttributionDAO;
use modele\metier\Etablissement;
use modele\dao\Bdd;
use vue\etablissements\VueListeEtablissements;
use vue\etablissements\VueDetailEtablissement;
use vue\etablissements\VueSaisieEtablissement;
use vue\etablissements\VueSupprimerEtablissement;

class CtrlEtablissements extends ControleurGenerique {

    /** controleur= etablissements & action= defaut
     * Afficher la liste des établissements      */
    public function defaut() {
        $this->liste();
    }

    /** controleur= etablissements & action= liste
     * Afficher la liste des établissements      */
    public function liste() {
        $laVue = new VueListeEtablissements();
        $this->vue = $laVue;
        // On récupère un tableau composé de la liste des établissements avec, pour chacun,
        //  son nombre d'attributions de chambres actuel : 
        //  on ne peut supprimer un établissement que si aucune chambre ne lui est actuellement attribuée
        Bdd::connecter();
        $laVue->setLesEtablissementsAvecNbAttributions($this->getTabEtablissementsAvecNbAttributions());
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - etablissements");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= etablissements & action=detail & id=identifiant_établissement
     * Afficher un établissement d'après son identifiant     */
    public function detail() {
        $idEtab = $_GET["id"];
        $this->vue = new VueDetailEtablissement();
        // Lire dans la BDD les données de l'établissement à afficher
        Bdd::connecter();
        $this->vue->setUnEtablissement(EtablissementDAO::getOneById($idEtab));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - etablissements");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= etablissements & action=creer
     * Afficher le formulaire d'ajout d'un établissement     */
    public function creer() {
        $laVue = new VueSaisieEtablissement();
        $this->vue = $laVue;
        $laVue->setActionRecue("creer");
        $laVue->setActionAEnvoyer("validerCreer");
        $laVue->setMessage("Nouvel établissement");
        // En création, on affiche un formulaire vide
        /* @var Etablissement $unEtab */
        $unEtab = new Etablissement("", "", "", "", "", "", "", 0, "Monsieur", "", "");
        $laVue->setUnEtablissement($unEtab);
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - etablissements");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= etablissements & action=validerCreer
     * ajouter d'un établissement dans la base de données d'après la saisie    */
    public function validerCreer() {
        Bdd::connecter();
        /* @var Etablissement $unEtab  : récupération du contenu du formulaire et instanciation d'un établissement */
        $unEtab = new Etablissement($_REQUEST['id'], $_REQUEST['nom'], $_REQUEST['adresseRue'], $_REQUEST['codePostal'], $_REQUEST['ville'], $_REQUEST['tel'], $_REQUEST['adresseElectronique'], $_REQUEST['type'], $_REQUEST['civiliteResponsable'], $_REQUEST['nomResponsable'], $_REQUEST['prenomResponsable']);
        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de création (paramètre n°1 = true)
        $this->verifierDonneesEtab($unEtab, true);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer l'établissement
            EtablissementDAO::insert($unEtab);
            // revenir à la liste des établissements
            header("Location: index.php?controleur=etablissements&action=liste");
        } else {
            // s'il y a des erreurs, 
            // réafficher le formulaire de création
            $laVue = new VueSaisieEtablissement();
            $this->vue = $laVue;
            $laVue->setActionRecue("creer");
            $laVue->setActionAEnvoyer("validerCreer");
            $laVue->setMessage("Nouvel établissement");
            $laVue->setUnEtablissement($unEtab);
            parent::controlerVueAutorisee();
            $laVue->setTitre("Festival - etablissements");
            $laVue->setVersion($this->version);
            $this->vue->afficher();
        }
    }

    /** controleur= etablissements & action=modifier $ id=identifiant de l'établissement à modifier
     * Afficher le formulaire de modification d'un établissement     */
    public function modifier() {
        $idEtab = $_GET["id"];
        $laVue = new VueSaisieEtablissement();
        $this->vue = $laVue;
        // Lire dans la BDD les données de l'établissement à modifier
        Bdd::connecter();
        /* @var Etablissement $leEtablissement */
        $leEtablissement = EtablissementDAO::getOneById($idEtab);
        $this->vue->setUnEtablissement($leEtablissement);
        $laVue->setActionRecue("modifier");
        $laVue->setActionAEnvoyer("validerModifier");
        $laVue->setMessage("Modifier l'établissement : " . $leEtablissement->getNom() . " (" . $leEtablissement->getId() . ")");
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - etablissements");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= etablissements & action=validerModifier
     * modifier un établissement dans la base de données d'après la saisie    */
    public function validerModifier() {
        Bdd::connecter();
        /* @var Etablissement $unEtab  : récupération du contenu du formulaire et instanciation d'un établissement */
        $unEtab = new Etablissement($_REQUEST['id'], $_REQUEST['nom'], $_REQUEST['adresseRue'], $_REQUEST['codePostal'], $_REQUEST['ville'], $_REQUEST['tel'], $_REQUEST['adresseElectronique'], $_REQUEST['type'], $_REQUEST['civiliteResponsable'], $_REQUEST['nomResponsable'], $_REQUEST['prenomResponsable']);

        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de modification (paramètre n°1 = false)
        $this->verifierDonneesEtab($unEtab, false);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer les modifications pour l'établissement
            EtablissementDAO::update($unEtab->getId(), $unEtab);
            // revenir à la liste des établissements
            header("Location: index.php?controleur=etablissements&action=liste");
        } else {
            // s'il y a des erreurs, 
            // réafficher le formulaire de modification
            $laVue = new VueSaisieEtablissement();
            $this->vue = $laVue;
            $laVue->setUnEtablissement($unEtab);
            $laVue->setActionRecue("modifier");
            $laVue->setActionAEnvoyer("validerModifier");
            $laVue->setMessage("Modifier l'établissement : " . $unEtab->getNom() . " (" . $unEtab->getId() . ")");
            parent::controlerVueAutorisee();
            $laVue->setTitre("Festival - etablissements");
            $laVue->setVersion($this->version);
            $this->vue->afficher();
        }
    }

    /** controleur= etablissements & action=supprimer & id=identifiant_établissement
     * Supprimer un établissement d'après son identifiant     */
    public function supprimer() {
        $idEtab = $_GET["id"];
        $this->vue = new VueSupprimerEtablissement();
        // Lire dans la BDD les données de l'établissement à supprimer
        Bdd::connecter();
        $this->vue->setUnEtablissement(EtablissementDAO::getOneById($idEtab));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - etablissements");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= etablissements & action= validerSupprimer
     * supprimer un établissement dans la base de données après confirmation   */
    public function validerSupprimer() {
        Bdd::connecter();
        if (!isset($_GET["id"])) {
            // pas d'identifiant fourni
            GestionErreurs::ajouter("Il manque l'identifiant de l'établissement à supprimer");
        } else {
            // suppression de l'établissement d'après son identifiant
            EtablissementDAO::delete($_GET["id"]);
        }
        // retour à la liste des établissements
        header("Location: index.php?controleur=etablissements&action=liste");
    }

    /**
     * Vérification des données du formulaire de saisie
     * @param Etablissement $unEtab établissement à vérifier
     * @param bool $creation : =true si formulaire de création d'un nouvel établissement ; =false sinon
     */
    private function verifierDonneesEtab(Etablissement $unEtab, bool $creation) {
        // Vérification des champs obligatoires.
        // Dans le cas d'une création, on vérifie aussi l'id
        if (($creation && $unEtab->getId() == "") || $unEtab->getNom() == "" || $unEtab->getAdresse() == "" || $unEtab->getCdp() == "" ||
                $unEtab->getVille() == "" || $unEtab->getTel() == "" || $unEtab->getNomResp() == "") {
            GestionErreurs::ajouter('Chaque champ suivi du caractère * est obligatoire');
        }
        if (filter_var($_REQUEST['adresseElectronique'], FILTER_VALIDATE_EMAIL)){
            
            
        } else{
            GestionErreurs::ajouter("L'adresse mail n'est pas valide");
            
        }
        // En cas de création, vérification du format de l'id et de sa non existence
        if ($creation && $unEtab->getId() != "") {
            // Si l'id est constitué d'autres caractères que de lettres non accentuées 
            // et de chiffres, une erreur est générée
            if (!estAlphaNumerique($unEtab->getId())) {
                GestionErreurs::ajouter("L'identifiant doit comporter uniquement des lettres non accentuées et des chiffres");
            } else {
                if (EtablissementDAO::isAnExistingId($unEtab->getId())) {
                    GestionErreurs::ajouter("L'établissement " . $unEtab->getId() . " existe déjà");
                }
            }
        }
        // Vérification qu'un établissement de même nom n'existe pas déjà (id + nom si création)
        if ($unEtab->getNom() != "" && EtablissementDAO::isAnExistingName($creation, $unEtab->getId(), $unEtab->getNom())) {
            GestionErreurs::ajouter("L'établissement " . $unEtab->getNom() . " existe déjà");
        }
        // Vérification du format du code postal
        if ($unEtab->getCdp() != "" && !estUnCp($unEtab->getCdp())) {
            GestionErreurs::ajouter('Le code postal doit comporter 5 chiffres');
        }
    }

    /*****************************************************************************
     * Méthodes permettant de préparer les informations à destination des vues
     ******************************************************************************/

    /**
     * Retourne la liste de tous les Etablissements et du nombre d'attributions de chacun
     * @return Array tableau associatif à 2 dimensions : 
     *      - dimension 1, l'index est l'id de l'établissement
     *      - dimension 2, index "etab" => objet de type Etablissement
     *      - dimension 2, index "nbAttrib" => nombre d'attributions pour cet établissement
     */
    public function getTabEtablissementsAvecNbAttributions(): Array {
        $lesEtablissementsAvecNbAttrib = Array();
        $lesEtablissements = EtablissementDAO::getAll();
        foreach ($lesEtablissements as $unEtab) {
            /* @var Etablissement $unEtab */
            $lesEtablissementsAvecNbAttrib[$unEtab->getId()]['etab'] = $unEtab;
            $lesEtablissementsAvecNbAttrib[$unEtab->getId()]['nbAttrib'] = count(AttributionDAO::getAllByIdEtab($unEtab->getId()));
        }
        return $lesEtablissementsAvecNbAttrib;
    }

}
