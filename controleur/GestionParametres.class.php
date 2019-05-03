<?php
/*
 * Classe utilitaire : gestion des paramètres de configuration de l'application en utilisant les sessions PHP 
 * Les paramètres de configuration sont stockés dans un fichier includes/parametres.ini
 * Après initialisation, un tableau associatif des paramètres est enregistré 
 * dans la variable de session nommée 'parametres' : $_SESSION['parametres']
 * @author prof
 * @version 2018
 */

namespace controleur;
use controleur\Session;

class GestionParametres {

    /**
     * Initialisation du tableau des paramètres en session
     * @return array : tableau des paramètres, enregistré dans la variable de session
     */
    public static function initialiser() : array {
        $tabParametres = Session::getObjetSession('parametres');
        // Pour ne pas relire le fichier de paramètres à chaque requête
        if (!isset($tabParametres)) {
            // si la liste des paramètres n'a pas encore été initialisée, lire le fichier de paramètres
            $tabParametres = parse_ini_file(__DIR__."/../includes/parametres.ini");
            Session::setObjetSession('parametres', $tabParametres);
        }
        // Le paramètre 'racineWeb' doit contenir le chemin relatif à la racine de l'application web,
        //  par rapport au "DOCUMENT_ROOT" du serveur. On supprime donc la partie "DOCUMENT_ROOT" du chemin d'accès absolu
        //  du répertoire courant pour obtenir un chemin d'accès relatif ; on doit toutefois remonter d'un niveau ('/../')
        //  pour sortir du sous-répertoire 'controleur')
        //  Exemple :
        //      DOCUMENT_ROOT : /var/www/html
        //      __DIR__ : /var/www/html/sites/2SLAM/FestivalPHP2018_2019_prof/controleur
        //      racineWeb = /sites/2SLAM/FestivalPHP2018_2019_prof/controleur/../
        // ON FORCE CE PARAMETRE : il est recalculé à chaque fois et ne risque pas de garder la valeur d'une précédente session
        $_SESSION['parametres']['racineWeb'] = str_replace($_SERVER['DOCUMENT_ROOT'],'',__DIR__)."/../";
        
        return $_SESSION['parametres'];
    }
    
    /**
     * Accesseur pour les paramètres de configuration
     * @param string $nomParametre : index du tableau associatif des paramètres de configuration
     * @return string : valeur corresppondant à l'index ou bien chaîne vide
     */
    public static function get(string $nomParametre) : string {
        $valeur = "";
        // si le paramètre existe
        $tabParametres = Session::getObjetSession('parametres');
        if ($tabParametres !== NULL) {
            if (isset($tabParametres[$nomParametre])){
                    $valeur = $tabParametres[$nomParametre];        
            }
        }
        // retourner la valeur obtenue ou ""
        return $valeur;
    }
    
    /**
     * Retourne le chemin d'accès absolu à la racine de l'application web sur le serveur
     * @return string chemin d'accès
     */
    public static function racine () : string {
        return $_SERVER['DOCUMENT_ROOT']."/".self::get("racineWeb");
    }
    /**
     * Mutateur pour les paramètres de configuration
     * @param string $nomParametre : index du tableau associatif des paramètres de configuration
     * @param string $valeur : valeur à enregistrer dans ce paramètre
     */
    public static function set(string $nomParametre, string $valeur) {
        // si le paramètre existe
        $tabParametres = Session::getObjetSession('parametres');
        if ($tabParametres !== NULL) {
            $tabParametres[$nomParametre] = $valeur;
            Session::setObjetSession('parametres', $tabParametres);
        }
    }
 
}
