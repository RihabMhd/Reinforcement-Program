-- EXERCICE 3 - Vues, agregations complexes et analyse temporelle (utiliser schema.sql)
--
-- 1. Creer une vue `vue_performance_restaurants` qui affiche pour chaque restaurant :
--    id, nom, ville, CA_total, nb_commandes, ticket_moyen, note_moyenne, nb_employes
--
-- 2. Creer une vue `vue_top_menus` qui affiche pour chaque menu :
--    id, nom_menu, nom_restaurant, ville, nb_commandes, quantite_totale, CA_genere
--    Trier par CA_genere decroissant.
--
-- 3. A partir de la vue performance, requeter :
--    Les restaurants dont le ticket moyen est superieur a la moyenne de la chaine.
--
-- 4. Analyse de fidelisation :
--    Pour chaque client programme fidelite, calculer l'intervalle moyen entre
--    ses commandes (en jours). Identifier les clients "actifs" (intervalle < 30 jours).
--
-- 5. Detecter les creneaux de forte affluence :
--    Compter les commandes par heure de la journee (0-23) et par type.
--    Identifier les 3 heures les plus chargees.

-- 1. Vue performance restaurants


-- 2. Vue top menus


-- 3. Restaurants au-dessus de la moyenne


-- 4. Fidelisation et intervalles


-- 5. Creneaux de forte affluence