-- EXERCICE 1 - Calculs de notes et classements scolaires
-- Utiliser le schema schema-ecole.sql

-- 1. Calculer la moyenne ponderee de chaque apprenant actif de la promotion 1.
--    (Moyenne des notes de chaque module * coefficient du module) / somme des coefficients
--    Afficher : nom, promotion, moyenne_ponderee
--    Trier par moyenne decroissante.

-- 2. Pour chaque module, afficher la note moyenne, min, max, et le taux de reussite (>= 10).
--    Trier par taux de reussite croissant.

-- 3. Identifier les apprenants en situation critique :
--    Moyenne < 10 dans au moins 2 modules.
--    Afficher : nom, liste des modules en difficulte, nombre de modules < 10.

-- 4. Classement final avec mention :
--    Calculer la moyenne finale de chaque apprenant.
--    Ajouter la colonne mention :
--    >= 16 : 'Tres Bien', >= 14 : 'Bien', >= 12 : 'Assez Bien', >= 10 : 'Passable', < 10 : 'Echec'

-- 5. Comparer les moyennes par type d'evaluation (QCM vs Projet) par module.
--    Afficher si les apprenants performent mieux en QCM ou en Projet.

-- Requete 1 : Moyennes ponderees
SELECT
    a.nom,
    p.nom AS promotion,
    ROUND(
        SUM(moyenne_module * m.coefficient) / SUM(m.coefficient),
        2
    ) AS moyenne_ponderee
FROM apprenants a
JOIN promotions p ON a.promotion_id = p.id
JOIN modules m ON TRUE
JOIN (
    SELECT apprenant_id, module_id, AVG(note) AS moyenne_module
    FROM notes
    GROUP BY apprenant_id, module_id
) AS moy ON moy.apprenant_id = a.id AND moy.module_id = m.id
WHERE a.statut = 'actif'
  AND a.promotion_id = 1
GROUP BY a.id, a.nom, p.nom
ORDER BY moyenne_ponderee DESC;


-- Requete 2 : Stats par module
SELECT
    m.nom AS module,
    ROUND(AVG(n.note), 2) AS note_moyenne,
    MIN(n.note) AS note_min,
    MAX(n.note) AS note_max,
    ROUND(
        SUM(CASE WHEN n.note >= 10 THEN 1 ELSE 0 END) * 100.0 / COUNT(*),
        2
    ) AS taux_reussite
FROM modules m
JOIN notes n ON n.module_id = m.id
GROUP BY m.id, m.nom
ORDER BY taux_reussite ASC;


-- Requete 3 : Apprenants en difficulte
SELECT
    a.nom,
    GROUP_CONCAT(m.nom ORDER BY m.nom SEPARATOR ', ') AS modules_en_difficulte,
    COUNT(*) AS nb_modules_echec
FROM apprenants a
JOIN (
    SELECT apprenant_id, module_id, AVG(note) AS moyenne_module
    FROM notes
    GROUP BY apprenant_id, module_id
    HAVING AVG(note) < 10
) AS echecs ON echecs.apprenant_id = a.id
JOIN modules m ON m.id = echecs.module_id
GROUP BY a.id, a.nom
HAVING COUNT(*) >= 2
ORDER BY nb_modules_echec DESC;


-- Requete 4 : Classement avec mention
SELECT
    a.nom,
    ROUND(
        SUM(moy.moyenne_module * m.coefficient) / SUM(m.coefficient),
        2
    ) AS moyenne_finale,
    CASE
        WHEN SUM(moy.moyenne_module * m.coefficient) / SUM(m.coefficient) >= 16 THEN 'Tres Bien'
        WHEN SUM(moy.moyenne_module * m.coefficient) / SUM(m.coefficient) >= 14 THEN 'Bien'
        WHEN SUM(moy.moyenne_module * m.coefficient) / SUM(m.coefficient) >= 12 THEN 'Assez Bien'
        WHEN SUM(moy.moyenne_module * m.coefficient) / SUM(m.coefficient) >= 10 THEN 'Passable'
        ELSE 'Echec'
    END AS mention
FROM apprenants a
JOIN modules m ON TRUE
JOIN (
    SELECT apprenant_id, module_id, AVG(note) AS moyenne_module
    FROM notes
    GROUP BY apprenant_id, module_id
) AS moy ON moy.apprenant_id = a.id AND moy.module_id = m.id
WHERE a.statut = 'actif'
GROUP BY a.id, a.nom
ORDER BY moyenne_finale DESC;


-- Requete 5 : QCM vs Projet
SELECT
    m.nom AS module,
    ROUND(AVG(CASE WHEN n.type_evaluation = 'QCM' THEN n.note END), 2) AS moyenne_qcm,
    ROUND(AVG(CASE WHEN n.type_evaluation = 'Projet' THEN n.note END), 2) AS moyenne_projet,
    CASE
        WHEN AVG(CASE WHEN n.type_evaluation = 'QCM' THEN n.note END)
           > AVG(CASE WHEN n.type_evaluation = 'Projet' THEN n.note END)
        THEN 'Meilleur en QCM'
        WHEN AVG(CASE WHEN n.type_evaluation = 'QCM' THEN n.note END)
           < AVG(CASE WHEN n.type_evaluation = 'Projet' THEN n.note END)
        THEN 'Meilleur en Projet'
        ELSE 'Egalite'
    END AS performance
FROM modules m
JOIN notes n ON n.module_id = m.id
GROUP BY m.id, m.nom
ORDER BY m.nom;