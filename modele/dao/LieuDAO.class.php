<?php
namespace modele\dao;

use modele\metier\Lieu;
use PDOStatement;
use PDO;

/**
 * Description of LieuDAO
 * Classe métier :  Lieu
 * @author btssio
 * @version 2018
 */
class LieuDAO {

    /**
     * Instancier un objet de la classe Lieu à partir d'un enregistrement de la table LIEU
     * @param array $enreg
     * @return Lieu
     */
    protected static function enregVersMetier(array $enreg) {
        $id = $enreg['ID'];
        $nom = $enreg['NOM'];
        $adresse = $enreg['ADRESSERUE'];
        $codePostal = $enreg['CODEPOSTAL'];
        $ville = $enreg['VILLE'];
        $capacite = $enreg['CAPACITE'];
        $unLieu = new Lieu($id, $nom, $adresse, $codePostal, $ville, $capacite);

        return $unLieu;
    }
    
    /**
     * Valorise les paramètres d'une requête préparée avec l'état d'un objet Lieu
     * @param Lieu $objetMetier un lieu
     * @param PDOStatement $stmt requête préparée
     */
    protected static function metierVersEnreg(Lieu $objetMetier, PDOStatement $stmt) {
        // On utilise bindValue plutôt que bindParam pour éviter des variables intermédiaires
        // Note : bindParam requiert une référence de variable en paramètre n°2 ; 
        // avec bindParam, la valeur affectée à la requête évoluerait avec celle de la variable sans
        // qu'il soit besoin de refaire un appel explicite à bindParam
        $stmt->bindValue(':id', $objetMetier->getId());
        $stmt->bindValue(':nom', $objetMetier->getNom());
        $stmt->bindValue(':adresse', $objetMetier->getAdresse());
        $stmt->bindValue(':codePostal', $objetMetier->getCodePostal());
        $stmt->bindValue(':ville', $objetMetier->getVille());
        $stmt->bindValue(':capacite', $objetMetier->getCapacite());
    }

    /**
     * Retourne la liste de tous les lieux
     * @return array tableau d'objets de type Lieu
     */
    public static function getAll() {
        $lesObjets = array();
        $requete = "SELECT * FROM Lieu ORDER BY NOM";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            // Tant qu'il y a des enregistrements dans la table
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //ajoute un nouveau lieu au tableau
                $lesObjets[] = self::enregVersMetier($enreg);
            }
        }
        return $lesObjets;
    }

    /**
     * Recherche un lieu selon la valeur de son identifiant
     * @param string $id
     * @return Lieu le lieu trouvé ; null sinon
     */
    public static function getOneById($id) {
        $objetConstruit = null;
        $requete = "SELECT * FROM Lieu WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        // attention, $ok = true pour un select ne retournant aucune ligne
        if ($ok && $stmt->rowCount() > 0) {
            $objetConstruit = self::enregVersMetier($stmt->fetch(PDO::FETCH_ASSOC));
        }
        return $objetConstruit;
    }

    /**
     * Insérer un nouvel enregistrement dans la table à partir de l'état d'un objet métier
     * @param Lieu $objet objet métier à insérer
     * @return boolean =FALSE si l'opération échoue
     */
    public static function insert(Lieu $objet) {
        $requete = "INSERT INTO Lieu VALUES (:id, :nom, :adresse, :codePostal, :ville, :capacite)";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }

    /**
     * Mettre à jour enregistrement dans la table à partir de l'état d'un objet métier
     * @param string identifiant de l'enregistrement à mettre à jour
     * @param Lieu $objet objet métier à mettre à jour
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function update($id, Lieu $objet) {
        $ok = false;
        $requete = "UPDATE Lieu SET NOM=:nom, ADRESSERUE=:adresse,
           CODEPOSTAL=:codePostal, VILLE=:ville, CAPACITE=:capacite
           WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }
    
    /**
     * Détruire un enregistrement de la table LIEU d'après son identifiant
     * @param string identifiant de l'enregistrement à détruire
     * @return boolean =TRUE si l'enregistrement est détruit, =FALSE si l'opération échoue
     */
    public static function delete($id) {
        $ok = false;
        $requete = "DELETE FROM Lieu WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        $ok = $ok && ($stmt->rowCount() > 0);
        return $ok;
    }

    /**
     * Permet de vérifier s'il existe ou non un lieu ayant déjà le même identifiant dans la BD
     * @param string $id identifiant du lieu à tester
     * @return boolean =true si l'id existe déjà, =false sinon
     */
    public static function isAnExistingId($id) {
        $requete = "SELECT COUNT(*) FROM Lieu WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn(0);
    }
    
    /**
     * Permet de vérifier s'il existe ou non un lieu portant déjà le même nom dans la BD
     * En mode modification, l'enregistrement en cours de modification est bien entendu exclu du test
     * @param boolean $estModeCreation =true si le test est fait en mode création, =false en mode modification
     * @param string $id identifiant du lieu à tester
     * @param string $nom nom du lieu à tester
     * @return boolean =true si le nom existe déjà, =false sinon
     */
    public static function isAnExistingName($estModeCreation, $id, $nom) {
        $nom = str_replace("'", "''", $nom);
        // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
        // on vérifie la non existence d'un autre lieu (id!='$id') portant 
        // le même nom
        if ($estModeCreation) {
            $requete = "SELECT COUNT(*) FROM Lieu WHERE NOM=:nom";
            $stmt = Bdd::getPdo()->prepare($requete);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
        } else {
            $requete = "SELECT COUNT(*) FROM Lieu WHERE NOM=:nom AND ID<>:id";
            $stmt = Bdd::getPdo()->prepare($requete);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
        }
        return $stmt->fetchColumn(0);
    }
    
    
}
