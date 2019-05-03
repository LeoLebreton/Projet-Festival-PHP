<?php
/**
 * Classe utilitaire : gestion des sessions PHP pour l'authentification des utilisateurs
 * @author prof
 * @version 2017
 * @version 2018 : héritage d'une classe Session plus générique
 * 
 */

namespace controleur;

use modele\metier\Utilisateur;

class SessionAuthentifiee extends Session {

    /**
     * Test d'ouverture de session avec authentification
     * @return bool : =true si une session est active 
     * ET qu'un utilisateur authentifié est associé à cette session avec la même adresse IP
     */
    public static function estConnecte() : bool{
        return ( parent::estDemarree() && parent::getObjetSession("ip_session") == $_SERVER['REMOTE_ADDR'] && isset($_SESSION['utilisateur']));
    }
      

    /**
     * Fin de la session et donc de la session avec authentification
     */
    public static function seDeconnecter() {
        parent::arreter();
    }

    /**
     * Ouvre une session avec authentification
     * @param Utilisateur $unUtilisateur authentifié à enregistrer
     */
    public static function seConnecter(Utilisateur $unUtilisateur) {
        parent::demarrer();
        self::setObjetSession("ip_session", $_SERVER['REMOTE_ADDR']);
        self::setObjetSession("utilisateur", $unUtilisateur);
    }

    /**
     * Etat courant de la session active si elle existe
     * @return string : chaîne contenant les valeurs des variables de la session active ou chaîne vide
     */
    public static function etat() :string {
        /* @var Utilisateur $utilisateur */
        $msg = parent::etat();
        if (self::estConnecte()) {
            $msg .= "Session id : " . session_id() . "<br/>";
            $utilisateur = parent::getObjetSession("utilisateur");
            $msg .= " - " . "utilisateur connecté : " . $utilisateur . "<br/>";
            $msg .= " - " . "@IP session : " . parent::getObjetSession("ip_session") . "<br/>";
        } else {
            $msg = "Session non connectée" . "<br/>";
        }
        return $msg;
    }


    /**
     * Accesseur de l'adresse ip de son poste client si un utilisateur s'est authentifié
     * @return string : adresse ip de l'utilisateur connecté ou null
     */
    public static function getIp() : string {
        return parent::getObjetSession("ip_session");
    }
    
    /**
     * Accesseur de l'objet de type Utilisateur si un utilisateur s'est authentifié
     * @return Utilisateur : objet Utilisateur de l'utilisateur connecté ou null
    */
    public static function getUtilisateur() : Utilisateur {
        return parent::getObjetSession("utilisateur");
    }

}
