-- EXERCICE 2 — Index et optimisation de requetes
--
-- Objectif : comprendre l impact des index sur les performances
-- et savoir choisir les colonnes a indexer.

-- Partie 1 : Analyse sans index
-- Executer EXPLAIN sur les requetes ci-dessous et noter le type (ALL, REF, etc.)

EXPLAIN
SELECT p.nom, p.stock_actuel, c.nom AS categorie
FROM produits p
INNER JOIN categories c ON c.id = p.categorie_id
WHERE p.prix_unitaire BETWEEN 100 AND 3000
  AND p.actif = 1
ORDER BY p.prix_unitaire ASC;

EXPLAIN
SELECT cl.nom, COUNT(co.id) AS nb_commandes, SUM(co.montant_total) AS ca_total
FROM clients cl
LEFT JOIN commandes co ON co.client_id = cl.id
WHERE co.date_commande >= '2024-01-01'
  AND co.statut = 'confirmee'
GROUP BY cl.id
ORDER BY ca_total DESC;

-- Partie 2 : Creer les index appropries
-- Pour chaque requete ci-dessus, creer le ou les index qui ameliorent les performances.
-- Justifier votre choix dans un commentaire (quelle colonne, pourquoi ce type d index).

-- Index 1 : (a creer + justification)
-- ...

-- Index 2 : (a creer + justification)
-- ...

-- Index 3 : (a creer + justification)
-- ...

-- Partie 3 : Apres creation des index
-- Relancer les EXPLAIN et verifier que le type est passe de ALL a REF ou RANGE.
-- Documenter l amelioration observee dans un commentaire.

-- Partie 4 : Index composites
-- Creer un index composite sur (client_id, statut, date_commande) dans commandes.
-- Ecrire une requete qui beneficie specifiquement de cet index composite.
-- Expliquer dans un commentaire pourquoi l ordre des colonnes dans l index est important.