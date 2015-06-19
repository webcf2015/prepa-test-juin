/* requête de connexion avec récupération des droits (sans vérification d'activation du compte) */
SELECT  u.id, u.lemail, u.lenom, 
		d.lenom AS nom_perm, d.ladesc, d.laperm 
	FROM utilisateur u
		INNER JOIN droit d ON u.droit_id = d.id
    WHERE u.lelogin='Admin' AND u.lemdp = 'admin';

/* Requete d'insertion d'une image dans la db */
INSERT INTO photo (lenom,letype,lepoids,lahauteur,lalargeur,letitre,ladesc,utilisateur_id) 
	VALUES ('h4qoqkffb3r0god3o71fqaqj1','jpg',762000,768,1024,'Koala','un beau koala',1);