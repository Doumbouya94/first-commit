<?php

    // prendre valeurs POST (salaire, heures_supp, absences)    
    

    // variables & constantes : 
    // salaire de base (float [dollars])
    // heures_supp (number [heures])
    // absences (number[jours])
    //


    // condition qui ajoute 10% salaire de base pour chaque heure supp 
    // Ex -> salaire_base +=  




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
        Salaire : <input type="number" name="salaire_base" step="0.01" placeholder="0.00" required> <br><br>
        Heures supplémentaires : <input type="number" name="heures_supp" required><br><br>
        Absences : <input type="number" name="absences" required>
    </form>
</body>
</html>