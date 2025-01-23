<?php
require_once 'config.php';

function getLivresDisponibles() {
    global $pdo;
    return $pdo->query("SELECT * FROM livres WHERE status = 'DISPONIBLE'");
}

function rechercherLivre($titre) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT * FROM livres WHERE titre LIKE :titre");
    $searchTerm = "%".$titre."%";
    $stmt->bindParam(':titre', $searchTerm);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function emprunterLivre($livre_id, $date_emprunt, $date_retour) {
    global $pdo;
    try {
        $pdo->beginTransaction();
        
        // Marquer le livre comme non disponible
        $stmt = $pdo->prepare("UPDATE livres SET disponible = 0 WHERE id = ?");
        $stmt->execute([$livre_id]);
        
        // Créer l'emprunt
        $stmt = $pdo->prepare("INSERT INTO emprunts (livre_id, date_emprunt, date_retour_prevue) VALUES (?, ?, ?)");
        $stmt->execute([$livre_id, $date_emprunt, $date_retour]);
        
        $pdo->commit();
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function calculerFraisRetard($emprunt_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT date_retour_prevue, date_retour_reelle FROM emprunts WHERE id = ?");
    $stmt->execute([$emprunt_id]);
    $emprunt = $stmt->fetch(PDO::FETCH_OBJ);
    
    if ($emprunt->date_retour_reelle) {
        return calculerFraisRetard($emprunt->date_retour_prevue, $emprunt->date_retour_reelle);
    }
    return 0;
}