-- EXERCICE 3 — Triggers et coherence des donnees
--
-- Objectif : utiliser des triggers pour automatiser des verifications
-- et maintenir la coherence sans intervention manuelle.

DELIMITER $$

-- TRIGGER 1 : before_update_stock
-- Se declenche AVANT chaque UPDATE sur la table produits
-- Verifie que stock_actuel ne passe jamais en dessous de 0
-- Si tentative de stock negatif => SIGNAL SQLSTATE '45000' avec message explicite

CREATE TRIGGER before_update_stock
BEFORE UPDATE ON produits
FOR EACH ROW
BEGIN
    -- Votre code ici
END$$


-- TRIGGER 2 : after_insert_ligne_commande
-- Se declenche APRES chaque INSERT dans lignes_commande
-- Met automatiquement a jour le montant_total de la commande correspondante
-- (recalculer la somme de toutes les lignes de la commande)

CREATE TRIGGER after_insert_ligne_commande
AFTER INSERT ON lignes_commande
FOR EACH ROW
BEGIN
    -- Votre code ici
END$$


-- TRIGGER 3 : after_update_stock_alerte
-- Se declenche APRES chaque UPDATE sur produits.stock_actuel
-- Si le nouveau stock < stock_minimum, inserer une alerte dans une table stock_alertes
-- La table stock_alertes doit etre creee : id, produit_id, stock_actuel, stock_minimum, date_alerte

CREATE TABLE IF NOT EXISTS stock_alertes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    produit_id INT NOT NULL,
    stock_actuel INT NOT NULL,
    stock_minimum INT NOT NULL,
    date_alerte DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produit_id) REFERENCES produits(id)
);

CREATE TRIGGER after_update_stock_alerte
AFTER UPDATE ON produits
FOR EACH ROW
BEGIN
    -- Votre code ici
END$$

DELIMITER ;

-- Tests a realiser :
-- 1. Tenter d UPDATE produits SET stock_actuel = -5 WHERE id = 1 => verifier l erreur
-- 2. Inserer une ligne_commande et verifier que montant_total se met a jour
-- 3. Reduire le stock d un produit sous son minimum => verifier l entree dans stock_alertes