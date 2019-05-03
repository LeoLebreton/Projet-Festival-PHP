<?php

/**
 * Contrôleur de gestion des offres d'hébergement
 * @author prof
 * @version 2018
 */

namespace controleur;

use modele\dao\EtablissementDAO;
use modele\dao\TypeChambreDAO;
use modele\dao\OffreDAO;
use modele\dao\AttributionDAO;
use modele\dao\Bdd;
use vue\offres\VueConsultationOffres;
use vue\offres\VueSaisieOffres;

class CtrlOffres extends ControleurGenerique {

    /** controleur= offres & action= defaut
     * Afficher la liste des offres d'hébergement      */
    public function defaut() {
        $this->consulter();
    }

    /** controleur= offres & action= consulter
     * Afficher la liste des offres d'hébergement       */
    function consulter() {
        $laVue = new VueConsultationOffres();
        $this->vue = $laVue;
        // La vue a besoin de la liste des établissments et de celle des types de chambres
        Bdd::connecter();
        $laVue->setLesEtablissements(EtablissementDAO::getAll());
        $laVue->setLesTypesChambres(TypeChambreDAO::getAll());
        $laVue->setTabNbChambresOffertes($this->getTabNbChambresOffertesParEtabParType());
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - offres");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= offres & action= modifier & id = identifiant de l'établissement visé
     * Modifier les offres d'hébergement proposées par un établissement  */
    function modifier() {
        $laVue = new VueSaisieOffres();
        $this->vue = $laVue;
        // Lecture de l'id de l'établissement
        $idEtab = $_GET['id'];
        // La vue a besoin :
        //  - de l'établissement concerné, 
        //  - des l'ensemble des types de chambres
        //  - des offres par type de chambre pour cet établissement
        //  - des attributions déjà effectuées par type de chambre pour cet établissement        
        Bdd::connecter();
        $laVue->setUnEtablissement(EtablissementDAO::getOneById($idEtab));
        $laVue->setLesTypesChambres(TypeChambreDAO::getAll());
        $laVue->setTabNbChambresAffiches($this->getTabNbChambresOffertesParTypePourUnEtab($idEtab));
        $laVue->setTabNbChambresAttribues($this->getTabNbChambresAttribueesParTypePourUnEtab($idEtab));
        parent::controlerVueAutorisee();
        $this->vue->setTitre("Festival - offres");
        $this->vue->setVersion($this->version);
        $this->vue->afficher();
    }

    /** controleur= offres & action= valider & id = identifiant de l'établissement visé
     * Afficher la liste des offres d'hébergement       */
    function valider() {
        $idEtab = $_GET['id'];
        Bdd::connecter();
        $lesTypesChambres = TypeChambreDAO::getAll();
        $tabNbChambresAttribues = $this->getTabNbChambresAttribueesParTypePourUnEtab($idEtab);
        // on va remplir ce tableau à partir des valeurs des champs saisis sur le formulaire
        // il servira à ré-afficher cette saisie si il y a au moins une erreur
        $tabNbChambresSaisis = Array();

        // Vérification du formulaire
        // $err = true si au moins une erreur détectée
        $err = false;
        // Pour chaque type de chambre
        foreach ($lesTypesChambres as $unTC) {
            // on récupère la valeur de la saisie pour cette offre
            // le nom du champ est formé vec le préfixe "nbChambres_" suivi de l'identifiant du type de chambre
            $nbChambresSaisi = $_REQUEST["nbChambres_" . $unTC->getId()];
            // enregistrement de la saisie pour transmission
            $tabNbChambresSaisis[$unTC->getId()] = $nbChambresSaisi;
            // Si la valeur saisie n'est pas numérique ou est inférieure aux 
            // attributions déjà effectuées pour cet établissement et ce type de
            // chambre, la modification n'est pas effectuée
            if (!estEntier($nbChambresSaisi)) {
                // valeur saisie non entière
                $err = true;
            } else {
                if ($nbChambresSaisi < $tabNbChambresAttribues[$unTC->getId()]) {
                    // nombre de chambres saisi incompatible avec les attributions déjà effectuées
                    $err = true;
                } else {
                    // La saisie est valide
                    // vérifier s'il y avait déjà une offre avant celle-ci
                    $uneOffre = OffreDAO::getOneById($idEtab, $unTC->getId());
                    if ($uneOffre != null) {
                        // il y avait déjà une offre
                        if ($nbChambresSaisi == 0) {
                            // Si l'offre tombe à 0, supprimer l'offre pré-existante
                            OffreDAO::delete($idEtab, $unTC->getId());
                        } else {
                            // Si la nouvelle offre comporte au moins une chambre, 
                            // mettre à jour l'offre pré-existante
                            OffreDAO::update($idEtab, $unTC->getId(), $nbChambresSaisi);
                        }
                    } else {
                        // Il n'y avait pas d'offre avant celle-ci
                        // Si la nouvelle offre comporte au moins une chambre,
                        // ajouter une offre
                        if ($nbChambresSaisi != 0) {
                            OffreDAO::insertValues($idEtab, $unTC->getId(), $nbChambresSaisi);
                        }
                    }
                }
            }
        }

        // Diagnostic du formulaire
        if ($err) {
            // Il y a au moins une valeur erronée
            // On enregistre le message d'erreur et on revient à la page de saisie des offres
            GestionErreurs::ajouter("Valeurs non entières ou inférieures aux attributions effectuées");
            // il faut ré-afficher la vue
            $laVue = new VueSaisieOffres();
            $this->vue = $laVue;
            // Lecture de l'id de l'établissement
            $idEtab = $_GET['id'];
            // La vue a besoin :
            //  - de l'établissement concerné, 
            //  - des l'ensemble des types de chambres
            //  - des offres par type de chambre pour cet établissement
            //  - des attributions déjà effectuées par type de chambre pour cet établissement        
            Bdd::connecter();
            $laVue->setUnEtablissement(EtablissementDAO::getOneById($idEtab));
            $laVue->setLesTypesChambres($lesTypesChambres);
            $laVue->setTabNbChambresAffiches($tabNbChambresSaisis);
            $laVue->setTabNbChambresAttribues($tabNbChambresAttribues);

            $this->vue->setTitre("Festival - offres");
            $this->vue->setVersion($this->version);
            parent::controlerVueAutorisee();
            $this->vue->afficher();
        } else {
            // Il n'y avait pas d'erreur, on peut revenir à la liste des offres
            header("Location: index.php?controleur=offres&action=consulter");
        }
    }

    /*     * ***********************************************************************
     * Fonctions utilitaires du contrôleur
     * ************************************************************************** */

    /**
     * Pour un établissement donné, cette méthode fournit le nombre total de chambres attribuées
     *  pour chaque type de chambre
     * @param string $idEtab
     * @return array tableau associatif
     * Exemple de contenu :
     * array (size=5)
      'C1' => int 0
      'C2' => int 2
      'C3' => int 1
      'C4' => int 0
      'C5' => int 0
     */
    public static function getTabNbChambresAttribueesParTypePourUnEtab(string $idEtab): Array {
        $tabNbChambres = Array();
        $lesTypesChambres = TypeChambreDAO::getAll();
        /* @var $unTC TypeChambre  */
        foreach ($lesTypesChambres as $unTC) {
            $nbAttribuees = AttributionDAO::getNbOccupiedRooms($idEtab, $unTC->getId());
            $tabNbChambres[$unTC->getId()] = $nbAttribuees;
        }
        return $tabNbChambres;
    }

    /**
     * Récapitule le nombre de chambres offertes par un établissement donné pour chaque type de chambre
     * @param string $idEtab identifiant de l'établissement concerné
     * @return array tableau associatif
     * Exemple de contenu :
      array (size=5)
      'C1' => int 5
      'C2' => int 10
      'C3' => int 5
      'C4' => int 0
      'C5' => int 0
     *    */
    public static function getTabNbChambresOffertesParTypePourUnEtab(string $idEtab): Array {
        $tabNbChambres = Array();
        $lesTypesChambres = TypeChambreDAO::getAll();
        /* @var $unTC TypeChambre  */
        foreach ($lesTypesChambres as $unTC) {
            $offre = OffreDAO::getOneById($idEtab, $unTC->getId());
            if (is_null($offre)) {
                $nbOffertes = 0;
            } else {
                $nbOffertes = $offre->getNbChambres();
            }
            $tabNbChambres[$unTC->getId()] = $nbOffertes;
        }
        return $tabNbChambres;
    }

    /**
     * Récapitule le nombre de chambres offertes pour chaque établissement, pour chaque type de chambre
     * @return array tableau associatif
     * Exemple de contenu :
      array (size=4)
      '0350773A' =>
      array (size=5)
      'C1' => int 0
      'C2' => int 15
      'C3' => int 1
      'C4' => int 0
      'C5' => int 0
      '0350785N' =>
      array (size=5)
      'C1' => int 5
      'C2' => int 10
      'C3' => int 5
      'C4' => int 0
      'C5' => int 0
      '0352072M' =>
      array (size=5)
      'C1' => int 5
      'C2' => int 10
      'C3' => int 3
      'C4' => int 0
      'C5' => int 0
      99999998 =>
      array (size=5)
      'C1' => int 49
      'C2' => int 0
      'C3' => int 0
      'C4' => int 0
      'C5' => int 0
     */
    public static function getTabNbChambresOffertesParEtabParType(): Array {
        $tabNbChambres = Array();
        $lesEtab = EtablissementDAO::getAll();
        $lesTypesChambres = TypeChambreDAO::getAll();
        foreach ($lesEtab as $unEtab) {
            foreach ($lesTypesChambres as $unTC) {
                $offre = OffreDAO::getOneById($unEtab->getId(), $unTC->getId());
                if (is_null($offre)) {
                    $nbOffertes = 0;
                } else {
                    $nbOffertes = $offre->getNbChambres();
                }
                $tabNbChambres[$unEtab->getId()][$unTC->getId()] = $nbOffertes;
            }
        }
        return $tabNbChambres;
    }

}
