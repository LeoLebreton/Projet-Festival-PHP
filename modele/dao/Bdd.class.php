<?php
/**
 * Description of Bdd
 * Singleton de connexion à la base de données
 * Remarque : les noms de colonnes de la BDD seront automatiquement convertis en majuscules
 * (option PDO : $pdo_options[PDO::ATTR_CASE] = PDO::CASE_UPPER
 * @author prof
 * @version 2017 
 * @version 2018 : lecture des paramètres dans le fichier de configuration "includes/parametres.ini"
 */

namespace modele\dao;

use \PDO;
use \PDOException;
use controleur\GestionParametres;

class Bdd {

    /**
     * @var PDO Objet de type PDO, dépositaire de la connexion courante à la BDD
     */
    private static $pdo = null;

    /**
     * Crée un objet de type PDO et ouvre la connexion 
     * @return \PDO un objet de type PDO pour accéder à la base de données
     */
    public static function connecter()  {
        // on ne crée une connexion que si elle n'existe pas déjà ...
        if (is_null(self::$pdo)) {
            try {
                // Paramètres de connexion : lire le fichier de configuration en variable de session
                GestionParametres::initialiser();
                // Attributs de connexion
                $pdo_options = array();
                $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;       // permet la gestion des exceptions
                $pdo_options[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";  // pour récupérer les données en UTF8
                $pdo_options[PDO::ATTR_CASE] = PDO::CASE_UPPER;                 // pour compatibilité avec Oracle database (noms de champs trancrits en majuscules)
                // instanciation d'un objet de connexion PDO
                self::$pdo = new PDO(
                    GestionParametres::get("dsn").GestionParametres::get("bdd"),
                    GestionParametres::get("login"), GestionParametres::get("mdp"),
                    $pdo_options
                );
                
            } catch (PDOException $e) {
                echo "ERREUR : " . $e->getMessage();
                die();
            }
        }
        return self::$pdo;
    }

    public static function deconnecter() {
        self::$pdo = null;
    }

    /**
     * Accesseur
     * @return PDO objet d'accès à la BDD ou bien null
     */
    public static function getPdo()  {
        return self::$pdo;
    }
}
