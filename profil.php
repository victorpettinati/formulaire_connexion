<?php
session_start(); // Démarre la session

$bdd = new PDO('mysql:host=localhost; dbname=moduleconnexion', 'root', 'Laplateforme.06!');

if (!isset($_SESSION['login'])) {
    // Redirige l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Récupère les informations de l'utilisateur connecté depuis la base de données
$req = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ?");
$req->execute(array($_SESSION['login']));
$user = $req->fetch();

if (isset($_POST['formprofil'])) {
    if (!empty($_POST['login']) && !empty($_POST['prenom']) && !empty($_POST['nom'])) {
        $login = htmlspecialchars($_POST['login']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $nom = htmlspecialchars($_POST['nom']);

        // Vérifie si le nouveau login existe déjà dans la base de données
        $req_login_exist = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ? AND id != ?");
        $req_login_exist->execute(array($login, $user['id']));
        $login_exist = $req_login_exist->fetch();

        if ($login_exist) {
            // Le nouveau login existe déjà
            $erreur = "Le login existe déjà.";
        } else {
            $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'];
            $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

            if ($nouveau_mot_de_passe === $confirmer_mot_de_passe) {
                // Met à jour les informations de l'utilisateur dans la base de données
                $update = $bdd->prepare("UPDATE utilisateurs SET login = ?, prenom = ?, nom = ? WHERE id = ?");
                $update->execute(array($login, $prenom, $nom, $user['id']));

                // Met à jour le mot de passe si un nouveau mot de passe est fourni
                if (!empty($nouveau_mot_de_passe)) {
                    $nouveau_mot_de_passe_hash = sha1($nouveau_mot_de_passe);
                    $update_password = $bdd->prepare("UPDATE utilisateurs SET password = ? WHERE id = ?");
                    $update_password->execute(array($nouveau_mot_de_passe_hash, $user['id']));
                }

                // Met à jour les informations dans la session
                $_SESSION['login'] = $login;
                $_SESSION['prenom'] = $prenom;

                $message = "Profil mis à jour avec succès.";
            } else {
                $erreur = "Les nouveaux mots de passe ne correspondent pas.";
            }
        }
    } else {
        $erreur = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Profil</title>
</head>
<body>
    <h1>Profil</h1>
                                                            <?php if (isset($message)) { ?>
                                                                <p><?php echo $message; ?></p>
                                                            <?php } ?>
                                                            <?php if (isset($erreur)) { ?>
                                                                <p><?php echo $erreur; ?></p>
                                                            <?php } ?>
    <div class="container">                                                                 
        <div class="text-frame">
            <form method="POST" action="">
                <table>
                    <tr>
                        <td>
                            <label for="login">Login :</label>
                        </td>
                        <td>
                            <input type="text" id="login" name="login" value="<?php echo $user['login']; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="prenom">Prénom :</label>
                        </td>
                        <td>
                            <input type="text" id="prenom" name="prenom" value="<?php echo $user['prenom']; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nom">Nom :</label>
                        </td>
                        <td>
                            <input type="text" id="nom" name="nom" value="<?php echo $user['nom']; ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
                        </td>
                        <td>
                            <input type="password" placeholder="Nouveau mot de passe" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="confirmer_mot_de_passe">Confirmer le nouveau mot de passe :</label>
                        </td>
                        <td>
                            <input type="password" placeholder="Confirmer le nouveau mot de passe" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe">
                        </td>
                    </tr>
                        <td>
                            <input type="submit" name="formprofil" value="Modifier"><a href="deconnexion.php">Déconnexion</a>
                        </td>
                </table>
            </form>
        </div>
    </div>
</body>
</html>


