<?php
require_once 'functions.php';

// Traitement du formulaire d'emprunt
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $livre_id = $_POST['livre_id'];
    $date_emprunt = $_POST['date_emprunt'];
    $date_retour = $_POST['date_retour_prevue'];
    
    if (emprunterLivre($livre_id, $date_emprunt, $date_retour)) {
        $message = "Livre emprunté avec succès";
    } else {
        $message = "Erreur lors de l'emprunt";
    }
}

if (isset($_POST['recherche'])) {
    $resultats = rechercherLivre($_POST['recherche']);
}

// Récupéreration de la liste des livres disponibles
$livres = getLivresDisponibles();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php if (isset($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- Formulaire d'emprunt -->
    <form method="POST">
        <select name="livre_id" required>
            <?php foreach($livres as $livre): ?>
                <option value="<?php echo $livre->id; ?>">
                    <?php echo $livre->titre; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="date_emprunt" required>
        <input type="date" name="date_retour_prevue" required>
        <button type="submit">Emprunter</button>
    </form>

    <!-- Formulaire de recherche -->
    <form method="GET" class="search-form">
        <input type="text" name="recherche" placeholder="Rechercher un livre...">
        <button type="submit">Rechercher</button>
    </form>
</body>
</html>