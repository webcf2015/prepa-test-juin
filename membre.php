<?php
session_start();

require_once 'config.php';
require_once 'connect.php';
require_once 'fonctions.php';

// si on est pas (ou plus) connecté
if (!isset($_SESSION['sid']) || $_SESSION['sid'] != session_id()) {
    header("location: deconnect.php");
}

// si on a envoyé le formulaire et qu'un fichier est bien attaché
if(isset($_POST['letitre'])&&isset($_FILES['lefichier'])){
    
    // traitement des chaines de caractères
    $letitre = traite_chaine($_POST['letitre']);
    $ladesc = traite_chaine($_POST['ladesc']);
    
    // récupération des paramètres du fichier uploadé
    $limage = $_FILES['lefichier'];

    // appel de la fonction d'envoi de l'image, le résultat de la fonction est mise dans la variable $upload
    $upload = upload_originales($limage,$dossier_ori,$formats_acceptes);
    
    // si $upload n'est pas un tableau c'est qu'on a une erreur
    if(!is_array($upload)){
        // on affiche l'erreur
        echo $upload;
        
    // si on a pas d'erreur, on va insérer dans la db et créer la miniature et grande image   
    }else{
        //var_dump($upload);
        // création de la grande image qui garde les proportions
        $gd_ok = creation_img($dossier_ori, $upload['nom'],$upload['extension'],$dossier_gd,$grande_large,$grande_haute,$grande_qualite);
        
        // création de la miniature centrée et coupée
        $min_ok = creation_img($dossier_ori, $upload['nom'],$upload['extension'],$dossier_mini,$mini_large,$mini_haute,$mini_qualite,false);
        
        // si la création des 2 images sont effectuées
        if($gd_ok==true && $min_ok==true){
            //var_dump($_POST);
            // préparation de la requête (on utilise un tableau venant de la fonction upload_originales, de champs de formulaires POST traités et d'une variable de session comme valeurs d'entrée)
            $sql= "INSERT INTO photo (lenom,letype,lepoids,lahauteur,lalargeur,letitre,ladesc,utilisateur_id) 
	VALUES ('".$upload['nom']."','".$upload['extension']."',".$upload['poids'].",".$upload['hauteur'].",".$upload['largeur'].",'$letitre','$ladesc',".$_SESSION['id'].");";
            
            mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
            
            // récupération de la dernière id insérée par la requête qui précède (dans photo par l'utilisateur actuel)
            $id_photo = mysqli_insert_id($mysqli);
            
            // vérification de l'existence des sections cochées dans le formulaire
            if(isset($_POST['section'])){
            foreach($_POST['section'] AS $clef => $valeur){
                if(ctype_digit($valeur)){
                    mysqli_query($mysqli,"INSERT INTO photo_has_rubrique VALUES ($id_photo,$valeur);")or die(mysqli_error($mysqli));
                }
            }
            }
            
        }else{
            echo 'Erreur lors de la création des images redimenssionnées';
        }
        
    }    
}



// si on confirme la suppression
if(isset($_GET['delete'])&& ctype_digit($_GET['delete'])){
    $idphoto = $_GET['delete'];
    $idutil = $_SESSION['id'];
    
    // récupération du nom de la photo
    $sql1="SELECT lenom, letype FROM photo WHERE id=$idphoto;";
    $nom_photo = mysqli_fetch_assoc(mysqli_query($mysqli,$sql1));
    
    // supression dans la table photo_has_rubrique (sans l'utilisation de la clef étrangère)
    $sql2="DELETE FROM photo_has_rubrique WHERE photo_id = $idphoto";
    mysqli_query($mysqli,$sql2);
    
    // puis suppression dans la table photo
    $sql3="DELETE FROM photo WHERE id = $idphoto AND utilisateur_id = $idutil;";
    mysqli_query($mysqli,$sql3);
    echo $dossier_ori.$nom_photo['lenom'].".".$nom_photo['letype'];
    
    // supression physique des fichiers
    unlink($dossier_ori.$nom_photo['lenom'].".".$nom_photo['letype']);
    unlink($dossier_gd.$nom_photo['lenom'].".jpg");
    unlink($dossier_mini.$nom_photo['lenom'].".jpg");
}





// récupérations des images de l'utilisateur connecté dans la table photo avec leurs sections même si il n'y a pas de sections sélectionnées (jointure externe avec LEFT)
$sql = "SELECT p.*, GROUP_CONCAT(r.id) AS idrub, GROUP_CONCAT(r.lintitle SEPARATOR '|||' ) AS lintitule
    FROM photo p
	LEFT JOIN photo_has_rubrique h ON h.photo_id = p.id
    LEFT JOIN rubrique r ON h.rubrique_id = r.id
        WHERE p.utilisateur_id = ".$_SESSION['id']."
        GROUP BY p.id
        ORDER BY p.id DESC;
    ";
$recup_sql = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

// récupération de toutes les rubriques pour le formulaire d'insertion
$sql="SELECT * FROM rubrique ORDER BY lintitle ASC;";
$recup_section = mysqli_query($mysqli, $sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $_SESSION['lelogin']?> - Votre Espace membre</title>
        <link rel="stylesheet" href="style.css" />
        <script src="monjs.js"></script>
    </head>
    <body>
         <div id="content">
             <div id="haut"><h1>Espace membre de <a href="./">photos.be</a></h1> 
                <div id="connect"><?php // texte d'accueil
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
                        }?></div>
            </div>
             <div id="milieu">
                 <div id="formulaire">
                <form action="membre.php" enctype="multipart/form-data" method="POST" name="onposte">
                    <input type="text" name="letitre" required /><br/>
                   <!-- <input type="hidden" name="MAX_FILE_SIZE" value="50000000" /> -->
                    <input type="file" name="lefichier" required /><br/>
                    <textarea name="ladesc"></textarea><br/>
                    
                    <input type="submit" value="Envoyer le fichier" /><br/>
                    Sections : <?php
                    // affichage des sections
                    while($ligne = mysqli_fetch_assoc($recup_section)){
                        echo $ligne['lintitle']." : <input type='checkbox' name='section[]' value='".$ligne['id']."' > - ";
                    }
                    ?>
                </form>
            </div>
                 <div id="lesphotos">
                     <?php
                     while($ligne = mysqli_fetch_assoc($recup_sql)){
                 echo "<div class='miniatures'>";
                 echo "<h4>".$ligne['letitre']."</h4>";
                 echo "<a href='".CHEMIN_RACINE.$dossier_gd.$ligne['lenom'].".jpg' target='_blank'><img src='".CHEMIN_RACINE.$dossier_mini.$ligne['lenom'].".jpg' alt='' /></a>";
                 echo "<p>".$ligne['ladesc']."<br /><br />";
                 // affichage des sections
                 $sections = explode('|||',$ligne['lintitule']);
                 //$idsections = explode(',',$ligne['idrub']);
                 foreach($sections AS $key => $valeur){
                     echo " $valeur<br/>";
                 }
                 echo"<br/><a href='modif.php?id=".$ligne['id']."'><img src='img/modifier.png' alt='modifier' /></a> <img onclick='supprime(".$ligne['id'].");' src='img/supprimer.png' alt='supprimer' />
                     </p>";
                 echo "</div>";
               }
               ?>
                 </div>
             </div>
            <div id="bas"></div>
         </div>
    </body>
</html>
