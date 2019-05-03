<?php
/**
 * Classe utilitaire : gestion des erreurs en utilisant les sessions PHP 
 * @author prof
 * @version 2018
 */

namespace controleur;

class GestionErreurs {

    /**
     * Ajout d'un message d'erreurs
     * @param string $msg message d'erreur à ajouter
     */
    public static function ajouter(string $msg) {
        // si la liste des erreurs n'existe pas : la créer
        if (!isset($_SESSION['erreurs'])) {
            $_SESSION['erreurs'] = array();
        }
        // ajouter le message passé en paramètre à la liste des erreurs
        $_SESSION['erreurs'][] = htmlentities($msg, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Vider la liste des erreurs
     */
    public static function razErreurs() {
        unset($_SESSION['erreurs']);
    }

    /**
     * Retourne la liste actuelle des messages d'erreurs (tableau de chaînes)
     * @return array liste des erreurs
     */
    public static function getErreurs() : array {
        if (!isset($_SESSION['erreurs'])) {
            $_SESSION['erreurs'] = array();
        }
        return $_SESSION['erreurs'];
    }

    /**
     * Retourne le nombre actuel des messages d'erreurs
     * @return int nombre de messages d'erreurs
     */
    public static function nbErreurs() : int {
        return count(self::getErreurs());
    }
    
    /**
     * Retourne l'affichage HTML des messages d'erreurs
     * @return string code HTML de la liste des erreurs
     */
    public static function printErreurs() : string {
        $retour = "";
        if (self::nbErreurs() != 0) {
            $retour .= '<div id="erreur" class="msgErreur">';
            $retour .=  '<ul>';
            foreach (self::getErreurs() as $erreur) {
                $retour .=  "<li>$erreur</li>";
            }
            $retour .=  '</ul>';
            $retour .=  '</div>';
        }
        return $retour;
    }
}   