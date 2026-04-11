-- EXERCICE 1 — Procedures stockees avec validation et gestion d erreurs
--
-- Utiliser le schema gestion_stock (schema-gestion-stock.sql)
--
-- Objectif : ecrire des procedures robustes avec validation des entrees,
-- gestion d erreurs et messages d information.

DELIMITER $$

-- PROCEDURE 1 : sp_approvisionner_produit
-- But : augmenter le stock d un produit suite a une livraison fournisseur
--
-- Parametres :
--   IN p_reference VARCHAR(20)      -- reference du produit
--   IN p_quantite INT               -- quantite a ajouter (doit etre > 0)
--   IN p_motif VARCHAR(200)         -- motif du mouvement (ex: "Livraison fournisseur #45")
--   OUT p_nouveau_stock INT         -- nouveau stock apres ajout
--   OUT p_message VARCHAR(200)      -- message de confirmation ou d erreur
--
-- Logique :
--   1. Verifier que le produit existe et est actif => sinon message d erreur, sortir
--   2. Verifier que la quantite est > 0 => sinon message d erreur, sortir
--   3. Mettre a jour le stock du produit
--   4. Inserer un mouvement de type 'entree' dans mouvements_stock
--   5. Retourner le nouveau stock et un message de confirmation
--
-- Test : approvisionner 50 unites de 'SOU-001' avec motif "Livraison BL-2024-001"
CREATE PROCEDURE sp_approvisionner_produit(/* parametres ici */)
BEGIN
    -- Votre code ici
END$$


-- PROCEDURE 2 : sp_annuler_commande
-- But : annuler une commande existante et restaurer le stock des produits
--
-- Parametres :
--   IN p_commande_id INT
--   IN p_motif VARCHAR(200)
--   OUT p_success TINYINT(1)
--   OUT p_message VARCHAR(200)
--
-- Logique :
--   1. Verifier que la commande existe
--   2. Verifier que le statut est 'en_attente' ou 'confirmee' (pas annulable si expediee)
--   3. Pour chaque ligne de commande, restaurer le stock du produit
--   4. Inserer un mouvement 'entree' pour chaque produit restaure
--   5. Mettre le statut de la commande a 'annulee'
--   6. Retourner success=1 si tout s est passe correctement
--
-- Test : annuler une commande existante, verifier que le stock est restaure
CREATE PROCEDURE sp_annuler_commande(/* parametres ici */)
BEGIN
    -- Votre code ici
END$$

DELIMITER ;