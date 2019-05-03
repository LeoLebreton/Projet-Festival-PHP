<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Test de la classe SessionAuthentifiee</title>
    </head>
    <body>
        <?php
        use controleur\SessionAuthentifiee;
        use modele\metier\Utilisateur;
        require_once __DIR__ . '/../../includes/autoload.inc.php';
        
        echo "<h2>Test unitaire de la classe SessionAuthentifiee</h2>";
        session_start();
        echo "<h3>1- Test avant connexion</h3>";
        if (SessionAuthentifiee::estConnecte()){
            echo "Pb, la session devrait être fermée";
        }else{
            echo "Test OK : aucun utilisateur n'est connecté";
        }
        
        echo "<h3>2- Test session connectée</h3>";
        /* @var  $monUtilisateur Utilisateur*/
        $monUtilisateur = new Utilisateur(1, "Mme", "Sand", "George", "gsand@free.fr", "gsand", "secret");
        SessionAuthentifiee::seConnecter($monUtilisateur);
        echo "<h4>2-1 Test état session</h4>";
        if (SessionAuthentifiee::estConnecte()){
            echo "Test Ok<br/>";
            echo SessionAuthentifiee::etat();
        }else{
            echo "Pb, la session devrait être ouverte";
        }
        echo "<h4>2-2 Test accesseurs session</h4>";
        /* @var  $unUtilisateur Utilisateur*/
        $unUtilisateur= SessionAuthentifiee::getUtilisateur();
        echo "getUtilisateur : ".$unUtilisateur."<br/>";
        echo "getIp : ".SessionAuthentifiee::getIp();
        
        echo "<h3>3- Test session déconnectée</h3>";
        SessionAuthentifiee::seDeconnecter();
        if (SessionAuthentifiee::estConnecte()){
            echo "Pb, la session devrait être fermée";
        }else{
            echo "Test OK";
        }
          
        ?>
    </body>
</html>
