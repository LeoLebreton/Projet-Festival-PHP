<?php
/**
 * Page de permettant d'attribuer des offres à des groupes :
 * cette page présente un tableau à 2 entrées :
 *     - en ligne : les groupes
 *     - en colonnes : pour chaque établissement -> tous les types de chambres 
 * Chaque cellule du tableau fournit un lien cliquable pour modifier 
 * une attribution existante ou bien en ajouter une
 * Une légende des couleurs figure sous le tableau.
 * Le tableau présente 4 lignes d'entête :
 *     - le titre du tableau
 *     - la liste des établissements
 *     - pour chaque établissement, la liste des codes des types de chambres
 *     - le nombre de chambres encore disponibles pour chaque type de chambre de chaque établissement
 * @author prof
 * @version 2018
 */

namespace vue\attributions;

use vue\VueGenerique;

class VueModificationAttributions extends VueGenerique {

    /** @var Array liste des établissments */
    private $lesEtabOffrantChambres;

    /** @var Array liste des types de chambres */
    private $lesTypesChambres;
    
    /** @var Array liste des groupes à héberger (champ hebergement = 'O') */
    private $lesGroupesAHeberger;

    /** @var Array tableau associatif du nombre de chambres offertes 'offertes' et 'disponibles' 
     *  pour chaque etablissement, pour chaque type de chambre
     * et des attributions de chaque groupe
     * Exemple d'utilisation : 
     *   $nbChLib = $this->tabNbChambres[$idEtab][$idTypeChambre]['disponibles'];               //int
     *   $nbOffre = $this->tabNbChambres[$idEtab][$idTypeChambre]['offertes'];                  //int
     *   $uneAttrib = $this->tabNbChambres[$idEtab][$idTypeChambre]['attribuees'][$idGroupe];   //Attribution
     */
    private $tabNbChambres;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();

        // Détermination du pourcentage de largeur des colonnes "établissements"
        $nbEtabOffrantChambres = count($this->lesEtabOffrantChambres);
        $pourcCol = 65 / $nbEtabOffrantChambres;
        // Calcul du nombre de colonnes du tableau   
        $nbTypesChambres = count($this->lesTypesChambres);
        $nbCol = ($nbEtabOffrantChambres * $nbTypesChambres) + 1;
        ?>

        <br>
        <table width="90%" cellspacing="0" cellpadding="0" class="tabQuadrille">

            <!--// AFFICHAGE DE LA 1ÈRE LIGNE D'EN-TÊTE-->
            <tr class="enTeteTabQuad">
                <td  colspan="<?= $nbCol ?>">
                    <strong>Effectuer ou modifier les attributions</strong>
                </td>
            </tr>

            <!--// AFFICHAGE DE LA 2ÈME LIGNE D'EN-TÊTE (ÉTABLISSEMENTS)-->
            <tr class="ligneTabQuad">
                <td rowspan="2">&nbsp;</td>

        <?php
        // BOUCLE SUR LES ÉTABLISSEMENTS
        foreach ($this->lesEtabOffrantChambres as $unEtab) {

            // La colonne d'en-tête établissement regroupe autant de colonnes 
            // qu'il existe de types de chambres 
            ?>
            <td width="<?= $pourcCol ?>%" colspan="<?= $nbTypesChambres ?>">
                <center><?= $unEtab->getNom() ?></center>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--// AFFICHAGE DE LA 3ÈME LIGNE D'EN-TÊTE (LIGNE AVEC C1, C2, ..., C1, C2, ...)-->
        <tr class="ligneTabQuad">
        <?php
        // BOUCLE BASÉE SUR LE CRITÈRE ÉTABLISSEMENT 
        foreach ($this->lesEtabOffrantChambres as $unEtab) {
            $idEtab = $unEtab->getId();
            // BOUCLE BASÉE SUR LES TYPES DE CHAMBRES
            // Pour chaque établissement, on affiche forcément chaque type de chambre :
            //     - sur fond gris si le type de chambre n'est pas proposé
            //     - sur fond vert associé au nombre de chambres libres si le type de
            //       chambre est proposé et qu'il reste des chambres libres de ce type.
            //     - sur fond blanc, si le type de chambre est proposé dans l'établissement 
            //       et qu'il ne reste plus de chambres libres de ce type
            foreach ($this->lesTypesChambres as $unTypeChambre) {
                $idTypeChambre = $unTypeChambre->getId();
                // nombre de chambres offertes et restant libres pour l'établissement et le type de chambre courants
                $nbOffre = $this->tabNbChambres[$idEtab][$idTypeChambre]['offertes'];
                $nbChLib = $this->tabNbChambres[$idEtab][$idTypeChambre]['disponibles'];
                if ($nbOffre == 0) {
                    // Affichage du type de chambre sur fond gris
                    ?>
                        <td class="absenceOffre"><?= $idTypeChambre ?><br>&nbsp;</td>
                        <?php
                } else {
                    // Pour un établissement et un code type chambre, on affiche le
                    // type chambre sur fond vert avec le nombre de chambres libres
                    if ($nbChLib != 0) {
                        ?>
                        <td class="libre"><?= $idTypeChambre ?><br><?= $nbChLib ?></td>
                        <?php
                    } else {
                        // s'il n'y a pas de chambres libres, seul le type chambre est affiché               
                        ?>
                        <td class="reserveSiLien"><?= $idTypeChambre ?><br>&nbsp; </td>
                        <?php
                    }
                }
            } // Fin de la boucle des types de chambres
        } // Fin de la boucle basée sur le critère établissement
            ?>
        </tr>
            <?php
    // 4ÈME PARTIE : CORPS DU TABLEAU : CONSTITUTION D'UNE LIGNE PAR GROUPE À 
    // HÉBERGER AVEC LES CHAMBRES ATTRIBUÉES ET LES LIENS POUR EFFECTUER OU
    // MODIFIER LES ATTRIBUTIONS
            // BOUCLE SUR LES GROUPES À HÉBERGER 
        foreach ($this->lesGroupesAHeberger as $unGroupe) {
            $idGroupe = $unGroupe->getId();
            $nom = $unGroupe->getNom();
            ?>
            <tr class="ligneTabQuad">
                <td align="center" width="25%"><?= $nom ?></td>
            <?php
            // BOUCLE SUR LES ÉTABLISSEMENTS
            foreach ($this->lesEtabOffrantChambres as $unEtab) {
                $idEtab = $unEtab->getId();
                // BOUCLE SUR LES TYPES DE CHAMBRES
                foreach ($this->lesTypesChambres as $unTypeChambre) {
                    $idTypeChambre = $unTypeChambre->getId();
                    // Pour chaque cellule, 4 cas possibles :
                    // 1) type chambre inexistant dans cet étab : fond gris, 
                    // 2) des chambres ont déjà été attribuées au groupe pour cet
                    //    étab et ce type de chambre : fond jaune avec le nb de 
                    //    chambres attribuées et lien permettant de modifier le nb,
                    // 3) aucune chambre du type en question n'a encore été attribuée
                    //    au groupe dans cet étab et il n'y a plus de chambres libres
                    //    de ce type dans l'étab : cellule vide,
                    // 4) aucune chambre du type en question n'a encore été attribuée
                    //    au groupe dans cet étab et il reste des chambres libres de 
                    //    ce type dans l'établissement : affichage d'un lien pour 
                    //    faire une attribution
                    $nbChLib = $this->tabNbChambres[$idEtab][$idTypeChambre]['disponibles'];
                    $nbOffre = $this->tabNbChambres[$idEtab][$idTypeChambre]['offertes'];
                    /* @var $uneAttrib modele\metier\Attribution */
                    $uneAttrib = $this->tabNbChambres[$idEtab][$idTypeChambre]['attribuees'][$idGroupe];
                    if ($nbOffre == 0) {
                        // Affichage d'une cellule vide sur fond gris 
                        ?><td class="absenceOffre">&nbsp;</td>
                        <?php
                    } else {
                        // On recherche si des chambres du type en question ont déjà
                        // été attribuées à ce groupe dans cet établissement
                        if (!is_null($uneAttrib)) {
                            $nbOccupGroupe = $uneAttrib->getNbChambres();
                        } else {
                            $nbOccupGroupe = 0;
                        }
                        if ($nbOccupGroupe != 0) {
                            // Le nombre de chambres maximum pouvant être 
                            // demandées est la somme du nombre de chambres 
                            // libres et du nombre de chambres actuellement 
                            // déjà attribuées au groupe
                            $nbMax = $nbChLib + $nbOccupGroupe;
                            ?>
                            <td class="reserve">
                                <a href="index.php?controleur=attributions&action=attribuer&idEtab=<?= $idEtab ?>&idTypeChambre=<?= $idTypeChambre ?>&idGroupe=<?= $idGroupe ?>&nbChambresMax=<?= $nbMax ?>&nbChambresAttrib=<?= $nbOccupGroupe ?>">
                                <?= $nbOccupGroupe ?>
                                </a>
                            </td>
                            <?php
                        } else {
                            // Cas où il n'y a pas de chambres de ce type 
                            // attribuées à ce groupe dans cet établissement : 
                            // on affiche un lien vers donnerNbChambres s'il y a 
                            // des chambres libres sinon rien n'est affiché     
                            if ($nbChLib != 0) {
                                ?>
                                <td class="reserveSiLien">
                                    <a href="index.php?controleur=attributions&action=attribuer&idEtab=<?= $idEtab ?>&idTypeChambre=<?= $idTypeChambre ?>&idGroupe=<?= $idGroupe ?>&nbChambresMax=<?= $nbChLib ?>&nbChambresAttrib=0">
                                    __
                                    </a>
                                </td>
                                <?php
                            } else {
                                ?>
                                <td class="reserveSiLien">&nbsp;</td>
                                <?php
                            }
                        }
                    }
                } // Fin de la boucle sur les types de chambres
            } // Fin de la boucle sur les établissements       
        } // Fin de la boucle sur les groupes à héberger
            ?>
        </table> <!--// Fin du tableau principal-->

        <!--// AFFICHAGE DE LA LÉGENDE-->
        <table width="70%" align=center>
            <tr>
            <br>
            <td class="reserveSiLien" height="10">&nbsp;</td>
            <td width="21%" align="left">Réservation possible si lien affiché</td>
            <td class="absenceOffre" height="10">&nbsp;</td>
            <td width="21%" align="left">Absence d'offre</td>
            <td class="reserve" height="10">&nbsp;</td>
            <td width="21%" align="left">Nombre de places réservées</td>
            <td class="libre" height="10">&nbsp;</td>
            <td width="21%" align="left">Nombre de places encore disponibles</td>
        </tr>
        </table>
        <br><center><a href="index.php?controleur=attributions">Retour</a></center>

        <?php
        include $this->getPied();
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

    public function setLesGroupesAHeberger(Array $lesGroupesAHeberger) {
        $this->lesGroupesAHeberger = $lesGroupesAHeberger;
    }

    public function setTabNbChambres(Array $tabNbChambres) {
        $this->tabNbChambres = $tabNbChambres;
    }


}
