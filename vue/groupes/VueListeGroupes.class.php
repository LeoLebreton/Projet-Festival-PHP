<?php
/**
 * Description Page de consultation de la liste des établissements
 * -> affiche Uun tableau constitué d'une ligne d'entête et d'une ligne par établissement
 * @author prof
 * @version 2018
 */
namespace vue\groupes;
use vue\VueGenerique;
class VueListeGroupes extends VueGenerique {
    
    /** @var array iliste des groupes à afficher */
    private $lesGroupesAvecNbAttributions;
    
    public function __construct() {
        parent::__construct();
    }
    public function afficher() {
        include $this->getEntete();
        ?>
        <br>
        <table width="55%" cellspacing="0" cellpadding="0" class="tabNonQuadrille" >

            <tr class="enTeteTabNonQuad" >
                <td colspan="4" ><strong>Groupes</strong></td>
            </tr>
            <?php
            // Pour chaque groupe lu dans la base de données
            foreach ($this->lesGroupesAvecNbAttributions as $unGroupeAvecNbAttributions) {
                $unGroupe = $unGroupeAvecNbAttributions["grp"];
                $id = $unGroupe->getId();
                $nom = $unGroupe->getNom();
                ?>
                <tr class="ligneTabNonQuad" >
                    <td width="52%" ><?= $nom ?></td>
                </tr>
                <td width="16%" align="center" > 
                    <a href="index.php?controleur=groupes&action=modifier&id=<?= $id ?>" >
                        Modifier
                    </a>
                </td>
                    <?php
                    // S'il existe déjà des attributions pour l'établissement, il faudra
                    // d'abord les supprimer avant de pouvoir supprimer l'établissement
                    if ($unGroupeAvecNbAttributions["nbAttrib"] == 0) {
                        ?>
                        <td width="16%" align="center" > 
                            <a href="index.php?controleur=groupes&action=supprimer&id=<?= $id ?>" >
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
                <?php
                
            }
            ?>
        </table>
        <br>
        <a href="index.php?controleur=groupes&action=creer" >
            Création d'un groupe</a >
        <?php
        include $this->getPied();
    }
    function setLesGroupesAvecNbAttributions($lesGroupesAvecNbAttributions) {
        $this->lesGroupesAvecNbAttributions = $lesGroupesAvecNbAttributions;
    }
}
