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
            $_SESSION['login'] = $user['login'];
            $_SESSION['prenom'] = $user['prenom'];
            header("Location: profil.php");
            exit();
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
    <title>Page de Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <?php if (isset($erreur)) { ?>
        <p><?php echo $erreur; ?></p>
    <?php } ?>
    <form method="POST" action="">
        <table>
            <tr>
                <td>
                    <label for="login">Login :</label>
                </td>
                <td>
                    <input type="text" id="login" name="login" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="mot_de_passe">Mot de passe :</label>
                </td>
                <td>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </td>
            </tr>
            <td>
                    <input type="submit" name="formconnexion" value="Se connecter">
            </td>
        </table>
    </form>
</body>
</html>
