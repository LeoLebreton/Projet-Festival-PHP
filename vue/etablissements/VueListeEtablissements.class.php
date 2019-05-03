<?php
/**
 * Description Page de consultation de la liste des établissements
 * -> affiche Uun tableau constitué d'une ligne d'entête et d'une ligne par établissement
 * @author prof
 * @version 2018
 */
namespace vue\etablissements;

use vue\VueGenerique;

class VueListeEtablissements extends VueGenerique {
    
    /** @var array iliste des établissements à afficher avec leur nombre d'atttributions */
    private $lesEtablissementsAvecNbAttributions;
    

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <br>
        <table width="55%" cellspacing="0" cellpadding="0" class="tabNonQuadrille" >

            <tr class="enTeteTabNonQuad" >
                <td colspan="4" ><strong>Etablissements</strong></td>
            </tr>
            <?php
            // Pour chaque établissement lu dans la base de données
            foreach ($this->lesEtablissementsAvecNbAttributions as $unEtablissementAvecNbAttrib) {
                $unEtablissement = $unEtablissementAvecNbAttrib["etab"];
                $id = $unEtablissement->getId();
                $nom = $unEtablissement->getNom();
                ?>
                <tr class="ligneTabNonQuad" >
                    <td width="52%" ><?= $nom ?></td>

                    <td width="16%" align="center" > 
                        <a href="index.php?controleur=etablissements&action=detail&id=<?= $id ?>" >
                            Voir détail</a>
                    </td>

                    <td width="16%" align="center" > 
                        <a href="index.php?controleur=etablissements&action=modifier&id=<?= $id ?>" >
                            Modifier
                        </a>
                    </td>

                    <?php
                    // S'il existe déjà des attributions pour l'établissement, il faudra
                    // d'abord les supprimer avant de pouvoir supprimer l'établissement
                    if ($unEtablissementAvecNbAttrib["nbAttrib"] == 0) {
                        ?>
                        <td width="16%" align="center" > 
                            <a href="index.php?controleur=etablissements&action=supprimer&id=<?= $id ?>" >
                               Supprimer
                            </a>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td width="16%" >&nbsp; </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </table>
        <br>
        <a href="index.php?controleur=etablissements&action=creer" >
            Création d'un établissement</a >
        <?php
        include $this->getPied();
    }

    function setLesEtablissementsAvecNbAttributions($lesEtablissementsAvecNbAttributions) {
        $this->lesEtablissementsAvecNbAttributions = $lesEtablissementsAvecNbAttributions;
    }

}
