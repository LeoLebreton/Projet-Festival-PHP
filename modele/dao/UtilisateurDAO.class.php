<?php
namespace modele\dao;

use modele\metier\Utilisateur;
use PDO;

/**
 * Description of UtilisateurDAO
 * Classe métier  :  Utilisateur
 * @author prof
 * @version 2017
 */
class UtilisateurDAO {
    /**
     * crée un objet métier à partir d'un enregistrement
     * @param array $enreg
     * @return Utilisateur objet métier obtenu
     */
    protected static function enregVersMetier(array $enreg) {
        $id = $enreg['ID'];
        $civilite = $enreg['CIVILITE'];
        $nom = $enreg['NOM'];
        $prenom = $enreg['PRENOM'];
        $email = $enreg['EMAIL'];
        $login = $enreg['LOGIN'];
        $mdp = $enreg['MDP'];
        $objetMetier = new Utilisateur($id, $civilite, $nom, $prenom, $email, $login, $mdp);
        return $objetMetier;
    }
  /**
     * Recherche un utilisateur selon la valeur de son login
     * @param string $unLogin valeur de login recherchée
     * @return Utilisateur l'utilisateur trouvé ; null sinon
     */
    public static function getOneByLogin($unLogin) {
        $objetConstruit = null;
        $requete = "SELECT * FROM Utilisateur WHERE LOGIN = :login";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':login', $unLogin);
        $ok = $stmt->execute();
        // attention, $ok = true pour un select ne retournant aucune ligne
        if ($ok && $stmt->rowCount() > 0) {
            $objetConstruit = self::enregVersMetier($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return $objetConstruit;
    }
    
    
    
}
