<?php

namespace vue\offres;

use vue\VueGenerique;
use modele\metier\Etablissement;

/**
 * Description Page de saisie/modification des offres d'hébergement
 * @author prof
 * @version 2018
 */
class VueSaisieOffres extends VueGenerique {

    /** @var Etablissement établissement à afficher */
    private $unEtablissement;

    /** @var Array liste des types de chambres */
    private $lesTypesChambres;

    /** @var Array tableau associatif du nombre de chambres saisi ou offert par type de chambres */
    private $tabNbChambresAffiches;

    /** @var Array tableau associatif du nombre de chambres déjà attribués par type de chambres */
    private $tabNbChambresAttribues;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <form method="POST" action="index.php?controleur=offres&action=valider&id=<?= $this->unEtablissement->getId() ?>">

            <br><strong><?= $this->unEtablissement->getNom() ?></strong><br><br>

            <table width="45%" cellspacing="0" cellpadding="0" class="tabQuadrille">

                <!--// AFFICHAGE DE LA LIGNE D'EN-TÊTE-->

                <tr class="enTeteTabQuad">
                    <td width="30%">Type</td>
                    <td width="37%">Capacité</td>
                    <td width="33%">Nombre de chambres</td> 
                </tr>
                <?php
                // BOUCLE SUR LES TYPES DE CHAMBRES (AFFICHAGE D'UNE LIGNE PAR TYPE DE 
                // CHAMBRE AVEC EN 3ÈME COLONNE LE NOMBRE DE CHAMBRES OFFERTES DANS
                // L'ÉTABLISSEMENT POUR LE TYPE DE CHAMBRE
                foreach ($this->lesTypesChambres as $unTypeChambre) {
                    $idTC = $unTypeChambre->getId();
                    ?>
                    <tr class="ligneTabQuad">
                        <td><?= $idTC ?></td>
                        <td><?= $unTypeChambre->getLibelle() ?></td>
                        <?php
                        // AFFICHAGE DE LA CELLULE NOMBRE DE CHAMBRES OFFERTES
                        // Afficher ce nombre en rouge (erreur) s'il n'est pas un entier
                        // ou bien s'il est inférieur au nombre de chambres déjà attribuées
                        $nbAffiche = $this->tabNbChambresAffiches["$idTC"];
                        $nbAttribue = $this->tabNbChambresAttribues["$idTC"];
                        $classeStyle = "";
                        if (!estEntier($nbAffiche) || $nbAffiche < $nbAttribue) {
                            $classeStyle = "class=\"erreur\"";
                        }
                        ?>
                        <td align="center">
                            <input  type="text" value="<?= $nbAffiche ?>" 
                                    name="nbChambres_<?= $unTypeChambre->getId() ?>" 
                                    maxlength="3" <?= $classeStyle ?>>
                        </td>
                        <?php
                        ?>
                    </tr>
                    <?php
                } // Fin de la boucle sur les types de chambres
                ?>
            </table>
            <table align="center" cellspacing="15" cellpadding="0">
                <tr>
                    <td align="right">
                        <input type="submit" value="Valider" name="valider"></td>
                    <td align="left">
                        <input type="reset" value="Annuler" name="annuler">
                    </td>
                </tr>
            </table>
            <a href="index.php?controleur=offres">Retour</a>
        </form> 
        <?php
        include $this->getPied();
    }

    public function getUnEtablissement(): Etablissement {
        return $this->unEtablissement;
    }

    public function setUnEtablissement(Etablissement $unEtablissement) {
        $this->unEtablissement = $unEtablissement;
    }

    public function setLesTypesChambres(Array $lesTypesChambres) {
        $this->lesTypesChambres = $lesTypesChambres;
    }

    public function setTabNbChambresAffiches(Array $tabNbChambresAffiches) {
        $this->tabNbChambresAffiches = $tabNbChambresAffiches;
    }

    public function setTabNbChambresAttribues(Array $tabNbChambresAttribues) {
        $this->tabNbChambresAttribues = $tabNbChambresAttribues;
    }

}
