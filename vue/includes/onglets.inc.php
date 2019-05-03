<?php
/**
 * Permet de construire les onglets ; 
 * inclus dans debut.inc.php (première partie commune aux pages HTML)
 * @author prof
 * @version 2017 : version initiale ; 2018 : tableau des onglets
 */

function getTabOnglets() : array {
    $tabOnglets = array();
    $tabOnglets[]['libelle'] = "Accueil"; $tabOnglets[0]['url'] = "index.php"; $tabOnglets[0]['controleur'] = "accueil";
    $tabOnglets[]['libelle'] = "Gestion établissements"; $tabOnglets[1]['url'] = "index.php"; $tabOnglets[1]['controleur'] = "etablissements";
    $tabOnglets[]['libelle'] = "Gestion types chambres"; $tabOnglets[2]['url'] = "index.php"; $tabOnglets[2]['controleur'] = "typesChambres";
    $tabOnglets[]['libelle'] = "Gestion groupes"; $tabOnglets[3]['url'] = "index.php"; $tabOnglets[3]['controleur'] = "groupes";
    $tabOnglets[]['libelle'] = "Offre hébergement"; $tabOnglets[4]['url'] = "index.php"; $tabOnglets[4]['controleur'] = "offres";
    $tabOnglets[]['libelle'] = "Attribution chambres"; $tabOnglets[5]['url'] = "index.php"; $tabOnglets[5]['controleur'] = "attributions";
    $tabOnglets[]['libelle'] = "Représentations"; $tabOnglets[6]['url'] = "index.php"; $tabOnglets[6]['controleur'] = "representations";
    return $tabOnglets;
}
/**
 * Cette fonction est appelée pour la construction de chaque onglet avec un lien cliquable
 * @param int $numOnglet numéro d'ordre de l'onglet dans la barre des onglets
 * @param string $controleurCourant
 * @param boolean $lienActif =true si l'onglet est utilisable (un utilisateur est authentifié et connecté), false sinon
 * @return string code HTML du lien cliquable de l'onglet n°$i dans la barre d'onglets
 */
function construireMenu(int $numOnglet, string $controleurCourant, string $lienActif) : string {
    $tabOnglets = getTabOnglets();
    // booléen =true l'onglet n°i est ouvert ; =false sinon
    $ongletOuvert = ($tabOnglets[$numOnglet]['controleur'] == $controleurCourant);
    // booléen =true l'onglet considéré est le premier (le plus à gauche) ; =false sinon
    $ongletDeGauche = ($numOnglet == 1);

    // 1- Contenu du lien : cliquable (<a href ...>) ou pas (inactif ou déjà ouvert)
    // 2- Classe CSS du lien
    //     S'il s'agit de l'onglet de gauche, le style est différent car il faut 
    //     conserver le trait à gauche sinon le trait de gauche est supprimé 
    //     (afin d'éviter d'avoir une double épaisseur en raison du trait droit
    //     de l'onglet précédent) 
    if ($ongletOuvert || !$lienActif) {
        // non cliquable : onglet déjà ouvert ou lien inactif
        $lien = $tabOnglets[$numOnglet]['libelle'];
        if ($ongletDeGauche) {
            $classeCSS = "ongletOuvertPrem";
        } else {
            $classeCSS = "ongletOuvert";
        }
    } else {
        // lien actif
        $lien = "<a href=\"".$tabOnglets[$numOnglet]['url']."?controleur=".$tabOnglets[$numOnglet]['controleur']."\">".$tabOnglets[$numOnglet]['libelle']."</a>";
        if ($ongletDeGauche) {
            $classeCSS = "ongletPrem";
        } else {
            $classeCSS = "onglet";
        }
    }

    // Génération de l'item n°i de la barre d'onglets
    return "<li class=\"$classeCSS\">$lien</li>" . "\n";
}
