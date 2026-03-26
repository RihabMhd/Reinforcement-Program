-- EXERCICE 1 - Reporting operationnel (utiliser schema.sql)
--
-- Toutes les requetes doivent etre ecrites proprement avec des alias explicites.
-- Source : base restauration (voir schema.sql)
--
-- 1. Chiffre d'affaires total par restaurant, avec le nom de la ville.
--    Trier par CA decroissant.
--
-- 2. Masse salariale par restaurant, avec le ratio (masse salariale / CA).
--    N'afficher que les restaurants ou ce ratio depasse 0.35 (35%).
--
-- 3. Pour chaque categorie de menu, afficher :
--    - Le nombre de plats
--    - Le prix moyen, min et max
--    - Le nombre de commandes contenant au moins un plat de cette categorie
--
-- 4. Clients VIP : clients qui ont commande dans au moins 2 restaurants differents
--    ET dont le montant total de commandes > 200 DH.
--    Afficher : nom, email, nb_restaurants, total_depense.
--
-- 5. Pour chaque mois de 2024, afficher :
--    - Nombre de commandes
--    - CA total
--    - Ticket moyen
--    - Trier chronologiquement

-- Ecrivez vos requetes ci-dessous

-- 1. CA par restaurant avec ville
SELECT r.nom AS restaurant, v.nom AS ville, SUM(c.montant_total) AS ca_total
FROM restaurants r
JOIN villes v ON r.ville_id = v.id
JOIN commandes c ON r.id = c.restaurant_id
GROUP BY r.id, r.nom, v.nom
ORDER BY ca_total DESC;

-- 2. Masse salariale vs CA
SELECT r.nom, s.paie, rev.ca, (s.paie / rev.ca) AS ratio
FROM restaurants r
JOIN (SELECT restaurant_id, SUM(salaire) AS paie FROM employes GROUP BY restaurant_id) s ON r.id = s.restaurant_id
JOIN (SELECT restaurant_id, SUM(montant_total) AS ca FROM commandes GROUP BY restaurant_id) rev ON r.id = rev.restaurant_id
WHERE (s.paie / rev.ca) > 0.35;
-- 3. Stats par categorie de menu
SELECT 
    m.categorie, 
    COUNT(m.id) AS nb_plats, 
    AVG(m.prix) AS prix_moyen,
    MIN(m.prix) AS prix_min, 
    MAX(m.prix) AS prix_max,
    COUNT(DISTINCT lc.commande_id) AS nb_commandes
FROM menus m
LEFT JOIN lignes_commande lc ON m.id = lc.menu_id
GROUP BY m.categorie;

-- 4. Clients VIP
SELECT cl.nom, cl.email, COUNT(DISTINCT c.restaurant_id) AS nb_restos, SUM(c.montant_total) AS total_depense
FROM clients cl
JOIN commandes c ON cl.id = c.client_id
GROUP BY cl.id, cl.nom, cl.email
HAVING COUNT(DISTINCT c.restaurant_id) >= 2 AND SUM(c.montant_total) > 200;

-- 5. Rapport mensuel 2024
SELECT 
    strftime('%m', date_commande) AS mois, 
    COUNT(id) AS nb_commandes, 
    SUM(montant_total) AS ca_mensuel,
    AVG(montant_total) AS ticket_moyen
FROM commandes
WHERE date_commande BETWEEN '2024-01-01' AND '2024-12-31'
GROUP BY mois
ORDER BY mois ASC;