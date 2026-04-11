-- EXERCICE 2 — Transactions avec ROLLBACK automatique
--
-- Objectif : ecrire des transactions qui garantissent la coherence des donnees.
-- Si une operation echoue a mi-chemin, tout doit etre annule.

DELIMITER $$

-- PROCEDURE : sp_passer_commande
-- But : creer une commande complete avec toutes ses lignes de maniere atomique
--
-- Parametres :
--   IN p_client_id INT
--   IN p_produits JSON
--     Format attendu : JSON array d objets
--     [{"produit_id": 1, "quantite": 2}, {"produit_id": 3, "quantite": 1}]
--   OUT p_commande_id INT
--   OUT p_montant_total DECIMAL(10,2)
--   OUT p_message VARCHAR(300)
--
-- Logique (TOUT dans une transaction) :
--   1. Verifier que le client existe
--   2. Pour chaque produit dans le JSON :
--      a. Verifier que le produit existe et est actif
--      b. Verifier que le stock est suffisant (stock_actuel >= quantite demandee)
--         => Si stock insuffisant : ROLLBACK + message d erreur explicite avec le nom du produit
--      c. Calculer le sous-total
--   3. Creer la commande (statut 'confirmee')
--   4. Pour chaque produit :
--      a. Inserer la ligne de commande avec le prix unitaire actuel
--      b. Decremente le stock
--      c. Inserer un mouvement 'sortie' dans mouvements_stock
--   5. Mettre a jour le montant_total de la commande
--   6. COMMIT + retourner commande_id, montant_total et message de confirmation
--
-- Note : utiliser JSON_TABLE ou une boucle WHILE selon votre version MySQL
--
-- Test 1 : commander 2 Laptop Pro 15 + 5 Souris ergonomiques pour client 1
-- Test 2 : tenter de commander 100 Ecrans (stock insuffisant) => verifier le ROLLBACK

CREATE PROCEDURE sp_passer_commande(/* parametres ici */)
BEGIN
    -- Votre code ici
END$$

DELIMITER ;