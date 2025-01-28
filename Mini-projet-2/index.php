<?php

// raisonnement :
// prendre valeurs POST (salaire, heures_supp, absences)    


// variables & constantes : 
// salaire de base (float [dollars canadiens])
// heures_supp (number [heures])
// absences (number[jours])
// constante du montant -> define(MONTANT_LIMITE, 50000)


// condition qui ajoute 1% salaire de base pour chaque heure supp 
//  salaire_base = ((salaire_base * 0.01) * heures_supp) + salaire_base

// condition qui pénalise de 5% pour chaque jour d'absence
// salaire_base = salaire_base - ((salaire_base * 0.05) * absences)


// Définition de la constante
define('MONTANT_LIMITE', 50000);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des valeurs POST
    $salaire_base = floatval($_POST['salaire_base']);
    $heures_supp = intval($_POST['heures_supp']);
    $absences = intval($_POST['absences']);
    $annees = intval($_POST['annees']);

    // Calcul du bonus pour heures supplémentaires (1% par heure) -> // fait plus de sens logiquement
    $bonus_heures = ($salaire_base * 0.01) * $heures_supp;
    
    // Calcul des pénalités pour absences (5% par jour)
    $penalites = ($salaire_base * 0.05) * $absences;
    
    // Calcul du salaire brut initial
    $salaire_brut = $salaire_base + $bonus_heures - $penalites;
    
    // Application de l'impôt si le salaire dépasse le montant limite
    if ($salaire_brut > MONTANT_LIMITE) {
        $impot = $salaire_brut * 0.20;
        $salaire_net = $salaire_brut - $impot;
    } else {
        $salaire_net = $salaire_brut;
    }

    // Calcul de la suite arithmétique pour l'augmentation sur n années
    // Supposons une augmentation annuelle de 3%
    $augmentation_annuelle = $salaire_net * 0.03;
    $total_cumule = 0;
    $evolution_salaires = array();
    
    for ($i = 0; $i < $annees; $i++) {
        $salaire_annee = $salaire_net + ($augmentation_annuelle * $i);
        $evolution_salaires[] = $salaire_annee;
        $total_cumule += $salaire_annee;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculateur de Salaire</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 200px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .results {
            margin-top: 20px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Calculateur de Salaire</h1>
    <form method="POST">
        <div class="form-group">
            <label for="salaire_base">Salaire de base :</label>
            <input type="number" id="salaire_base" name="salaire_base" step="0.01" placeholder="0.00" required>
        </div>
        <div class="form-group">
            <label for="heures_supp">Heures supplémentaires :</label>
            <input type="number" id="heures_supp" name="heures_supp" min="0" required>
        </div>
        <div class="form-group">
            <label for="absences">Jours d'absence :</label>
            <input type="number" id="absences" name="absences" min="0" required>
        </div>
        <div class="form-group">
            <label for="annees">Nombre d'années :</label>
            <input type="number" id="annees" name="annees" min="1" required>
        </div>
        <button type="submit">Calculer</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
    <div class="results">
        <h2>Résultats</h2>
        <p>Salaire de base : <?php echo number_format($salaire_base, 2); ?> $</p>
        <p>Bonus heures supplémentaires : <?php echo number_format($bonus_heures, 2); ?> $</p>
        <p>Pénalités absences : <?php echo number_format($penalites, 2); ?> $</p>
        <p>Salaire brut : <?php echo number_format($salaire_brut, 2); ?> $</p>
        <p>Salaire net initial : <?php echo number_format($salaire_net, 2); ?> $</p>
        
        <h3>Évolution sur <?php echo $annees; ?> ans</h3>
        <?php foreach ($evolution_salaires as $index => $salaire): ?>
            <p>Année <?php echo $index + 1; ?> : <?php echo number_format($salaire, 2); ?> $</p>
        <?php endforeach; ?>
        
        <p><strong>Total cumulé sur <?php echo $annees; ?> ans : <?php echo number_format($total_cumule, 2); ?> $</strong></p>
    </div>
    <?php endif; ?>
</body>
</html>

