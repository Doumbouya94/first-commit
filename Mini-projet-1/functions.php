<?php
require_once 'config.php';

function getLivresDisponibles() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT id, titre, auteur FROM livres WHERE status = 'DISPONIBLE'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

function verifierCarte($carte_id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT status FROM cartes WHERE id = ? AND status = 'ACTIVE'");
        $stmt->execute([$carte_id]);
        return $stmt->fetch() !== false;
    } catch(PDOException $e) {
        return false;
    }
}

function rechercherLivre($titre) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM livres WHERE titre LIKE :titre");
        $searchTerm = "%".$titre."%";
        $stmt->execute(['titre' => $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
        return [];
    }
}

function emprunterLivre($livre_id, $carte_id, $date_emprunt, $date_retour) {
    global $pdo;
    try {
        // Vérifier si le livre est disponible
        $stmt = $pdo->prepare("SELECT status FROM livres WHERE id = ?");
        $stmt->execute([$livre_id]);
        $livre = $stmt->fetch();
        
        if ($livre && $livre['status'] == 'DISPONIBLE') {
            // Créer l'emprunt
            $stmt = $pdo->prepare("INSERT INTO emprunts (livre_id, carte_id, date_emprunt, date_retour_prevue) VALUES (?, ?, ?, ?)");
            $stmt->execute([$livre_id, $carte_id, $date_emprunt, $date_retour]);
            
            // Mettre à jour le statut du livre
            $stmt = $pdo->prepare("UPDATE livres SET status = 'EMPRUNTE' WHERE id = ?");
            $stmt->execute([$livre_id]);
            
            return true;
        }
        return false;
    } catch(PDOException $e) {
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

