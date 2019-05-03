<?php

/**
 * Contrôleur de gestion des offres d'hébergement
 * @author prof
 * @version 2018
 */

namespace controleur;

use modele\dao\EtablissementDAO;
use modele\dao\GroupeDAO;
use modele\dao\TypeChambreDAO;
use modele\dao\OffreDAO;
use modele\dao\AttributionDAO;
use modele\dao\Bdd;
use vue\attributions\VueConsultationAttributions;
use vue\attributions\VueModificationAttributions;
use vue\attributions\VueAttribution;

class CtrlAttributions extends ControleurGenerique {

    /** controleur= attributions & action= defaut
     * Afficher la liste des attributions d'hébergement      */
    public function defaut() {
        $this->consulter();
    }

    /** controleur= attributions & action= consulter
     * Afficher la liste des attributions d'hébergement       */
    function consulter() {
        $laVue = new VueConsultationAttributions();
        $this->vue = $laVue;
        Bdd::connecter();
        $lesEtab = EtablissementDAO::getAllOfferingRooms();
        $lesTypesChambres = TypeChambreDAO::getAll();
        // La vue a besoin des tableaux de valeurs suivants :
        $laVue->setLesEtabOffrantChambres($lesEtab);
        $laVue->setLesTypesChambres($lesTypesChambres);
        $laVue->setLesGroupesParEtab($this->getTabGroupesParEtab($lesEtab));
        $laVue->setTabNbChambresDispos($this->getTabNbChambresDispos($lesEtab, $lesTypesChambres));
        $laVue->setTabAttributions($this->getTabAttributions($lesEtab, $lesTypesChambres));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - attributions");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= attributions & action= modifier
     * Afficher l'écran permettant de choisir l'attribution à modifier      */
    function modifier() {
        $laVue = new VueModificationAttributions();
        $this->vue = $laVue;
        Bdd::connecter();
        $lesEtabOffrantChambre = EtablissementDAO::getAllOfferingRooms();
        $lesTypesChambres = TypeChambreDAO::getAll();
        $lesGroupesAHeberger = GroupeDAO::getAllToHost();
        // La vue a besoin des tableaux de valeurs suivants :
        $laVue->setLesEtabOffrantChambres($lesEtabOffrantChambre);
        $laVue->setLesTypesChambres($lesTypesChambres);
        $laVue->setLesGroupesAHeberger($lesGroupesAHeberger);
        $laVue->setTabNbChambres($this->getTabChambres($lesEtabOffrantChambre, $lesTypesChambres, $lesGroupesAHeberger));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - attributions");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= attributions & action= attribuer
     * Afficher l'écran permettant de choisir le nombre de chambres à attribuer
     */
    function attribuer() {
        // récupération des valeurs fournies dans l'URL
        $idEtab = $_GET['idEtab'];
        $idTypeChambre = $_GET['idTypeChambre'];
        $idGroupe = $_GET['idGroupe'];
        $nbChambresMax = $_GET['nbChambresMax'];
        $nbChambresMax = $_GET['nbChambresMax'];
        $nbChambresAttrib = $_GET['nbChambresAttrib'];
        Bdd::connecter();
        $laVue = new VueAttribution();
        $this->vue = $laVue;
        $laVue->setEtab(EtablissementDAO::getOneById($idEtab));
        $laVue->setIdTypeChambre($idTypeChambre);
        $laVue->setGroupe(GroupeDAO::getOneById($idGroupe));
        $laVue->setNbChambresMax($nbChambresMax);
        $laVue->setNbChambresAttrib($nbChambresAttrib);
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - attributions");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= attributions & action= valider
     * Valider l'attribution effectuée => l'inscrire dans la BDD
     * Revenir à l'écran de modification de l'ensemble des attributions
     */
    function valider() {
        // Lire les données saisies sur le formulaire 
        $idEtab = $_REQUEST['idEtab'];
        $idTypeChambre = $_REQUEST['idTypeChambre'];
        $idGroupe = $_REQUEST['idGroupe'];
        $nbChambres = $_REQUEST['nbChambres'];
        Bdd::connecter();
        if ($nbChambres == 0) {
            // Si le nouveau nombre de chambres attribuées à cette offre est nul, il faut supprimer l'attribution
            AttributionDAO::delete($idEtab, $idTypeChambre, $idGroupe);
        } else {
            // Si le nouveau nombre de chambres attribuées à cette offre est non nul :
            // vérifier l'existence de l'attribution considérée
            /* @var $uneAttrib modele\metier\Attribution */
            $uneAttrib = AttributionDAO::getOneById($idEtab, $idTypeChambre, $idGroupe);
            if ($uneAttrib != null) {
                // si l'attribution existe déjà, il faut la mettre à jour
                AttributionDAO::update($idEtab, $idTypeChambre, $idGroupe, $nbChambres);
            } else {
                // si l'attribution n'existe pas, il faut la créer
                AttributionDAO::insertValues($idEtab, $idTypeChambre, $idGroupe, $nbChambres);
            }
        }
        header("Location: index.php?controleur=attributions&action=modifier");
    }

    /**************************************************************************
     * Fonctions utilitaires du contrôleur pour  l'action consulter
     ****************************************************************************/

    /**
     * Tableau des attributions par établissement, par groupe, par type de chambre
     * @param array $lesEtab
     * @param array $lesTypesChambres
     * @return array nombre de chambres actuellement attribuées pour chaque établissement, groupe, type dechambre
     *      * Exemple de contenu :
     */
    public function getTabAttributions(Array $lesEtab, Array $lesTypesChambres): Array {
        $tabAttributions = Array();
        /* @var $unEtab Etablissment */
        foreach ($lesEtab as $unEtab) {
            $lesGroupes = GroupeDAO::getAllByEtablissement($unEtab->getId());
            foreach ($lesGroupes as $unGroupe) {
                foreach ($lesTypesChambres as $unTypeChambre) {
                    $tabAttributions[$unEtab->getId()][$unTypeChambre->getId()][$unGroupe->getId()] = AttributionDAO::getOneById($unEtab->getId(), $unTypeChambre->getId(), $unGroupe->getId());
                }
            }
        }
        return $tabAttributions;
    }

    /**
     * Tableau des disponibilités par établissement et par type de chambre
     * @param array $lesEtab : les établissements offrant des chambres 
     * @param array $lesTypesChambres : tous les types de chambres
     * @return Array tableau d'entiers : nbres de chambres disponibles par établissement et par type de chambre (id)
     */
    public function getTabNbChambresDispos(Array $lesEtab, Array $lesTypesChambres): Array {
        $tabNbDispos = Array();
        foreach ($lesEtab as $unEtab) {
            foreach ($lesTypesChambres as $unTypeChambre) {
                $tabNbDispos[$unEtab->getId()][$unTypeChambre->getId()] = $this->getNbDispo($unEtab->getId(), $unTypeChambre->getId());
            }
        }
        return $tabNbDispos;
    }

    /**
     * Tableau des groupes concernés par un établissement donné
     * @param Array $lesEtab : les établissements offrant des chambres
     * @return Array tableau d'objets Groupe
     */
    public function getTabGroupesParEtab(Array $lesEtab): Array {
        $tabGroupes = Array();
        foreach ($lesEtab as $unEtab) {
            $tabGroupes[$unEtab->getId()] = GroupeDAO::getAllByEtablissement($unEtab->getId());
        }
        return $tabGroupes;
    }

    /**
     * Calcule le nombre de chambres disponibles pour un établissement et un type de chambres
     * @param string $idEtab
     * @param string $idTypeChambre
     * @return int nombre de chambres disponibles ; =0 si absence d'offre ou si absence de disponibilité
     */
    public function getNbDispo(string $idEtab, string $idTypeChambre): int {
        $nbDispo = 0;
        Bdd::connecter();
        $uneOffre = OffreDAO::getOneById($idEtab, $idTypeChambre);
        if (!is_null($uneOffre)) {
            $nbOffre = $uneOffre->getNbChambres();
        } else {
            $nbOffre = 0;
        }
        if ($nbOffre != 0) {
            // Recherche du nombre de chambres occupées pour l'établissement et le
            // type de chambre en question
            $nbOccup = AttributionDAO::getNbOccupiedRooms($idEtab, $idTypeChambre);
            // Calcul du nombre de chambres libres
            $nbDispo = $nbOffre - $nbOccup;
        }
        return $nbDispo;
    }

    /*     * *************************************************************************
     * Fonctions utilitaires du contrôleur pour l'action modifier
     * ************************************************************************** */

    /**
     * Construction du tableau de valeurs à transmettre à la vue VueModifierAttributions
     * pour affichage
     * @param array $lesEtab : tous les établissements offrant des chambres 
     * @param array $lesTypesChambres : tous les types de chambres
     * @param array $lesGroupes : liste des groupes demandant un hébergement
     * @return array 
     */
    public function getTabChambres(Array $lesEtab, Array $lesTypesChambres, Array $lesGroupes): Array {
        $tabChambres = Array();
        foreach ($lesEtab as $unEtab) {
            foreach ($lesTypesChambres as $unTypeChambre) {
                $nbDispos = $this->getNbDispo($unEtab->getId(), $unTypeChambre->getId());
                $nbOffertes = 0;
                $uneOffre = OffreDAO::getOneById($unEtab->getId(), $unTypeChambre->getId());
                if (!is_null($uneOffre)) {
                    $nbOffertes = $uneOffre->getNbChambres();
                }
                $tabChambres[$unEtab->getId()][$unTypeChambre->getId()]['disponibles'] = $nbDispos;
                $tabChambres[$unEtab->getId()][$unTypeChambre->getId()]['offertes'] = $nbOffertes;
                $tabAttrib = Array();
                foreach ($lesGroupes as $unGroupe) {
                    $tabAttrib[$unGroupe->getId()] = AttributionDAO::getOneById($unEtab->getId(), $unTypeChambre->getId(), $unGroupe->getId());
                }
                $tabChambres[$unEtab->getId()][$unTypeChambre->getId()]['attribuees'] = $tabAttrib;
            }
        }
        return $tabChambres;
    }

}
