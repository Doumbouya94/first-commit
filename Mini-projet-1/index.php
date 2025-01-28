<?php
require_once 'config.php';
require_once 'functions.php';

if (isset($_POST['action']) && $_POST['action'] == 'emprunter') {
    if (emprunterLivre(
        $_POST['livre_id'],
        $_POST['carte_id'],
        $_POST['date_emprunt'],
        $_POST['date_retour']
    )) {
        echo "Livre emprunté avec succès !";
    } else {
        echo "Erreur lors de l'emprunt du livre.";
    }
}

$resultats = [];
if (isset($_POST['recherche']) && !empty($_POST['recherche'])) {
    $resultats = rechercherLivre($_POST['recherche']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bibliothèque</title>
    <meta charset="UTF-8">
</head>
<body>
    <form method="POST">
        <input type="text" name="recherche" placeholder="Rechercher un livre...">
        <button type="submit">Rechercher</button>
    </form>

    <?php if (!empty($resultats)): ?>
        <div class="resultats">
            <?php foreach($resultats as $livre): ?>
                <div class="livre">
                    <h3><?php echo htmlspecialchars($livre['titre']); ?></h3>
                    <p>Auteur : <?php echo htmlspecialchars($livre['auteur']); ?></p>
                    <p>Status : <?php echo htmlspecialchars($livre['status']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php elseif (isset($_POST['recherche'])): ?>
        <p>Aucun livre trouvé.</p>
    <?php endif; ?>

<!-- Formulaire d'emprunt -->
<h2>Emprunter un livre</h2>
<form method="POST" action="">
    <input type="hidden" name="action" value="emprunter">
    <div>
        <label>Sélectionnez un livre :</label>
        <select name="livre_id" required>
            <?php 
            $livres = getLivresDisponibles();
            foreach($livres as $livre): 
            ?>
                <option value="<?php echo $livre['id']; ?>">
                    <?php echo $livre['titre'] . ' - ' . $livre['auteur']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label>ID de la carte :</label>
        <input type="number" name="carte_id" required>
    </div>
    <div>
        <label>Date d'emprunt :</label>
        <input type="date" name="date_emprunt" required>
    </div>
    <div>
        <label>Date de retour prévue :</label>
        <input type="date" name="date_retour" required>
    </div>
    <button type="submit">Emprunter</button>
</form>
</body>
</html>