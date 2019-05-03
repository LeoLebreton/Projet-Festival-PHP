<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test de la classe Session</title>
    </head>
    <body>
        <?php

        use controleur\Session;
        use modele\metier\Utilisateur;

require_once __DIR__ . '/../../includes/autoload.inc.php';

        echo "<h2>Test unitaire de la classe Session</h2>";
        echo "<h3>1-1 Test avant démarrage</h3>";
        if (Session::estDemarree()) {
            echo "Pb, la session devrait être arrêtée";
        } else {
            echo "Test OK (session inactive)";
        }

        echo "<h3>1-2 Test après démarrage</h3>";
        Session::demarrer();
        if (!Session::estDemarree()) {
            echo "Pb, la session devrait être démarrée";
        } else {
            echo "Test OK ; id session : " . Session::idSession();
        }


        echo "<h3>2- Test session démarrée</h3>";
        /* @var  $monUtilisateur Utilisateur */
        $unObjet = new Utilisateur(1, "Mme", "Sand", "George", "gsand@free.fr", "gsand", "secret");
        $uneValeurSimple = 3.14159;
        $unTableau = array("a", "b", "c", array(1, 2, array("A", "B")), array(3, 4), "d");
        Session::demarrer();
        echo "<h4>2-1 Test état session</h4>";
        if (Session::estDemarree()) {
            echo "Test après re-démarrage Ok<br/>";
            Session::setObjetSession("val1", $uneValeurSimple);
            Session::setObjetSession("val2", $unObjet);
            Session::setObjetSession("val3", $unTableau);
            echo "Vérifiez l'état :<br/>";
            echo Session::etat();
        } else {
            echo "Pb, la session devrait être démarée";
        }
        echo "<h4>2-2 Test accesseurs session</h4>";
        echo "valeur simple : " . Session::getObjetSession("val1") . "<br/>";
        echo "objet : " . Session::getObjetSession("val2") . "<br/>";
        ob_start();
        var_dump(Session::getObjetSession("val3")) . "<br/>";
        $dump = ob_get_clean();
        echo "tableau : " . $dump . "<br/>";

        echo "<h3>3- Test session arrêtée</h3>";
        Session::arreter();
        if (Session::estDemarree()) {
            echo "Pb, la session devrait être arrêtée";
        } else {
            echo "Test OK";
        }
        ?>
    </body>
</html>
