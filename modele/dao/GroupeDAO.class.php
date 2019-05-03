<?php
namespace modele\dao;

use modele\metier\Groupe;
use PDOStatement;
use PDO;

/**
 * Description of GroupeDAO
 * Classe métier :  Groupe
 * @author prof
 * @version 2017
 */
class GroupeDAO {


    /**
     * Instancier un objet de la classe Groupe à partir d'un enregistrement de la table GROUPE
     * @param array $enreg
     * @return Groupe
     */
    protected static function enregVersMetier(array $enreg) {
        $id = $enreg['ID'];
        $nom = $enreg['NOM'];
        $identite = $enreg['IDENTITERESPONSABLE'];
        $adresse = $enreg['ADRESSEPOSTALE'];
        $nbPers = $enreg['NOMBREPERSONNES'];
        $nomPays = $enreg['NOMPAYS'];
        $hebergement = $enreg['HEBERGEMENT'];
        $unGrp = new Groupe($id, $nom, $identite, $adresse, $nbPers, $nomPays, $hebergement);

        return $unGrp;
    }
    /**
     * Valorise les paramètres d'une requête préparée avec l'état d'un objet Groupe
     * @param Groupe $objetMetier un groupe
     * @param PDOStatement $stmt requête préparée
     */
    protected static function metierVersEnreg(Groupe $objetMetier, PDOStatement $stmt) {
        // On utilise bindValue plutôt que bindParam pour éviter des variables intermédiaires
        // Note : bindParam requiert une référence de variable en paramètre n°2 ; 
        // avec bindParam, la valeur affectée à la requête évoluerait avec celle de la variable sans
        // qu'il soit besoin de refaire un appel explicite à bindParam
        $stmt->bindValue(':id', $objetMetier->getId());
        $stmt->bindValue(':nom', $objetMetier->getNom());
        $stmt->bindValue(':resp', $objetMetier->getIdentite());
        $stmt->bindValue(':adresse', $objetMetier->getAdresse());
        $stmt->bindValue(':nbPers', $objetMetier->getNbPers());
        $stmt->bindValue(':pays', $objetMetier->getNomPays());
        $stmt->bindValue(':hebergement', $objetMetier->getHebergement());
    }


    /**
     * Retourne la liste de tous les groupes
     * @return array tableau d'objets de type Groupe
     */
    public static function getAll() {
        $lesObjets = array();
        $requete = "SELECT * FROM Groupe ORDER BY NOM";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            // Tant qu'il y a des enregistrements dans la table
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //ajoute un nouveau groupe au tableau
                $lesObjets[] = self::enregVersMetier($enreg);
            }
        }
        return $lesObjets;
    }

    /**
     * Recherche un groupe selon la valeur de son identifiant
     * @param string $id
     * @return Groupe le groupe trouvé ; null sinon
     */
    public static function getOneById($id) {
        $objetConstruit = null;
        $requete = "SELECT * FROM Groupe WHERE ID = :id";
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
     * Retourne la liste des groupes attribués à un établissement donné
     * @param string $idEtab
     * @return array tableau d'éléments de type Groupe
     */
    public static function getAllByEtablissement($idEtab) {
        $lesGroupes = array();  // le tableau à retourner
        $requete = "SELECT * FROM Groupe
                    WHERE ID IN (
                    SELECT DISTINCT ID FROM Groupe g
                            INNER JOIN Attribution a ON a.IDGROUPE = g.ID 
                            WHERE IDETAB=:id
                    )";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $idEtab);
        $ok = $stmt->execute();
        if ($ok) {
            // Tant qu'il y a des enregistrements dans la table
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                //ajoute un nouveau groupe au tableau
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        } 
        return $lesGroupes;
    }

    
    /**
     * Retourne la liste des groupes souhaitant un hébergement, ordonnée par id
     * @return array tableau d'éléments de type Groupe
     */
    public static function getAllToHost() {
        $lesGroupes = array();
        $requete = "SELECT * FROM Groupe WHERE HEBERGEMENT='O' ORDER BY ID";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesGroupes[] = self::enregVersMetier($enreg);
            }
        }
        return $lesGroupes;
    }

    /**
     * Insérer un nouvel enregistrement dans la table à partir de l'état d'un objet métier
     * @param Groupe $objet objet métier à insérer
     * @return boolean =FALSE si l'opération échoue
     */
    public static function insert(Groupe $objet) {
        $requete = "INSERT INTO Groupe VALUES (:id, :nom, :resp, :adresse, :nbPers, :pays, :hebergement)";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }

    /**
     * Mettre à jour enregistrement dans la table à partir de l'état d'un objet métier
     * @param string identifiant de l'enregistrement à mettre à jour
     * @param Groupe $objet objet métier à mettre à jour
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function update($id, Groupe $objet) {
        $ok = false;
        $requete = "UPDATE  Groupe SET NOM=:nom, IDENTITERESPONSABLE=:resp,
           ADRESSEPOSTALE=:adresse, NOMBREPERSONNES=:nbPers, NOMPAYS=:pays,
           HEBERGEMENT=:hebergement
           WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }
    
    /**
     * Détruire un enregistrement de la table GROUPE d'après son identifiant
     * @param string identifiant de l'enregistrement à détruire
     * @return boolean =TRUE si l'enregistrement est détruit, =FALSE si l'opération échoue
     */
    public static function delete($id) {
        $ok = false;
        $requete = "DELETE FROM Groupe WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        $ok = $ok && ($stmt->rowCount() > 0);
        return $ok;
    }

    /**
     * Permet de vérifier s'il existe ou non un groupe ayant déjà le même identifiant dans la BD
     * @param string $id identifiant du groupe à tester
     * @return boolean =true si l'id existe déjà, =false sinon
     */
    public static function isAnExistingId($id) {
        $requete = "SELECT COUNT(*) FROM Groupe WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn(0);
    }
    /**
     * Permet de vérifier s'il existe ou non un groupe portant déjà le même nom dans la BD
     * En mode modification, l'enregistrement en cours de modification est bien entendu exclu du test
     * @param boolean $estModeCreation =true si le test est fait en mode création, =false en mode modification
     * @param string $id identifiant du groupe à tester
     * @param string $nom nom du groupe à tester
     * @return boolean =true si le nom existe déjà, =false sinon
     */
    public static function isAnExistingName($estModeCreation, $id, $nom) {
        $nom = str_replace("'", "''", $nom);
        // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
        // on vérifie la non existence d'un autre groupe (id!='$id') portant 
        // le même nom
        if ($estModeCreation) {
            $requete = "SELECT COUNT(*) FROM Groupe WHERE NOM=:nom";
            $stmt = Bdd::getPdo()->prepare($requete);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
        } else {
            $requete = "SELECT COUNT(*) FROM Groupe WHERE NOM=:nom AND ID<>:id";
            $stmt = Bdd::getPdo()->prepare($requete);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nom', $nom);
            $stmt->execute();
        }
        return $stmt->fetchColumn(0);
    }
    
    
}
