/* requête de connexion avec récupération des droits (sans vérification d'activation du compte) */
SELECT  u.id, u.lemail, u.lenom, 
		d.lenom AS nom_perm, d.ladesc, d.laperm 
	FROM utilisateur u
		INNER JOIN droit d ON u.droit_id = d.id
    WHERE u.lelogin='Admin' AND u.lemdp = 'admin';

/* Requete d'insertion d'une image dans la db */
INSERT INTO photo (lenom,letype,lepoids,lahauteur,lalargeur,letitre,ladesc,utilisateur_id) 
	VALUES ('h4qoqkffb3r0god3o71fqaqj1','jpg',762000,768,1024,'Koala','un beau koala',1);
    
/* Requete pour récupérer toutes les images avec l'utilisateur*/
SELECT p.lenom,p.letype,p.letitre,p.ladesc, u.lelogin FROM photo p
    INNER JOIN utilisateur u ON u.id = p.utilisateur_id
    ORDER BY p.id DESC;

/* Requête qui supprime une image si c'est son propriétaire qui effectue l'action*/
DELETE FROM photo WHERE id = 1 AND utilisateur_id = 1;

/* Récupération de toutes les sections*/
SELECT * FROM rubrique ORDER BY lintitle ASC;

/* récupération des photos avec leurs sections même si elles ne sont pas 
dans une section lorsque l'id utilisateur est 1 */
SELECT p.*, GROUP_CONCAT(r.id ORDER BY r.lintitle ASC) AS idrub, 
			GROUP_CONCAT(r.lintitle ORDER BY r.lintitle ASC SEPARATOR '|||' ) AS lintitule
		FROM photo p
	LEFT JOIN photo_has_rubrique h ON h.photo_id = p.id
    LEFT JOIN rubrique r ON h.rubrique_id = r.id
        WHERE p.utilisateur_id = 1
        GROUP BY p.id
        ORDER BY p.id DESC