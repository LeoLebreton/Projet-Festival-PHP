<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test de la classe GestionParametres</title>
    </head>
    <body>
        <?php

        use controleur\Session;
        use controleur\GestionParametres;

        require_once __DIR__ . '/../../includes/autoload.inc.php';

        echo "<h2>Test unitaire de la classe GestionParametres</h2>";
        Session::demarrer();
        echo "<h3>1- Test de lecture du fichier des paramètres</h3>";
        $tabParam = GestionParametres::initialiser();
        var_dump($tabParam);

        echo "<h3>2-1- Test accesseur valeur existante</h3>";
        $loginUtilisateurBdd = GestionParametres::get("login");
        if ($loginUtilisateurBdd === "") {
            echo "Pb, le login devrait être lu";
        } else {
            echo "Test OK ; login : " . $loginUtilisateurBdd;
        }

        echo "<h3>2-2- Test accesseur valeur inconnue</h3>";
        $valeurLue = GestionParametres::get("inconnue");
        if ($valeurLue === "") {
            echo "Test OK, la valeur n'a pu être lue";
        } else {
            echo "Pb, la valeur aurait du être chaîne vide ; valeur Lue : " . $valeurLue;
        }
        ?>
    </body>
</html>
