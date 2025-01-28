<?php
// Informations de connexion à la base de données
define('DB_HOST', 'localhost');     // L'hôte de base de données
define('DB_NAME', 'bibliotheque');  // Le nom de la base de données 
define('DB_USER', 'root');         // Le nom d'utilisateur MySQL (par défaut 'root' avec XAMPP)
define('DB_PASS', '');             // Le mot de passe MySQL (vide par défaut avec XAMPP)

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    $pdo->exec("SET CHARACTER SET utf8");
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>