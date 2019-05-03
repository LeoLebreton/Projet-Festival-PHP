<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Representation Test</title>
    </head>
    <body>
        <?php
        use modele\metier\Representation;
        require_once __DIR__ . '/../../includes/autoload.inc.php';
        echo "<h2>Test unitaire de la classe métier Representation</h2>";
        $objet = new Representation("repre321", "g001", "lieu1234", "2019-01-15", "20:00", "21:45");
        var_dump($objet);
        ?>
    </body>
</html>