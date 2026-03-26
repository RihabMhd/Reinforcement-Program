-- EXERCICE 2 - Requetes avancees avec sous-requetes et analyses (utiliser schema.sql)
--
-- 1. Lister les menus qui n'ont jamais ete commandes.
--
-- 2. Pour chaque restaurant, afficher le plat le plus commande (en quantite totale).
--    En cas d'egalite, prendre le plat le plus cher.
--
-- 3. Classement des employes par salaire au sein de leur restaurant.
--    Afficher : nom_restaurant, nom_employe, poste, salaire, rang_dans_restaurant.
--    (Indice : utiliser une sous-requete correlee ou une window function si disponible)
--
-- 4. Trouver les restaurants dont la note moyenne est superieure a la note moyenne
--    de tous les restaurants de leur ville.
--
-- 5. Identifier les clients qui ont commande le meme mois dans des restaurants
--    de villes differentes.

-- 1. Menus jamais commandes
SELECT m.*
FROM menus m
LEFT JOIN lignes_commande lc ON m.id = lc.menu_id
WHERE lc.menu_id IS NULL;

-- 2. Plat le plus commande par restaurant
SELECT restaurant_nom, plat_nom, total_qty
FROM (
    SELECT 
        r.nom AS restaurant_nom, 
        m.nom AS plat_nom, 
        SUM(lc.quantite) AS total_qty,
        RANK() OVER (PARTITION BY r.id ORDER BY SUM(lc.quantite) DESC, m.prix DESC) as rang
    FROM restaurants r
    JOIN menus m ON r.id = m.restaurant_id
    JOIN lignes_commande lc ON m.id = lc.menu_id
    GROUP BY r.id, m.id
) AS stats
WHERE rang = 1;

-- 3. Classement salaires intra-restaurant
SELECT 
    r.nom AS restaurant, 
    e.nom AS employe, 
    e.poste, 
    e.salaire,
    RANK() OVER (PARTITION BY e.restaurant_id ORDER BY e.salaire DESC) AS rang_salaire
FROM employes e
JOIN restaurants r ON e.restaurant_id = r.id;

-- 4. Restaurants au-dessus de la moyenne de leur ville
SELECT r.nom, r.note_moyenne, v.nom AS ville
FROM restaurants r
JOIN villes v ON r.ville_id = v.id
WHERE r.note_moyenne > (
    SELECT AVG(note_moyenne) 
    FROM restaurants 
    WHERE ville_id = r.ville_id
);

-- 5. Clients multi-villes sur un meme mois
SELECT 
    cl.nom, 
    strftime('%m-%Y', c.date_commande) AS mois,
    COUNT(DISTINCT r.ville_id) AS nb_villes
FROM clients cl
JOIN commandes c ON cl.id = c.client_id
JOIN restaurants r ON c.restaurant_id = r.id
GROUP BY cl.id, mois
HAVING nb_villes > 1;