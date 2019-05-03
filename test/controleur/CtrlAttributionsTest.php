<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>CtrlAttributions : test</title>
    </head>

    <body>

    <?php

        use controleur\CtrlAttributions;
        
        use modele\dao\EtablissementDAO;
        use modele\dao\TypeChambreDAO;
        use modele\dao\Bdd;
        use controleur\Session;

        require_once __DIR__ . '/../../includes/autoload.inc.php';
        Session::demarrer();
        Bdd::connecter();

        echo "<h2>Test de CtrlAttributions</h2>";
        
        $idEtab = '0350773A';
        $idTypeCh = 'C2';
        $idGroupe = 'g005';
        
        /* @var CtrlAttributions $leControleur */
        $leControleur = new CtrlAttributions();
        // Test n°1
        echo "<h3>1- getNbDispo</h3>";
        try {
            $tab = $leControleur->getNbDispo($idEtab, $idTypeCh);
            print_r($tab);
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }

        // Test n°2
        echo "<h3>2- getTabAttributions</h3>";
        try {
            $lesEtab = EtablissementDAO::getAllOfferingRooms();
            $lesTypesChambres = TypeChambreDAO::getAll();
            $tab = $leControleur->getTabAttributions($lesEtab, $lesTypesChambres);
            var_dump($tab);
        } catch (Exception $e) {
            echo "<h4>*** échec de la requête ***</h4>" . $e->getMessage();
        }

        Bdd::deconnecter();
        Session::arreter();
        ?>


    </body>
</html>
