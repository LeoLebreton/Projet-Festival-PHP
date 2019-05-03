<?php
/**
 * Description Page de consultation des attributions d'hébergement par établissement
 * -> affiche une page comportant un tableau par établissement, indiquant 
 * pour chaque type de chambre, et pour chaque groupe musical concerné,
 * le nombre de chambres offertes
 * @author prof
 * @version 2018
 */

namespace vue\attributions;

use vue\VueGenerique;

class VueConsultationAttributions extends VueGenerique {
    
    /** @var Array liste des établissments */
    private $lesEtabOffrantChambres;

    /** @var Array liste des types de chambres */
    private $lesTypesChambres;

    /** @var Array tableau assocciatif idEtab -> liste de groupes */
    private $lesGroupesParEtab;
    
    /** @var Array tableau associatif du nombre de chambres disponibles (nbOffertes-nbOccupées)
     *  pour chaque etablissement, pour chaque type de chambre */
    private $tabNbChambresDispos;
    
    /** @var Array tableau associatif : une attribution par établissement / type de chambre / groupe */
    private $tabAttributions;
    
    

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <center> 
            <a href="index.php?controleur=attributions&action=modifier">
                Effectuer ou modifier les attributions
            </a> <br/> <br/>
        </center>
        <?php
        // Détermination du :
        //    . % de largeur que devra occuper chaque colonne contenant les attributions
        //      (100 - 35 pour la colonne d'en-tête) / nb de types chambres
        //    . nombre de colonnes de chaque tableau
        $nbTypesChambres = count($this->lesTypesChambres);
        $pourcCol = 65 / $nbTypesChambres;
        $nbCol = $nbTypesChambres + 1;
        // BOUCLE SUR LES ÉTABLISSEMENTS OFFRANT DES CHAMBRES
        foreach ($this->lesEtabOffrantChambres as $unEtab) {
            ?>
            <table width="70%" cellspacing="0" cellpadding="0" class="tabQuadrille">
                <!--// AFFICHAGE DE LA 1ÈRE LIGNE D'EN-TÊTE-->
                <tr class="enTeteTabQuad">
                    <td colspan="<?= $nbCol ?>"><strong><?= $unEtab->getNom() ?></strong></td>
                </tr>
                <!-- AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE : 1 LIT : NOMBRE DE CHAMBRES 
                     DISPONIBLES, 2 À 3 LITS : NOMBRE DE CHAMBRES DISPONIBLES...  -->
                <tr class="enTete2TabQuad">
                    <td width="35%"><i>Disponibilités</i></td>
                    <?php
            // POUR CHAQUE TYPE DE CHAMBRE : afficher les disponibilités
            foreach ($this->lesTypesChambres as $unTypeChambre) {
                // On recherche les disponibilités pour l'établissement et le type
                // de chambre en question
                $nbChDispo = $this->tabNbChambresDispos[$unEtab->getId()][$unTypeChambre->getId()];
                ?>
                    <td>
                        <center>
                            <?= $unTypeChambre->getLibelle() ?><br/>
                            <?= $nbChDispo ?>
                        </center>
                    </td>
                <?php
            }
            ?>
            </tr>
            <?php
            // AFFICHAGE DU DÉTAIL DES ATTRIBUTIONS
            // POUR CHAQUE GROUPE (en ligne
            foreach ($this->lesGroupesParEtab[$unEtab->getId()] as $unGroupe) {
                ?>
                <tr class="ligneTabQuad">
                    <td width="35%">&nbsp;<?= $unGroupe->getNom() ?></td>
                    <?php
                // POUR CHAQUE TYPE DE CHAMBRE (en colonne)
                foreach ($this->lesTypesChambres as $unTypeChambre) {
                    // On recherche si des chambres du type en question ont 
                    // déjà été attribuées à ce groupe dans l'établissement
                    /* @var $uneAttrib modele\metier\Attribution */
                    $uneAttrib = $this->tabAttributions[$unEtab->getId()][$unTypeChambre->getId()][$unGroupe->getId()];
                    if (!is_null($uneAttrib)) {
                        $nbOccupGroupe = $uneAttrib->getNbChambres();
                    } else {
                        $nbOccupGroupe = 0;
                    }
                    ?>
                        <td width="<?= $pourcCol ?>%"><center><?= $nbOccupGroupe ?></center></td>
                    <?php
                } // Fin de la boucle sur les types de chambres
                ?>
                </tr>
                <?php
            } // Fin de la boucle sur les groupes
            ?>
            </table>
            <br>
            <?php
        } // Fin de la boucle sur les établissements

        include $this->getPied();
    }

    public function getLesEtabOffrantChambres(): Array {
        return $this->lesEtabOffrantChambres;
    }

    public function setLesEtabOffrantChambres(Array $lesEtabOffrantChambres) {
        $this->lesEtabOffrantChambres = $lesEtabOffrantChambres;
    }

    public function setLesTypesChambres(Array $lesTypesChambres) {
        $this->lesTypesChambres = $lesTypesChambres;
    }

    public function setLesGroupesParEtab(Array $lesGroupesParEtab) {
        $this->lesGroupesParEtab = $lesGroupesParEtab;
    }

    public function setTabNbChambresDispos(Array $tabNbChambresDispos) {
        $this->tabNbChambresDispos = $tabNbChambresDispos;
    }

    public function setTabAttributions(Array $tabAttributions) {
        $this->tabAttributions = $tabAttributions;
    }


}
