<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>GroupeDAO : test</title>
    </head>

    <body>

        <?php

        use modele\metier\Groupe;
        use modele\dao\GroupeDAO;
        use modele\dao\Bdd;
        use controleur\Session;

require_once __DIR__ . '/../../includes/autoload.inc.php';

        $id = 'g010';
        Session::demarrer();
        Bdd::connecter();

        echo "<h2>Test GroupeDAO</h2>";

        // Test n°1
        echo "<h3>1- Test getOneById</h3>";
        try {
            $objet = GroupeDAO::getOneById($id);
            var_dump($objet);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°2
        echo "<h3>2- Test getAll</h3>";
        try {
            $lesObjets = GroupeDAO::getAll();
            var_dump($lesObjets);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°2-bis
        echo "<h3>2-bis Test getAllToHost</h3>";
        try {
            $lesObjets = GroupeDAO::getAllToHost();
            var_dump($lesObjets);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        // Test n°3
        echo "<h3>3- insert</h3>";
        try {
            $id = 'g999';
            $objet1 = new Groupe($id, 'Bagad Plougastel', 'Dan Ar Braz', 'Plougastel-Daoulas',98,'France - Bretagne','N');
            $ok = GroupeDAO::insert($objet1);
            if ($ok) {
                echo "<h4>ooo réussite de l'insertion ooo</h4>";
                $objetLu = GroupeDAO::getOneById($id);
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
            $objet2 = new Groupe($id, 'Muse', 'Matthew Bellamy','London',3,'Grande Bretagne','O');
            $ok = GroupeDAO::insert($objet2);
            if ($ok) {
                echo "<h4>*** échec du test : l'insertion ne devrait pas réussir  ***</h4>";
                $objetLu = Bdd::getOneById($id);
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
            $objet1->setNbPers(99);
            $objet1->setAdresse('Manchester');
            $ok = GroupeDAO::update($id, $objet1);
            if ($ok) {
                echo "<h4>ooo réussite de la mise à jour ooo</h4>";
                $objetLu = GroupeDAO::getOneById($id);
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
            $ok = GroupeDAO::delete($id);
//            $ok = GroupeDAO::delete("xxx");
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
            $id = "g010";
            $ok = GroupeDAO::isAnExistingId($id);
            $ok = $ok && !GroupeDAO::isAnExistingId('AZERTY');
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
