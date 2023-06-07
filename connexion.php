<?php
session_start();

$bdd = new PDO('mysql:host=localhost; dbname=moduleconnexion', 'root', 'Laplateforme.06!');

if (isset($_POST['formconnexion'])) {
    if (!empty($_POST['login']) && !empty($_POST['mot_de_passe'])) {
        $login = htmlspecialchars($_POST['login']);
        $password = sha1($_POST['mot_de_passe']);

        $req = $bdd->prepare("SELECT * FROM utilisateurs WHERE login = ? AND password = ?");
        $req->execute(array($login, $password));
        $user = $req->fetch();

        if ($user) {
            if ($login === 'admin' && $password === sha1('admin')) {
                header("Location: admin.php");
                exit();
            } else {
                $_SESSION['login'] = $user['login'];
                $_SESSION['prenom'] = $user['prenom'];
                header("Location: profil.php");
                exit();
            }
        } else {
            $erreur = "Identifiant ou mot de passe incorrect.";
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
    <title>Page de Connexion</title>
</head>
<body>
    <h1><a href="index.php">Connexion</a></h1>
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
                                    <input type="text" placeholder="Login" id="login" name="login" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="mot_de_passe">Mot de passe :</label>
                                </td>
                                <td>
                                    <input type="password" placeholder="Mot de passe" id="mot_de_passe" name="mot_de_passe" required>
                                </td>
                            </tr>
                            <td>
                                    <input type="submit" name="formconnexion" value="Se connecter"><a href="inscription.php">S'inscrire</a>
                                </td>
                </table>
            </form>
        </div>
    </div>
</body>
</html>
