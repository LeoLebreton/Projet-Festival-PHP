<?php
/**
 * Description Page de consultation de la liste des types de chambres
 * -> affiche Uun tableau constitué d'une ligne d'entête et d'une ligne par type de chambre
 * @author prof
 * @version 2018
 */

namespace vue\typesChambres;

use vue\VueGenerique;

class VueListeTypesChambres extends VueGenerique {

    /** @var array liste des types de chambres à afficher  */
    private $lesTypesChambresAvecNbAttributions;

    public function __construct() {
        parent::__construct();
    }

    /**
     * Implémentation de la méthode générant le code HTML de la page concernée
     */
    public function afficher() {
        include $this->getEntete();
        ?>
        <!-- INSERER ICI LE CODE HTML-->
        <br>
        <table width='40%' cellspacing='0' cellpadding='0' class='tabNonQuadrille'>
            <tr class='enTeteTabNonQuad'>
                <td colspan='4'><strong>Types de chambres</strong></td>
            </tr>
            <?php
            // Pour chaque type de chambre
            foreach ($this->lesTypesChambresAvecNbAttributions as $unTcAvecNbAtt) {
                $unTypeChambre = $unTcAvecNbAtt['typeChambre'];
                ?>
                <tr class='ligneTabNonQuad'> 
                    <td width='15%'><?= $unTypeChambre->getId() ?></td>
                    <td width='33%'><?= $unTypeChambre->getLibelle() ?></td>
                    <td width='26%' align='center'>         
                        <a href="index.php?controleur=typesChambres&action=modifier&id=<?= $unTypeChambre->getId() ?>">
                            Modifier
                        </a></td>
                    <?php
                    // On calcule le nombre d'attributions de chambres existant pour ce type de chambre
                    $nbAttrib = $unTcAvecNbAtt['nbAttrib'];

                    if ($nbAttrib == 0) {
                        // si aucune chambre de ce type n'a déjà été allouée, on peut afficher le lien de suppression
                        ?>
                        <td width='26%' align='center'>
                            <a href="index.php?controleur=typesChambres&action=supprimer&id=<?= $unTypeChambre->getId() ?>">
                                Supprimer
                            </a></td>
                        <?php
                    } else {
                        // si des chambres de ce type ont déjà été attribuées, on ne génère pas de lien de suppression
                        ?>
                        <td width='26%'>&nbsp; </td>
                    </tr>
                    <?php
                    }
            }
            ?>    
        </table><br>
        <a href='index.php?controleur=typesChambres&action=creer'>
            Création d'un type de chambre
        </a>      
        <?php
        include $this->getPied();
    }

    // ACCESSEUR et MUTATEURS
    public function setLesTypesChambresAvecNbAttributions(Array $lesTypesChambresAvecNbAttributions) {
        $this->lesTypesChambresAvecNbAttributions = $lesTypesChambresAvecNbAttributions;
    }


}
