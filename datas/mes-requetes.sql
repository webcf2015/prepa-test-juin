/* requête de connexion avec récupération des droits (sans vérification d'activation du compte) */
SELECT  u.id, u.lemail, u.lenom, 
		d.lenom AS nom_perm, d.ladesc, d.laperm 
	FROM utilisateur u
		INNER JOIN droit d ON u.droit_id = d.id
    WHERE u.lelogin='Admin' AND u.lemdp = 'admin';