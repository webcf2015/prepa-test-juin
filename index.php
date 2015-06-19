<?php
session_start();

require_once 'config.php';
require_once 'connect.php';
require_once 'fonctions.php';

// si tentative de connexion
if (isset($_POST['lelogin'])) {
    $lelogin = traite_chaine($_POST['lelogin']);
    $lemdp = traite_chaine($_POST['lemdp']);

    // vérification de l'utilisateur dans la db
    $sql = "SELECT  u.id, u.lemail, u.lenom, 
		d.lenom AS nom_perm, d.ladesc, d.laperm 
	FROM utilisateur u
		INNER JOIN droit d ON u.droit_id = d.id
    WHERE u.lelogin='$lelogin' AND u.lemdp = '$lemdp';";
    $requete = mysqli_query($mysqli, $sql)or die(mysqli_error($mysqli));
    $recup_user = mysqli_fetch_assoc($requete);

    // vérifier si on a récupèré un utilisateur
    if (mysqli_num_rows($requete)) { // vaut true si 1 résultat (ou plus), false si 0

        // si l'utilisateur est bien connecté

        $_SESSION = $recup_user; // transformation des résultats de la requête en variable de session
        $_SESSION['sid'] = session_id(); // récupération de la clef de session
        $_SESSION['lelogin'] = $lelogin; // récupération du login (du POST après traitement)
        // var_dump($_SESSION);
        // redirection vers la page d'accueil (pour éviter les doubles connexions par F5)
        header('location: ' . CHEMIN_RACINE);
    }
}

// récupérations des images dans la table photo
$sql = "SELECT p.lenom,p.letype,p.letitre,p.ladesc, u.lelogin 
    FROM photo p
    INNER JOIN utilisateur u ON u.id = p.utilisateur_id
    ORDER BY p.id DESC; 
    ";
$recup_sql = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));        

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accueil</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
        <div id="content">
            <div id="haut"><h1>photos.be</h1> 
                <div id="connect">
                    <?php
                    // si on est pas (ou plus) connecté
                    if (!isset($_SESSION['sid']) || $_SESSION['sid'] != session_id()) {
                        ?>
                        <form action="" name="connection" method="POST">
                            <input type="text" name="lelogin" required />
                            <input type="password" name="lemdp" required />
                            <input type="submit" value="Connexion" />
                        </form>
                        <a href="mdp.php">Mot de passe oublié?</a>
                        <a href="inscription.php">Inscription</a>
                        <?php
                        // sinon on est connecté
                    }else{
                        
                        // texte d'accueil
                        echo "<h3>Bonjour ".$_SESSION['lenom'].'</h3>';
                        echo "<p>Vous êtes connecté en tant que <span title='".$_SESSION['ladesc']."'>".$_SESSION['nom_perm']."</span></p>";
                        echo "<h5><a href='deconnect.php'>Déconnexion</a></h5>";
                        
                        // liens  suivant la permission utilisateur
                        switch($_SESSION['laperm']){
                            // si on est l'admin
                            case 0 :
                               echo "<a href='admin.php'>Administrer le site</a> - <a href='membre.php'>Espace membre</a>";
                                break;
                            // si on est modérateur
                            case 1:
                                echo "<a href='modere.php'>Modérer le site</a> - <a href='membre.php'>Espace membre</a>";
                                break;
                            // si autre droit (ici simple utilisateur)
                            default :
                                echo "<a href='membre.php'>Espace membre</a>";
                        }
                    }
                    ?>
                </div>
            </div>
            <div id="milieu">
                <?php
                // affichez les miniatures de chaques photos dans la db par id Desc, avec le titre au dessus et la description en dessous, et affichage de la grande photo dans une nouvelle fenêtre lors du clic, Bonus : afficher lelogin de l'auteur de l'image
               while($ligne = mysqli_fetch_assoc($recup_sql)){
                 echo "<div class='miniatures'>";
                 echo "<h4>".$ligne['letitre']."</h4>";
                 echo "<a href='".CHEMIN_RACINE.$dossier_gd.$ligne['lenom'].".".$ligne['letype']."' target='_blank'><img src='".CHEMIN_RACINE.$dossier_mini.$ligne['lenom'].".".$ligne['letype']."' alt='' /></a>";
                 echo "<p>".$ligne['ladesc']."<br /> par <strong>".$ligne['lelogin']."</strong></p>";
                 echo "</div>";
               }                
               ?> 
            </div>
            <div id="bas"></div>
        </div>
    </body>
</html>
