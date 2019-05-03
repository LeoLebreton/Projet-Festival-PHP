<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Contrôleur Offres : test</title>
    </head>

    <body>

    <?php

        use controleur\CtrlOffres;
        
        use modele\dao\EtablissementDAO;
        use modele\dao\TypeChambreDAO;
        use modele\dao\Bdd;
        use controleur\Session;

        require_once __DIR__ . '/../../includes/autoload.inc.php';
        Session::demarrer();
        Bdd::connecter();

        echo "<h2>Test de CtrlOffres</h2>";
        
        $idEtab = '0350773A';
        $idTypeCh = 'C2';
        $idGroupe = 'g005';
        
        /* @var CtrlOffres $leControleur */
        $leControleur = new CtrlOffres();
        // Test n°1
        echo "<h3>1- getTabNbChambresAttribueesParTypePourUnEtab</h3>";
        try {
            $tab = $leControleur->getTabNbChambresAttribueesParTypePourUnEtab($idEtab);
            var_dump($tab);
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }
        
        // Test n°2
        echo "<h3>2- getTabNbChambresOffertesParEtabParType</h3>";
        try {
            $tab = $leControleur->getTabNbChambresOffertesParEtabParType();
            var_dump($tab);
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }

// getTabNbChambresOffertesParEtabParType

        Bdd::deconnecter();
        Session::arreter();
        ?>


    </body>
</html>
