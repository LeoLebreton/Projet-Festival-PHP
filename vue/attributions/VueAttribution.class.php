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
use modele\metier\Groupe;
use modele\metier\Etablissement;

class VueAttribution extends VueGenerique {

    /** @var Etablissement établissement fournissant l'offre */
    private $etab;
    /** @var string identifiant du type de chambre concernée par l'offre */
    private $idTypeChambre;
    /** @var Groupe groupe bénéficiaire de l'offre */
    private $groupe;
    /** @var int nombre de chambres maximum à attribuer */
    private $nbChambresMax;
    /** @var int nombre de chambres actuellement attribuées */
    private $nbChambresAttrib;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>

        <form method="POST" 
              action="index.php?controleur=attributions&action=valider&idEtab=<?= $this->etab->getId() ?>&idTypeChambre=<?= $this->idTypeChambre ?>&idGroupe=<?= $this->groupe->getId() ?>">
            <br/>
            <center>
                Combien de chambres de type <?= $this->idTypeChambre ?> 
                souhaitez-vous pour le groupe <?= $this->groupe->getNom() ?> 
                dans l'établissement <?= $this->etab->getNom() ?> ?
            </center>
            <br/><br/><br/>
            <center>
            <select name="nbChambres">
                <?php
                //contenu de la liste déroulante
                for ($i = 0; $i <= $this->nbChambresMax; $i++) {
                    $sel = "";
                    if ($i == $this->nbChambresAttrib){
                        $sel = "selected=\"selected\"";
                    }
                    ?> <option <?= $sel ?> ><?= $i ?></option>
                    <?php
                }
                ?>
            </select>
            </center>
        <br>
        <input type="submit" value="Valider" name="valider">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="reset" value="Annuler" name="Annuler">
        <br/><br/>
        <a href="index.php?controleur=attributions&action=modifier">
            Retour
        </a>
        </form>

        <?php
        include $this->getPied();
    }

    public function setEtab(Etablissement $etab) {
        $this->etab = $etab;
    }

    public function setIdTypeChambre(string $idTypeChambre) {
        $this->idTypeChambre = $idTypeChambre;
    }

    public function setGroupe(Groupe $groupe) {
        $this->groupe = $groupe;
    }

    public function setNbChambresMax(int $nbChambres) {
        $this->nbChambresMax = $nbChambres;
    }

    public function setNbChambresAttrib(int $nbChambres) {
        $this->nbChambresAttrib = $nbChambres;
    }




}
