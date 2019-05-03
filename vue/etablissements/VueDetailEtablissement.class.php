<?php

namespace vue\etablissements;

use vue\VueGenerique;
use modele\metier\Etablissement;

/**
 * Description Page de consultation d'un établissement donné
 * @author prof
 * @version 2018
 */
class VueDetailEtablissement extends VueGenerique {

    /** @var Etablissement identificateur de l'établissement à afficher */
    private $unEtablissement;

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();

        ?>
        <br>
        <table width='60%' cellspacing='0' cellpadding='0' class='tabNonQuadrille'> 
            <tr class='enTeteTabNonQuad'>
                <td colspan='3'><strong><?= $this->unEtablissement->getNom() ?></strong></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td  width='20%'> Id: </td>
                <td><?= $this->unEtablissement->getId() ?></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> Adresse: </td>
                <td><?= $this->unEtablissement->getAdresse() ?></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> Code postal: </td>
                <td><?= $this->unEtablissement->getCdp() ?></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> Ville: </td>
                <td><?= $this->unEtablissement->getVille() ?></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> Téléphone: </td>
                <td><?= $this->unEtablissement->getTel() ?></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> E-mail: </td>
                <td><?= $this->unEtablissement->getEmail() ?></td>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> Type: </td>
        <?php
        if ($this->unEtablissement->getTypeEtab() == 1) {
            ?>
                    <td> Etablissement scolaire </td>
                    <?php
                } else {
                    ?>
                    <td> Autre établissement </td>
                    <?php
                }
                ?>
            </tr>
            <tr class='ligneTabNonQuad'>
                <td> Responsable: </td>
                <td><?= $this->unEtablissement->getCiviliteResp() ?>&nbsp; <?= $this->unEtablissement->getNomResp() ?>&nbsp; <?= $this->unEtablissement->getPrenomResp() ?>
                </td>
            </tr> 
        </table>
        <br>
        <a href='index.php?controleur=etablissements&action=liste'>Retour</a>
        <?php
        include $this->getPied();
    }

    function setUnEtablissement(Etablissement $unEtablissement) {
        $this->unEtablissement = $unEtablissement;
    }


}
