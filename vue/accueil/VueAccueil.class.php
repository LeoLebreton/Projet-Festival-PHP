<?php

namespace vue\accueil;
use vue\VueGenerique;

/**
 * Description Page d'accueil du site
 *
 * @author prof
 * @version 2018
 */
class VueAccueil extends VueGenerique {

    public function __construct() {
        parent::__construct();
    }

    public function afficher() {
        include $this->getEntete();
        ?>
        <table width='95%'>
            <tr>
                <td class='texteAccueil'> 
                </td>
            </tr>
            <tr>
                <td >
                </td>
            </tr>
            <tr>
                <td >
                </td>
            </tr>
            <tr>
                <td class='texteAccueil'>
                    Cette application web permet de gérer l'hébergement des groupes de musique 
                    durant le festival Folklores du Monde.
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td class='texteAccueil'>
                    Elle offre les services suivants :
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td>
                </td>
            </tr>
            <tr>
                <td class='texteAccueil'>
                    <ul>
                        <li>Consulter, créer, modifier ou supprimer des établissements acceptant d'héberger les groupes de musiciens.
                            <p>
                            </p>
                        <li>Consulter, créer, modifier ou supprimer des caractéristiques de chambres.
                            <p>
                            </p>
                        <li>Consulter, déclarer ou modifier des groupes musicaux.
                            <p>
                            </p>
                        <li>Consulter, déclarer ou modifier les capacités d'accueil des établissements.
                            <p>
                            </p>
                        <li>Consulter, réaliser ou modifier les attributions des chambres aux groupes dans les établissements.
                            <p>
                            </p>
                        <li>Consulter, réaliser ou modifier les représentations (un groupe, un lieu, une date, un horaire).
                    </ul>
                </td>
            </tr>
        </table>
        <?php
        include $this->getPied();
    }

}
