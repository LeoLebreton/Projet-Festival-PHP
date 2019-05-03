<?php
/**
 * Contrôleur de gestion des établissem ents
 * @author prof
 * @version 2018
 */
namespace controleur;
use controleur\GestionErreurs;
use modele\dao\GroupeDAO;
use modele\metier\Groupe;
use modele\dao\Bdd;
use vue\groupes\VueListeGroupes;
use modele\dao\AttributionDAO;
use vue\groupes\VueSaisieGroupe;
use vue\groupes\VueSupprimerGroupe;

class CtrlGroupes extends ControleurGenerique {

    /** controleur= groupes & action= defaut
     * Afficher la liste des groupes      */
    public function defaut() {
        $this->liste();
    }
    /** controleur= groupes & action= liste
     * Afficher la liste des groupes      */
    public function liste() {
        $laVue = new VueListeGroupes();
        $this->vue = $laVue;
        // On récupère un tableau composé de la liste des groupes
        Bdd::connecter();
        $laVue->setLesGroupesAvecNbAttributions($this->getTabGroupesAvecNbAttributions());
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - groupes");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }
    /** controleur= groupes & action=creer
     * Afficher le formulaire d'ajout d'un groupe     */
    public function creer() {
        $laVue = new VueSaisieGroupe();
        $this->vue = $laVue;
        $laVue->setActionRecue("creer");
        $laVue->setActionAEnvoyer("validerCreer");
        $laVue->setMessage("Nouveau groupe");
        // En création, on affiche un formulaire vide
        /* @var Groupe $unGrp */
        $unGrp = new Groupe("", "", "", "", "", "", "");
        $laVue->setUnGroupe($unGrp);
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - groupes");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= groupes & action=validerCreer
     * ajouter d'un groupe dans la base de données d'après la saisie    */
    public function validerCreer() {
        Bdd::connecter();
        /* @var Groupe $unGrp  : récupération du contenu du formulaire et instanciation d'un groupe */
        $unGrp = new Groupe($_REQUEST['id'], $_REQUEST['nom'], $_REQUEST['identiteResponsable'], $_REQUEST['adressePostale'], $_REQUEST['nombrePersonnes'], $_REQUEST['nomPays'], $_REQUEST['hebergement']);
        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de création (paramètre n°1 = true)
        $this->verifierDonneesGrp($unGrp, true);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer le groupe
            GroupeDAO::insert($unGrp);
            // revenir à la liste des groupes
            header("Location: index.php?controleur=groupes&action=liste");
        } else {
            // s'il y a des erreurs, 
            // réafficher le formulaire de création
            $laVue = new VueSaisieGroupe();
            $this->vue = $laVue;
            $laVue->setActionRecue("creer");
            $laVue->setActionAEnvoyer("validerCreer");
            $laVue->setMessage("Nouveau groupe");
            $laVue->setUnGroupe($unGrp);
            parent::controlerVueAutorisee();
            $laVue->setTitre("Festival - groupes");
            $laVue->setVersion($this->version);
            $this->vue->afficher();
        }
    }
    
        /** controleur= groupes & action=modifier $ id=identifiant du groupe à modifier
     * Afficher le formulaire de modification d'un groupe     */
    public function modifier() {
        $idGrp = $_GET["id"];
        $laVue = new VueSaisieGroupe();
        $this->vue = $laVue;
        // Lire dans la BDD les données du groupe à modifier
        Bdd::connecter();
        /* @var Groupe $leGroupe */
        $leGroupe = GroupeDAO::getOneById($idGrp);
        $this->vue->setUnGroupe($leGroupe);
        $laVue->setActionRecue("modifier");
        $laVue->setActionAEnvoyer("validerModifier");
        $laVue->setMessage("Modifier le groupe : " . $leGroupe->getNom() . " (" . $leGroupe->getId() . ")");
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - groupes");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= groupes & action=validerModifier
     * modifier un groupe dans la base de données d'après la saisie    */
    public function validerModifier() {
        Bdd::connecter();
        /* @var Groupe $unGrp  : récupération du contenu du formulaire et instanciation d'un groupe */
        $unGrp = new Groupe($_REQUEST['id'], $_REQUEST['nom'], $_REQUEST['identiteResponsable'], $_REQUEST['adressePostale'], $_REQUEST['nombrePersonnes'], $_REQUEST['nomPays'], $_REQUEST['hebergement']);

        // vérifier la saisie des champs obligatoires et les contraintes d'intégrité du contenu
        // pour un formulaire de modification (paramètre n°1 = false)
        $this->verifierDonneesGrp($unGrp, false);
        if (GestionErreurs::nbErreurs() == 0) {
            // s'il ny a pas d'erreurs,
            // enregistrer les modifications pour le groupe
            GroupeDAO::update($unGrp->getId(), $unGrp);
            // revenir à la liste des groupes
            header("Location: index.php?controleur=groupes&action=liste");
        } else {
            // s'il y a des erreurs, 
            // réafficher le formulaire de modification
            $laVue = new VueSaisieGroupe();
            $this->vue = $laVue;
            $laVue->setUnGroupe($unGrp);
            $laVue->setActionRecue("modifier");
            $laVue->setActionAEnvoyer("validerModifier");
            $laVue->setMessage("Modifier l'établissement : " . $unGrp->getNom() . " (" . $unGrp->getId() . ")");
            parent::controlerVueAutorisee();
            $laVue->setTitre("Festival - groupes");
            $laVue->setVersion($this->version);
            $this->vue->afficher();
        }
    }
    
        /** controleur= groupe & action=supprimer & id=identifiant_groupe
     * Supprimer un groupe d'après son identifiant     */
    public function supprimer() {
        $idGrp = $_GET["id"];
        $this->vue = new VueSupprimerGroupe();
        // Lire dans la BDD les données du Groupe à supprimer
        Bdd::connecter();
        $this->vue->setUnGroupe(GroupeDAO::getOneById($idGrp));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - etablissements");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= groupe & action= validerSupprimer
     * supprimer un groupe dans la base de données après confirmation   */
    public function validerSupprimer() {
        Bdd::connecter();
        if (!isset($_GET["id"])) {
            // pas d'identifiant fourni
            GestionErreurs::ajouter("Il manque l'identifiant du groupe à supprimer");
        } else {
            // suppression du groupe d'après son identifiant
            GroupeDAO::delete($_GET["id"]);
        }
        // retour à la liste des groupes
        header("Location: index.php?controleur=groupes&action=liste");
    }
    
    private function verifierDonneesGrp(Groupe $unGrp, bool $creation) {
        // Vérification des champs obligatoires.
        // Dans le cas d'une création, on vérifie aussi l'id
        if (($creation && $unGrp->getId() == "") || $unGrp->getNom() == "" || $unGrp->getIdentite() == "" || $unGrp->getAdresse() == "" ||
                $unGrp->getNbPers() == "" || $unGrp->getNomPays() == "" || $unGrp->getHebergement() == "") {
            GestionErreurs::ajouter('Chaque champ suivi du caractère * est obligatoire');
        }
        // En cas de création, vérification du format de l'id et de sa non existence
        if ($creation && $unGrp->getId() != "") {
            // Si l'id est constitué d'autres caractères que de lettres non accentuées 
            // et de chiffres, une erreur est générée
            if (!estAlphaNumerique($unGrp->getId())) {
                GestionErreurs::ajouter("L'identifiant doit comporter uniquement des lettres non accentuées et des chiffres");
            } else {
                if (GroupeDAO::isAnExistingId($unGrp->getId())) {
                    GestionErreurs::ajouter("Le groupe " . $unGrp->getId() . " existe déjà");
                }
            }
        }
        // Vérification qu'un groupe de même nom n'existe pas déjà (id + nom si création)
        if ($unGrp->getNom() != "" && GroupeDAO::isAnExistingName($creation, $unGrp->getId(), $unGrp->getNom())) {
            GestionErreurs::ajouter("Le groupe " . $unGrp->getNom() . " existe déjà");
        }
    }
    
    /**
     * Retourne la liste de tous les Groupes et du nombre d'attributions de chacun
     * @return Array tableau associatif à 2 dimensions : 
     *      - dimension 1, l'index est l'id du groupe
     *      - dimension 2, index "grp" => objet de type groupe
     *      - dimension 2, index "nbAttrib" => nombre d'attributions pour ce groupe */
    public function getTabGroupesAvecNbAttributions(): Array {
        $lesGroupesAvecNbAttrib = Array();
        $lesGroupes = GroupeDAO::getAll();
        foreach ($lesGroupes as $unGrp) {
            /* @var Groupe $unGrp */
            $lesGroupesAvecNbAttrib[$unGrp->getId()]['grp'] = $unGrp;
            $lesGroupesAvecNbAttrib[$unGrp->getId()]['nbAttrib'] = count(AttributionDAO::getAllByIdGrp($unGrp->getId()));
        }
        return $lesGroupesAvecNbAttrib;
    }
}
