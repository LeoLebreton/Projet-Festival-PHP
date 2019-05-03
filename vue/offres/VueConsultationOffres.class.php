<?php
/**
 * Description Page de consultation des offres d'hébergement par établissmeent
 * -> affiche une page comportant un tableau par établissement, indiquant 
 * pour chaque type de chambre, le nombre de chambres offertes pour cet établissment
 * @author prof
 * @version 2018
 */

namespace vue\offres;

use vue\VueGenerique;
use modele\metier\Etablissement;

class VueConsultationOffres extends VueGenerique {

    /** @var array liste des établissments */
    private $lesEtablissements;

    /** @var array liste des types de chambre */
    private $lesTypesChambres;
    
    /** @var Array tableau associatif du nombre de chambres offert par etablissement, par type de chambres */
    private $tabNbChambresOffertes;
    

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();

        // IL FAUT QU'IL Y AIT AU MOINS UN ÉTABLISSEMENT ET UN TYPE CHAMBRE POUR QUE L'AFFICHAGE SOIT EFFECTUÉ        
        if (count($this->lesEtablissements) != 0 && count($this->lesTypesChambres) != 0) {
            // POUR CHAQUE ÉTABLISSEMENT : AFFICHAGE DU NOM ET D'UN TABLEAU COMPORTANT 1
            // LIGNE D'EN-TÊTE ET 1 LIGNE PAR TYPE DE CHAMBRE
            foreach ($this->lesEtablissements as $unEtablissement) {
                // AFFICHAGE DU NOM DE L'ÉTABLISSEMENT ET D'UN LIEN VERS LE FORMULAIRE DE MODIFICATION
                ?>
                <strong><?= $unEtablissement->getNom() ?></strong><br>
                <a href="index.php?controleur=offres&action=modifier&id=<?= $unEtablissement->getId() ?>">
                    Modifier
                </a>
                <table width="45%" cellspacing="0" cellpadding="0" class="tabQuadrille">
                    <!--AFFICHAGE DE LA LIGNE D'EN-TÊTE-->
                    <tr class="enTeteTabQuad">
                        <td width="30%">Type</td>
                        <td width="35%">Capacité</td>
                        <td width="35%">Nombre de chambres</td> 
                    </tr>
                    <?php
                    // BOUCLE SUR LES TYPES DE CHAMBRES (AFFICHAGE D'UNE LIGNE PAR TYPE DE 
                    // CHAMBRE AVEC LE NOMBRE DE CHAMBRES OFFERTES DANS L'ÉTABLISSEMENT POUR 
                    // LE TYPE DE CHAMBRE)
                    /* @var TypeChambre $unTypeChambre */
                    foreach ($this->lesTypesChambres as $unTypeChambre) {
                        ?>
                        <tr class="ligneTabQuad">
                            <td><?= $unTypeChambre->getId() ?></td>
                            <td><?= $unTypeChambre->getLibelle() ?></td>
                            <?php
                            // On récupère le nombre de chambres offertes pour l'établissement 
                            // et le type de chambre actuellement traités
                            $nbChambresOffertes = $this->tabNbChambresOffertes[$unEtablissement->getId()][$unTypeChambre->getId()];
                            ?>
                            <td><?= $nbChambresOffertes ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </table><br>
                <?php
            }
            include $this->getPied();
        }
    }

    public function setLesEtablissements(array $lesEtablissements) {
        $this->lesEtablissements = $lesEtablissements;
    }

    public function setLesTypesChambres(array $lesTypesChambres) {
        $this->lesTypesChambres = $lesTypesChambres;
    }
    
    public function setTabNbChambresOffertes(Array $tabNbChambresOffertes) {
        $this->tabNbChambresOffertes = $tabNbChambresOffertes;
    }

    

}
