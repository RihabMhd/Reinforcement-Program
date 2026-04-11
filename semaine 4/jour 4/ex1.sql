-- EXERCICE 1 — Fonctions utilisateur scalaires et tabulaires
--
-- Utiliser le schema gestion_stock.

DELIMITER $$

-- FONCTION 1 : fn_calculer_remise
-- Calcule la remise en pourcentage selon le montant de la commande
-- Regles :
--   - montant < 1000 DH        : 0%
--   - 1000 <= montant < 5000   : 5%
--   - 5000 <= montant < 20000  : 10%
--   - montant >= 20000         : 15%
-- Retourne le pourcentage (0, 5, 10 ou 15)

CREATE FUNCTION fn_calculer_remise(p_montant DECIMAL(10,2))
RETURNS INT
DETERMINISTIC
BEGIN
    -- Votre code ici
END$$


-- FONCTION 2 : fn_stock_statut
-- Retourne le statut du stock d un produit donne par son ID
-- Retourne :
--   'RUPTURE'    si stock_actuel = 0
--   'CRITIQUE'   si stock_actuel < stock_minimum
--   'FAIBLE'     si stock_actuel < stock_minimum * 2
--   'NORMAL'     si stock_actuel < stock_minimum * 5
--   'ABONDANT'   sinon

CREATE FUNCTION fn_stock_statut(p_produit_id INT)
RETURNS VARCHAR(20)
READS SQL DATA
BEGIN
    -- Votre code ici
END$$


-- FONCTION 3 : fn_ca_client_periode
-- Calcule le chiffre d affaires d un client sur une periode donnee
-- Prend en compte seulement les commandes de statut 'confirmee' ou 'expediee'
-- Retourne 0.00 si aucune commande sur la periode

CREATE FUNCTION fn_ca_client_periode(
    p_client_id INT,
    p_date_debut DATE,
    p_date_fin DATE
)
RETURNS DECIMAL(10,2)
READS SQL DATA
BEGIN
    -- Votre code ici
END$$

DELIMITER ;

-- Tests :
-- SELECT fn_calculer_remise(500);    => 0
-- SELECT fn_calculer_remise(3000);   => 5
-- SELECT fn_calculer_remise(18000);  => 10
-- SELECT fn_calculer_remise(25000);  => 15
-- SELECT fn_stock_statut(1);         => statut du Laptop Pro 15
-- SELECT fn_stock_statut(8);         => statut du Cable RJ45