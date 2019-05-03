<?php
/**
 * Chargement dynamique des classes 
 * dont les espaces de noms respectent la PSR-0
 * sauf qu'ils ne sont pas préfixés par le "Vendor"
 * @param string $className
 */
function __autoload(string $className)
{
//    $className = ltrim($className, '\\');
    $fileName  = __DIR__.'/../';

    $fileName  .= str_replace('\\', DIRECTORY_SEPARATOR, $className).'.class.php';
    if (file_exists($fileName)) {
        require_once($fileName);
    } else {
        throw new Exception("Autoload - problème de chargement de la classe $className - Le fichier : " . $fileName . " n\'existe pas.");
    }
    
}