<?php
// préparation des constantes de connexion à mysql
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_MDP", "");
define("DB_NAME", "prepatestjuin");

// constant contenant la racine du site
define("CHEMIN_RACINE", "http://localhost/php/prepa-test-juin/");

// nom des dossiers de destination des images (chemin relatif)
$dossier_ori = "images/originales/"; // dossier de l'image originale
$dossier_gd = "images/affichees/"; // dossier de l'image pour affichage
$dossier_mini = "images/miniatures/"; // dossier des miniatures

// taille des images d'affichage proportionnelle en pixels
$grande_large = 850; // taille maximale en largeur
$grande_haute = 700; // taille maximale en hauteur

// taille des miniatures coupées et centrées en pixels
$mini_large = 100; // taille maximale en largeur
$mini_haute = 120; // taille maximale en hauteur

// qualité de l'image d'affichage (jpg de 0 à 100)
$grande_qualite = 80;

// qualité de l'image de la miniature (jpg de 0 à 100)
$mini_qualite = 60;

// formats acceptés en minuscule dans un tableau, séparé par des ','
$formats_acceptes = array('jpg','jpeg','png');