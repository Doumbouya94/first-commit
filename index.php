<?php
require_once 'config.php';
require_once 'functions.php';

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
</body>
</html>