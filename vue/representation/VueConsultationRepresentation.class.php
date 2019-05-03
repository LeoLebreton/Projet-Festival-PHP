<?php
/**
 * Description Page de consultation des Représentations
 * @author btssio
 * @version 2018
 */

namespace vue\representation;

use vue\VueGenerique;
use modele\metier\Representation;
use modele\dao\RepresentationDao;

class VueConsultationRepresentation extends VueGenerique {


    /** @var array liste des représentations */
    private $lesRepresentations;



    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?><strong> PROGRAMME PAR JOUR </strong><br>
        <?php
        // Si il y a plus d'une représentation on affiche le tableau
        if (count($this->lesRepresentations) != 0) {
            // On affiche les informations de chaque représentations

            $tabDate=array();
            foreach ($this->lesRepresentations as $uneRepresentation) {
                $date=$uneRepresentation->getDate();
                $ok = in_array($date, $tabDate);
                if (!$ok){
                    array_push($tabDate, $date);
                }
            }
            foreach ($tabDate as $date){
                ?>
                <strong><?= $date ?></strong>
                <table width="45%" cellspacing="0" cellpadding="0" class="tabQuadrille">
                    <!--affichage des données qui seront dans le tableau-->
                    <tr class="enTeteTabQuad">
                        <td width="25%">Lieu</td>
                        <td width="25%">Groupe</td>
                        <td width="15%">Heure Début</td>
                        <td width="15%">Heure Fin</td>
                        <td width="15%">Modifier</td>
                        <td width="15%">Supprimer</td>
                    </tr>
                    <?php
                    $lesRepres = RepresentationDAO::getRepresentationByDate($date);

                    foreach ($lesRepres as $uneRepres){
                        ?>
                    <tr class="ligneTabQuad">
                        <!-- affichage des données de chaques représentationsn-->
                        <td><?= $uneRepres->getIdLieu()->getNom(); ?></td>
                        <td><?= $uneRepres->getIdGroupe()->getNom(); ?></td>
                        <td><?= $uneRepres->getHeureDebut(); ?></td>
                        <td><?= $uneRepres->getHeureFin(); ?></td>
                        <td><a href="index.php?controleur=representations&action=modifier&id=<?=$uneRepres->getId()?>" >Modifier</a></td>
                        <td><a href="index.php?controleur=representations&action=supprimer&id=<?=$uneRepres->getId()?>" >Supprimer</a></td>
                    <?php
                    }
                    ?>
                </table>
            <?php
            }
            ?>
             </br>
                <a href="index.php?controleur=representations&action=creer" >
            Création d'une représentation</a >
          <?php
            include $this->getPied();

}
}
    public function setLesRepresentations(array $lesRepresentations) {
        $this->lesRepresentations = $lesRepresentations;
    }

}
