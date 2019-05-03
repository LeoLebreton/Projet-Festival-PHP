<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>UtilisateurDAO : test</title>
    </head>

    <body>

        <?php

        use modele\dao\UtilisateurDAO;
        use modele\dao\Bdd;
        use modele\metier\Utilisateur;
        use controleur\Session;

        require_once __DIR__ . '/../../includes/autoload.inc.php';

        $login = 'jjoubert';
        Session::demarrer();
        Bdd::connecter();

        echo "<h2>Test UtilisateurDAO</h2>";
        // Test n°1
        echo "<h3>1- getOneByLogin</h3>";
        try {
            /* @var $objet Utilisateur */
            $objet = UtilisateurDAO::getOneByLogin($login);
            echo "<p>Voici le nom de l'utilisateur de login $login : ". $objet->getCivilite() . " " . $objet->getNom() . "</p>";
            var_dump($objet);
        } catch (Exception $ex) {
            echo "<h4>*** échec de la requête ***</h4>" . $ex->getMessage();
        }

        Bdd::deconnecter();
        Session::arreter();
        ?>


    </body>
</html>
