-- EXERCICE 3 - Procedures stockees et transactions
-- Utiliser le schema schema-ecole.sql

-- 1. Creer une procedure stockee `sp_ajouter_note`
--    Parametres : p_apprenant_id, p_module_id, p_type, p_note, p_date
--    - Verifier que l'apprenant existe et est actif
--    - Verifier que p_note est entre 0 et 20
--    - Verifier que le module existe
--    - Inserer la note si tout est OK
--    - Retourner un message de succes ou d'erreur

-- 2. Creer une procedure `sp_bilan_apprenant`
--    Parametre : p_apprenant_id
--    Retourner un bilan complet :
--    - Ligne par module : note_moyenne, taux_presence, statut_module (admis/echec)
--    - Moyenne generale ponderee
--    - Statut global (admis / rattrapage / echec)
--    - Rattrapage si 8 <= moyenne < 10

-- 3. Creer une transaction qui effectue un "passage en annee superieure" :
--    - Verifier que tous les apprenants de la promotion 1 ont leurs notes
--    - Calculer les moyennes
--    - Mettre a jour le statut des apprenants (admis / redoublant)
--    - Si une erreur survient, ROLLBACK
--    - Consigner dans une table `journal_promotions` (a creer)

-- Procedure 1
DELIMITER $$

CREATE PROCEDURE sp_ajouter_note(
    IN p_apprenant_id INT,
    IN p_module_id INT,
    IN p_type VARCHAR(50),
    IN p_note DECIMAL(5,2),
    IN p_date DATE,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_apprenant_existe INT DEFAULT 0;
    DECLARE v_module_existe INT DEFAULT 0;

    -- Verifier que l'apprenant existe et est actif
    SELECT COUNT(*) INTO v_apprenant_existe
    FROM apprenants
    WHERE id = p_apprenant_id AND statut = 'actif';

    IF v_apprenant_existe = 0 THEN
        SET p_message = 'Erreur : apprenant inexistant ou inactif.';
    ELSE
        -- Verifier que la note est entre 0 et 20
        IF p_note < 0 OR p_note > 20 THEN
            SET p_message = 'Erreur : la note doit etre comprise entre 0 et 20.';
        ELSE
            -- Verifier que le module existe
            SELECT COUNT(*) INTO v_module_existe
            FROM modules
            WHERE id = p_module_id;

            IF v_module_existe = 0 THEN
                SET p_message = 'Erreur : module inexistant.';
            ELSE
                -- Inserer la note
                INSERT INTO notes (apprenant_id, module_id, type_evaluation, note, date_eval)
                VALUES (p_apprenant_id, p_module_id, p_type, p_note, p_date);

                SET p_message = 'Succes : la note a ete ajoutee.';
            END IF;
        END IF;
    END IF;
END$$

DELIMITER ;


-- Procedure 2
DELIMITER $$

CREATE PROCEDURE sp_bilan_apprenant(
    IN p_apprenant_id INT
)
BEGIN
    DECLARE v_moyenne_generale DECIMAL(5,2);
    DECLARE v_statut_global VARCHAR(20);

    -- Bilan par module
    SELECT
        m.nom AS module,
        ROUND(AVG(n.note), 2) AS note_moyenne,
        ROUND(
            SUM(CASE WHEN p.present = TRUE THEN 1 ELSE 0 END) * 100.0 / COUNT(DISTINCT p.id),
            2
        ) AS taux_presence,
        CASE
            WHEN AVG(n.note) >= 10 THEN 'Admis'
            ELSE 'Echec'
        END AS statut_module
    FROM modules m
    JOIN notes n ON n.module_id = m.id AND n.apprenant_id = p_apprenant_id
    JOIN presences p ON p.module_id = m.id AND p.apprenant_id = p_apprenant_id
    GROUP BY m.id, m.nom;

    -- Calculer la moyenne generale ponderee
    SELECT
        ROUND(SUM(moy.moyenne_module * m.coefficient) / SUM(m.coefficient), 2)
    INTO v_moyenne_generale
    FROM (
        SELECT module_id, AVG(note) AS moyenne_module
        FROM notes
        WHERE apprenant_id = p_apprenant_id
        GROUP BY module_id
    ) AS moy
    JOIN modules m ON m.id = moy.module_id;

    -- Determiner le statut global
    SET v_statut_global = CASE
        WHEN v_moyenne_generale >= 10 THEN 'Admis'
        WHEN v_moyenne_generale >= 8  THEN 'Rattrapage'
        ELSE 'Echec'
    END;

    -- Afficher le resume global
    SELECT
        v_moyenne_generale AS moyenne_generale_ponderee,
        v_statut_global AS statut_global;
END$$

DELIMITER ;


-- Transaction 3

-- Creation de la table journal_promotions
CREATE TABLE IF NOT EXISTS journal_promotions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    promotion_id INT,
    date_traitement DATETIME,
    nb_admis INT,
    nb_redoublants INT,
    commentaire VARCHAR(255)
);

-- Transaction de passage en annee superieure
START TRANSACTION;

BEGIN
    DECLARE v_nb_apprenants INT DEFAULT 0;
    DECLARE v_nb_avec_notes INT DEFAULT 0;
    DECLARE v_nb_admis INT DEFAULT 0;
    DECLARE v_nb_redoublants INT DEFAULT 0;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SELECT 'Erreur : transaction annulee (ROLLBACK effectue).' AS message;
    END;

    -- Compter les apprenants actifs de la promotion 1
    SELECT COUNT(*) INTO v_nb_apprenants
    FROM apprenants
    WHERE promotion_id = 1 AND statut = 'actif';

    -- Compter ceux qui ont au moins une note
    SELECT COUNT(DISTINCT apprenant_id) INTO v_nb_avec_notes
    FROM notes
    WHERE apprenant_id IN (
        SELECT id FROM apprenants WHERE promotion_id = 1 AND statut = 'actif'
    );

    -- Verifier que tous les apprenants ont leurs notes
    IF v_nb_apprenants != v_nb_avec_notes THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : certains apprenants n ont pas encore de notes.';
    END IF;

    -- Mettre a jour le statut selon la moyenne ponderee
    UPDATE apprenants a
    JOIN (
        SELECT
            n.apprenant_id,
            SUM(AVG(n.note) * m.coefficient) / SUM(m.coefficient) AS moyenne_ponderee
        FROM notes n
        JOIN modules m ON m.id = n.module_id
        WHERE n.apprenant_id IN (
            SELECT id FROM apprenants WHERE promotion_id = 1 AND statut = 'actif'
        )
        GROUP BY n.apprenant_id
    ) AS moyennes ON moyennes.apprenant_id = a.id
    SET a.statut = CASE
        WHEN moyennes.moyenne_ponderee >= 10 THEN 'admis'
        ELSE 'redoublant'
    END
    WHERE a.promotion_id = 1 AND a.statut = 'actif';

    -- Compter les admis et redoublants
    SELECT COUNT(*) INTO v_nb_admis
    FROM apprenants WHERE promotion_id = 1 AND statut = 'admis';

    SELECT COUNT(*) INTO v_nb_redoublants
    FROM apprenants WHERE promotion_id = 1 AND statut = 'redoublant';

    -- Consigner dans le journal
    INSERT INTO journal_promotions (promotion_id, date_traitement, nb_admis, nb_redoublants, commentaire)
    VALUES (1, NOW(), v_nb_admis, v_nb_redoublants, 'Passage en annee superieure promotion 1');

    COMMIT;
    SELECT 'Succes : passage en annee superieure effectue.' AS message;
END;