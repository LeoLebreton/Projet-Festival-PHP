<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lieu Test</title>
    </head>
    <body>
        <?php
        use modele\metier\Lieu;
        require_once __DIR__ . '/../../includes/autoload.inc.php';
        echo "<h2>Test unitaire de la classe métier Lieu</h2>";
        $objet = new Lieu('0589L','Le Calamar','45 rue du poulet','35407' ,'Saint-Malo',300);
        var_dump($objet);
        ?>
    </body>
</html>