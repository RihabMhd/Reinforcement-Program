-- EXERCICE 2 - Presences et alertes
-- Utiliser le schema schema-ecole.sql

-- 1. Taux d'absenteisme par apprenant (sur tous les modules confondus).
--    Afficher : nom, total_seances, absences, taux_absenteisme
--    Trier par taux_absenteisme decroissant.

-- 2. Apprenants avec plus de 20% d'absences dans au moins un module.
--    Afficher : nom, module, nb_seances, nb_absences, taux.

-- 3. Correlation presence/performance :
--    Pour chaque apprenant, calculer le taux de presence et la moyenne generale.
--    Afficher les deux colonnes. Y a-t-il une correlation visible ?

-- 4. Creer une vue `vue_bulletin` qui affiche pour chaque apprenant :
--    nom, module, note_qcm, note_projet, moyenne_module, taux_presence

-- 5. Alerte absenteisme :
--    Lister les apprenants qui ont ete absents 2 fois de suite dans un meme module.
--    (2 dates consecutives avec present = FALSE)

-- Requete 1
SELECT
    a.nom,
    COUNT(*) AS total_seances,
    SUM(CASE WHEN p.present = FALSE THEN 1 ELSE 0 END) AS absences,
    ROUND(
        SUM(CASE WHEN p.present = FALSE THEN 1 ELSE 0 END) * 100.0 / COUNT(*),
        2
    ) AS taux_absenteisme
FROM apprenants a
JOIN presences p ON p.apprenant_id = a.id
GROUP BY a.id, a.nom
ORDER BY taux_absenteisme DESC;


-- Requete 2
SELECT
    a.nom,
    m.nom AS module,
    COUNT(*) AS nb_seances,
    SUM(CASE WHEN p.present = FALSE THEN 1 ELSE 0 END) AS nb_absences,
    ROUND(
        SUM(CASE WHEN p.present = FALSE THEN 1 ELSE 0 END) * 100.0 / COUNT(*),
        2
    ) AS taux
FROM apprenants a
JOIN presences p ON p.apprenant_id = a.id
JOIN modules m ON m.id = p.module_id
GROUP BY a.id, a.nom, m.id, m.nom
HAVING taux > 20
ORDER BY taux DESC;


-- Requete 3
SELECT
    a.nom,
    ROUND(
        SUM(CASE WHEN p.present = TRUE THEN 1 ELSE 0 END) * 100.0 / COUNT(*),
        2
    ) AS taux_presence,
    ROUND(
        (
            SELECT SUM(moy.moyenne_module * mod2.coefficient) / SUM(mod2.coefficient)
            FROM (
                SELECT apprenant_id, module_id, AVG(note) AS moyenne_module
                FROM notes
                WHERE apprenant_id = a.id
                GROUP BY apprenant_id, module_id
            ) AS moy
            JOIN modules mod2 ON mod2.id = moy.module_id
        ),
        2
    ) AS moyenne_generale
FROM apprenants a
JOIN presences p ON p.apprenant_id = a.id
GROUP BY a.id, a.nom
ORDER BY taux_presence DESC;


-- Requete 4 (vue)
CREATE OR REPLACE VIEW vue_bulletin AS
SELECT
    a.nom,
    m.nom AS module,
    MAX(CASE WHEN n.type_evaluation = 'QCM' THEN n.note END) AS note_qcm,
    MAX(CASE WHEN n.type_evaluation = 'Projet' THEN n.note END) AS note_projet,
    ROUND(AVG(n.note), 2) AS moyenne_module,
    ROUND(
        SUM(CASE WHEN p.present = TRUE THEN 1 ELSE 0 END) * 100.0 / COUNT(DISTINCT p.id),
        2
    ) AS taux_presence
FROM apprenants a
JOIN notes n ON n.apprenant_id = a.id
JOIN modules m ON m.id = n.module_id
JOIN presences p ON p.apprenant_id = a.id AND p.module_id = m.id
GROUP BY a.id, a.nom, m.id, m.nom;


-- Requete 5
SELECT DISTINCT
    a.nom,
    m.nom AS module,
    p1.date_seance AS premiere_absence,
    p2.date_seance AS deuxieme_absence
FROM presences p1
JOIN presences p2
    ON p1.apprenant_id = p2.apprenant_id
    AND p1.module_id = p2.module_id
    AND p1.present = FALSE
    AND p2.present = FALSE
    AND p2.date_seance = (
        SELECT MIN(p3.date_seance)
        FROM presences p3
        WHERE p3.apprenant_id = p1.apprenant_id
          AND p3.module_id = p1.module_id
          AND p3.date_seance > p1.date_seance
    )
JOIN apprenants a ON a.id = p1.apprenant_id
JOIN modules m ON m.id = p1.module_id
ORDER BY a.nom, m.nom;