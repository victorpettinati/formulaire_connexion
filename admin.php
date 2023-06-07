<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ma page Administrateur</title>

</head>
<body>

<h1>Administrateur</h1>

<div class="container">                                                                 
        <div class="text-frame">
<?php

session_start();

$serveur = 'localhost';
$nomUtilisateur = 'root';
$motDePasse = 'Laplateforme.06!';
$moduleconnexion = 'moduleconnexion';

try {
    // Connexion à la base de données avec PDO
    $bdd = new PDO("mysql:host=$serveur;dbname=$moduleconnexion;charset=utf8", $nomUtilisateur, $motDePasse);

    // Requête SQL pour récupérer les informations de la table étudiants
    $requete = "SELECT * FROM utilisateurs";
    $resultat = $bdd->query($requete);
// Affichage du résultat dans un tableau HTML
    echo "<table>";
    echo "<table border = '1'>"; // afficher les lignes du tableau
    echo "<thead>";
    echo "<tr>";
    echo "<th>id</th>";
    echo "<th>login</th>";
    echo "<th>prenom</th>";
    echo "<th>nom</th>";
    echo "<th>password</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    while ($ligne = $resultat->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $ligne['id'] . "</td>";
        echo "<td>" . $ligne['login'] . "</td>";
        echo "<td>" . $ligne['prenom'] . "</td>";
        echo "<td>" . $ligne['nom'] . "</td>";
        echo "<td>" . $ligne['password'] . "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";

    // Fermeture de la connexion à la base de données
    $resultat->closeCursor();
    $bdd = null;
} catch (PDOException $e) {
    // En cas d'erreur, afficher le message d'erreur
    echo "Erreur : " . $e->getMessage();
}
?>
        <a href="deconnexion.php">Déconnexion</a>
</div>
</div>


</body>
</html>
