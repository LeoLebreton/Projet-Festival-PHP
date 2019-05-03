<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>LieuDAO : test</title>
    </head>

    <body>

        <?php
        use modele\metier\Lieu;
        use modele\dao\LieuDAO;
        use modele\dao\Bdd;
        use controleur\Session;

require_once __DIR__ . '/../../includes/autoload.inc.php';

        $id = '0500L';
        Session::demarrer();
        Bdd::connecter();

        echo "<h2>Test LieuDAO</h2>";

        // Test n°1
        echo "<h3>1- Test getOneById</h3>";
        try {
            $objet = LieuDAO::getOneById($id);
            var_dump($objet);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°2
        echo "<h3>2- Test getAll</h3>";
        try {
            $lesObjets = LieuDAO::getAll();
            var_dump($lesObjets);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°3
        echo "<h3>3- insert</h3>";
        try {
            $id = '0258L';
            $objet = new Lieu($id, 'Boulangerie', '22 rue du Pigeon', '44000', 'Nantes',100);
            $ok = LieuDAO::insert($objet);
            if ($ok) {
                echo "<h4>ooo réussite de l'insertion ooo</h4>";
                $objetLu = LieuDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>*** échec de l'insertion ***</h4>";
            }
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }

        // Test n°3-bis
        echo "<h3>3-bis insert déjà présent</h3>";
        try {
            $id = '0258L';
            $objet = new Lieu($id, 'Boulangerie', '22 rue du Pigeon', '44000', 'Nantes',100);
            $ok = LieuDAO::insert($objet);
            if ($ok) {
                echo "<h4>*** échec du test : l'insertion ne devrait pas réussir  ***</h4>";
                $objetLu = LieuDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>ooo réussite du test : l'insertion a logiquement échoué ooo</h4>";
            }
         } catch (Exception $e) {
            echo "<h4>ooo réussite du test : la requête d'insertion a logiquement échoué ooo</h4>" . $e->getMessage();
        }
        
        // Test n°4
        echo "<h3>4- update</h3>";
        try {
            $objet->setCapacite(99);
            $objet->setVille('Manchester');
            $ok = LieuDAO::update($id, $objet);
            if ($ok) {
                echo "<h4>ooo réussite de la mise à jour ooo</h4>";
                $objetLu = LieuDAO::getOneById($id);
                var_dump($objetLu);
            } else {
                echo "<h4>*** échec de la mise à jour ***</h4>";
            }
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }

        // Test n°5
        echo "<h3>5- delete</h3>";
        try {
            $ok = LieuDAO::delete($id);
//            $ok = LieuDAO::delete("xxx");
            if ($ok) {
                echo "<h4>ooo réussite de la suppression ooo</h4>";
            } else {
                echo "<h4>*** échec de la suppression ***</h4>";
            }
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }
        
        // Test n°6
        echo "<h3>6- isAnExistingId</h3>";
        try {
            $id = "0500L";
            $ok = LieuDAO::isAnExistingId($id);
            $ok = $ok && !LieuDAO::isAnExistingId('AZERTY');
            if ($ok) {
                echo "<h4>ooo test réussi ooo</h4>";
            } else {
                echo "<h4>*** échec du test ***</h4>";
            }
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }
        
        Bdd::deconnecter();
        Session::arreter();
        ?>


    </body>
</html>
