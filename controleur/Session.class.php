<?php

/**
 * Classe utilitaire : gestion des sessions PHP 
 * @author prof
 * @version 2018
 */

namespace controleur;

class Session {

    /**
     * Test si au moins une session est active
     * @return bool =true si une session est démarrée ; =false sinon
     */
    public static function estDemarree() {
        return (session_status() === PHP_SESSION_ACTIVE);
    }

    /**
     * Débute une nouvelle session, même si un session est déjà ouverte ;
     * dans ce dernier cas, un nouvel identifiant de session est généré, 
     * et les données de l'ancienne session sont conservées
     * @return bool =true en cas de succès ou =false si une erreur survient. 
     */
    public static function demarrer(): bool {
        $ok = true;
        if (!self::estDemarree()) {
            $ok = session_start();
        } else {
            // changer l'identification de session (sécurité)
            $ok = session_regenerate_id();
        }
        return $ok;
    }

    /**
     * Met fin à la session courante
     */
    public static function arreter() {
        session_unset();
        session_destroy();
    }

    /**
     * Etat de la session
     * @return string chaîne décrivant l'état de la session
     */
    public static function etat() {
        $msg = "";
        if (self::estDemarree()) {
            $msg .= "@IP client : " . $_SERVER['REMOTE_ADDR'] . "<br/>";
            $msg .= "id de session : " . Session::idSession() . "<br/>";
            $msg .= "Tableau de session :<br/>";
            // pour mettre la sortie de "var_dump" dans un tampon et la récupérer dans une chaîne
            ob_start();
            var_dump($_SESSION);
            // récupération et effacement du contenu du tampon
            $dump = ob_get_contents();
            ob_end_clean();
            // le contenu va compléter la chaîne (le message) à retourner
            $msg .= $dump;
//            $msg .= htmlspecialchars($dump, ENT_QUOTES);
        } else {
            $msg = "Session inactive" . "<br/>";
        }
        return $msg;
    }

    /**
     * accesseur pour une variable de session
     * @param string $nom : nom de la variable de session à consulter
     * @return unknown type : valeur actuelle de la variable de session
     */
    public static function getObjetSession(string $nom) {
        $retour = null;
        if (self::estDemarree()) {
            if (isset($_SESSION[$nom])) {
                $retour = $_SESSION[$nom];
            }
        }
        return $retour;
    }

    /**
     *  mutateur pour une variable de session
     * @param string $nom : nom de la variable de session à modifier
     * @param unknown type $valeur : nouvelle valeur à affecter à la variable de session
     * @return bool : =true si l'affectation a bien eu lieu ; =false sinon (session non démarrée)
     */
    public static function setObjetSession(string $nom, $valeur): bool {
        $retour = false;
        if (self::estDemarree()) {
            $_SESSION[$nom] = $valeur;
            $retour = true;
        }
        return $retour;
    }
    
    /**
     * Accesseur de l'identifiant de session
     * @return string : identifiant de session si une session est active ; chaîne vide sinon
     */
    public static function idSession() : string {
        return session_id();
    }

}
