<?php

namespace modele\dao;
use modele\metier\Representation;
use modele\metier\Lieu;
use modele\metier\Groupe;
use PDOStatement;
use PDO;

/**
 * Description of RepresentationDao
 *
 * @author hcaillaud
 */
class RepresentationDao {

    /**
     * Instancier un objet de type Representation à partir d'un enregistrement de la table REPRESENTATION
     * @param array $enreg
     * @return Representation
     */
    protected static function enregVersMetier(array $enreg) {
        $id = $enreg['ID'];
        $idGroupe = GroupeDAO::getOneById($enreg['IDGROUPE']);
        $idLieu = LieuDAO::getOneById($enreg['IDLIEU']);
        $date = $enreg['DATE'];
        $heureDebut = $enreg['HEUREDEBUT'];
        $heureFin = $enreg['HEUREFIN'];
        $uneRepresentation = new Representation($id, $idGroupe, $idLieu, $date, $heureDebut, $heureFin);

        return $uneRepresentation;
    }

    /**
     * Valorise les paramètres d'une requête préparée avec l'état d'un objet Representation
     * @param Representatin $objetMetier une Representatin
     * @param PDOStatement $stmt requête préparée
     */
    protected static function metierVersEnreg(Representation $objetMetier, PDOStatement $stmt) {
        $stmt->bindValue(':ID', $objetMetier->getId());
        $stmt->bindValue(':IDGROUPE', $objetMetier->getIdGroupe()->getId());
        $stmt->bindValue(':IDLIEU', $objetMetier->getIdLieu()->getId());
        $stmt->bindValue(':DATE', $objetMetier->getDate());
        $stmt->bindValue(':HEUREDEBUT', $objetMetier->getHeureDebut());
        $stmt->bindValue(':HEUREFIN', $objetMetier->getHeureFin());
    }

    /**
     * Retourne la liste de toutes les Representation
     * @return array tableau d'objets de type Representation
     */
    public static function getAll() {
        $lesObjets = array();
        $requete = "SELECT * FROM Representation ORDER BY ID";
        $stmt = Bdd::getPdo()->prepare($requete);
        $ok = $stmt->execute();
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesObjets[] = self::enregVersMetier($enreg);
            }
        }
        return $lesObjets;
    }

    /**
     * Recherche une representation selon la valeur de son identifiant
     * @param string $id
     * @return Representation la representation trouvé ; null sinon
     */
    public static function getOneById($id) {
        $objetConstruit = null;
        $requete = "SELECT * FROM Representation WHERE ID = :id";
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
     * Retourne toutes les représentations triées par date
     * @return type
     */
    public static function getRepresentationByDate($date) {
        $objetConstruit = null;
        $requete = "SELECT * FROM Representation WHERE DATE=:DATE";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':DATE', $date);
        $ok = $stmt->execute();
        // attention, $ok = true pour un select ne retournant aucune ligne
        if ($ok) {
            while ($enreg = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lesObjets[] = self::enregVersMetier($enreg);
            }
        }
        return $lesObjets;
    }

    /**
     * Insérer un nouvel enregistrement dans la table à partir de l'état d'un objet métier
     * @param Representation $objet objet métier à insérer
     * @return boolean =FALSE si l'opération échoue
     */
    public static function insert(Representation $objet) {
        $requete = "INSERT INTO Representation VALUES (:ID, :IDGROUPE, :IDLIEU, :DATE, :HEUREDEBUT, :HEUREFIN)";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        if(!RepresentationDao::isAnExistingId($objet->getId())){
           $ok = $stmt->execute();
            return ($ok && $stmt->rowCount() > 0);
        }else{
            $ok = false;
        }
    }

    /**
     * Mettre à jour enregistrement dans la table à partir de l'état d'un objet métier
     * @param string identifiant de l'enregistrement à mettre à jour
     * @param Representation $objet objet métier à mettre à jour
     * @return boolean =FALSE si l'opérationn échoue
     */
    public static function update($id, Representation $objet) {
        $ok = false;
        $requete = "UPDATE Representation SET DATE=:DATE, HEUREDEBUT=:HEUREDEBUT,
            HEUREFIN=:HEUREFIN, IDGROUPE=:IDGROUPE, IDLIEU=:IDLIEU WHERE ID=:ID";
        $stmt = Bdd::getPdo()->prepare($requete);
        self::metierVersEnreg($objet, $stmt);
        $stmt->bindParam(':ID', $id);
        $ok = $stmt->execute();
        return ($ok && $stmt->rowCount() > 0);
    }

    /**
     * Détruire un enregistrement de la table REPRESENTATION d'après son identifiant
     * @param string identifiant de l'enregistrement à détruire
     * @return boolean =TRUE si l'enregistrement est détruit, =FALSE si l'opération échoue
     */
    public static function delete($id) {
        $ok = false;
        $requete = "DELETE FROM Representation WHERE ID = :id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $ok = $stmt->execute();
        $ok = $ok && ($stmt->rowCount() > 0);
        return $ok;
    }

    /**
     * Permet de vérifier s'il existe ou non une Representation ayant déjà le même identifiant dans la BD
     * @param string $id identifiant de la Representation à tester
     * @return boolean =true si l'id existe déjà, =false sinon
     */
    public static function isAnExistingId($id) {
        $requete = "SELECT COUNT(*) FROM Representation WHERE ID=:id";
        $stmt = Bdd::getPdo()->prepare($requete);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn(0);
    }
    /**
    * Permet de vérifier s'il existe ou non une représenation au même moment dans la BD
    * En mode modification, l'enregistrement en cours de modification est bien entendu exclu du test
    * @param boolean $estModeCreation =true si le test est fait en mode création, =false en mode modification
    * @param string $id identifiant de la représentation à tester
    * @param string $groupe groupe de la représentation à tester
    * @param string $lieu lieu de la représentation à tester
    * @param string $jour jour de la représentation à tester
    * @param string $debut heure de début de la représentation à tester
    * @return boolean =true si le nom existe déjà, =false sinon
    */
   public static function isAnExistingCreneau($estModeCreation, Representation $uneRepresentation) {

       $lieuRepres = $uneRepresentation->getIdLieu();
       $dateRepres = $uneRepresentation->getDate();
       $heureDebRepres = $uneRepresentation->getHeureDebut();
       $idRepresentation = $uneRepresentation->getId();
       $idLieu=$lieuRepres->getId();
       if ($estModeCreation) {
           $requete = "SELECT COUNT(*) FROM Representation WHERE DATE=:dateRepres AND HEUREDEBUT=:heureDebRepres AND IDLIEU=:lieuRepres";
           $stmt = Bdd::getPdo()->prepare($requete);
           $stmt->bindParam(':dateRepres', $dateRepres);
           $stmt->bindParam(':heureDebRepres', $heureDebRepres);
           $stmt->bindParam(':lieuRepres', $idLieu);
           $stmt->execute();
       } else {
           $requete = "SELECT COUNT(*) FROM Representation WHERE DATE=:dateRepres AND HEUREDEBUT=:heureDebRepres AND IDLIEU=:lieuRepres AND ID=:ID";
           $stmt = Bdd::getPdo()->prepare($requete);
           $stmt->bindParam(':ID', $idRepresentation);
           $stmt->bindParam(':heureDebRepres', $heureDebRepres);
           $stmt->bindParam(':dateRepres', $dateRepres);
           $stmt->bindParam(':lieuRepres', $idLieu);
           $stmt->execute();
       }
       return $stmt->fetchColumn(0);
   }

   public static function isAnExistingGroupRepres($estModeCreation, Representation $uneRepresentation) {
       $id = $uneRepresentation->getId();
       $dateRepres = $uneRepresentation->getDate();
       $heureDebRepres = $uneRepresentation->getHeureDebut();
       //$heureFin = $uneRepresentation->getFin();
       $groupe = $uneRepresentation->getIdGroupe();
       $idGroupe = $groupe->getId();
       // S'il s'agit d'une création, on vérifie juste la non existence du nom sinon
       // on vérifie la non existence d'un autre groupe (id!='$id') portant
       // le même nom
       if ($estModeCreation) {
           $requete = "SELECT COUNT(*) FROM Representation WHERE DATE=:dateRepres AND HEUREDEBUT=:heureDebRepres AND IDGROUPE=:idGroupe ";
           $stmt = Bdd::getPdo()->prepare($requete);
           $stmt->bindParam(':dateRepres', $dateRepres);
           $stmt->bindParam(':heureDebRepres', $heureDebRepres);
           //$stmt->bindParam(':heureFin', $heureFin);
           $stmt->bindParam(':idGroupe', $idGroupe);
           $stmt->execute();
       } else {
           $requete = "SELECT COUNT(*) FROM Representation WHERE DATE=:dateRepres AND HEUREDEBUT=:heureDebRepres AND IDGROUPE=:idGroupe AND ID=:id";
           $stmt = Bdd::getPdo()->prepare($requete);
           $stmt->bindParam(':id', $id);
           $stmt->bindParam(':dateRepres', $dateRepres);
           $stmt->bindParam(':heureDebRepres', $heureDebRepres);
           //$stmt->bindParam(':heureFin', $heureFin);
           $stmt->bindParam(':idGroupe', $idGroupe);
           $stmt->execute();
       }
       return $stmt->fetchColumn(0);
   }
}
