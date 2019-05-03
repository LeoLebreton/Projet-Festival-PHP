<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test de la classe VueSaisieTypeChambre</title>
    </head>
    <body>
        <?php
        use vue\typesChambres\VueSaisieTypeChambre;
        use modele\metier\TypeChambre;
        
        use controleur\Session;
        use controleur\GestionParametres;
        
        require_once __DIR__ . '/../../includes/autoload.inc.php';
        
        echo "<h2>Test de la classe VueSaisieTypeChambre</h2>";
        Session::demarrer();
        
        GestionParametres::initialiser();
        include GestionParametres::racine() . "includes/fonctionsUtilitaires.inc.php";
        include GestionParametres::racine() . "includes/fonctionsDatesTimes.inc.php";

        $vue= new VueSaisieTypeChambre();
        $vue->setTitre("Festival - types de chambres");
        $vue->setVersion("V de test");

        // On récupère un tableau composé d'objets de type TypeChambre lus dans la BDD
        $unTC = new TypeChambre("C9", "Dortoir");
        $vue->setUnTypeChambre($unTC);
        $vue->setActionRecue("modifier");
        $vue->setActionAEnvoyer("validerModifier");
        $vue->setMessage("Modifier type chambre : " . $vue->getUnTypeChambre()->getId() );
        $vue->setEstConnecte(true);
        $vue->setLienOngletActif(true);
        $vue->setIdentite("Al Abonneur");
        $vue->afficher();        
           
        ?>
    </body>
</html>
