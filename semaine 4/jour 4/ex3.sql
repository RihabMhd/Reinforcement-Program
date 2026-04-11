-- EXERCICE 3 — Vues pour le reporting et le dashboard
--
-- Creer des vues qui encapsulent des requetes complexes
-- et servent de base a un tableau de bord.

-- VUE 1 : vue_dashboard_stock
-- Colonnes : id, reference, nom, categorie, prix_unitaire, stock_actuel,
--            stock_minimum, statut_stock (utiliser fn_stock_statut),
--            valeur_stock (stock_actuel * prix_unitaire)
-- Seulement les produits actifs
-- Trier par valeur_stock decroissant

CREATE OR REPLACE VIEW vue_dashboard_stock AS
-- Votre code ici
;


-- VUE 2 : vue_performance_clients
-- Colonnes : client_id, client_nom, email,
--            nb_commandes_total, nb_commandes_confirmees,
--            ca_total (somme des montants des commandes confirmees/expediees),
--            panier_moyen, derniere_commande (date),
--            remise_applicable (fn_calculer_remise sur ca_total)
-- Inclure tous les clients (meme ceux sans commande)

CREATE OR REPLACE VIEW vue_performance_clients AS
-- Votre code ici
;


-- VUE 3 : vue_alertes_reapprovisionnement
-- Colonnes : reference, nom_produit, categorie,
--            stock_actuel, stock_minimum, ecart (stock_minimum - stock_actuel),
--            quantite_a_commander (ecart * 3 pour avoir 3x le minimum),
--            cout_reappro (quantite_a_commander * prix_unitaire)
-- Seulement les produits avec stock_actuel < stock_minimum
-- Trier par ecart decroissant

CREATE OR REPLACE VIEW vue_alertes_reapprovisionnement AS
-- Votre code ici
;


-- REQUETES SUR LES VUES (a ecrire) :
-- 1. Depuis vue_dashboard_stock : lister tous les produits en statut CRITIQUE ou RUPTURE
-- 2. Depuis vue_performance_clients : afficher le top 3 des clients par CA
-- 3. Depuis vue_alertes_reapprovisionnement : calculer le budget total de reapprovisionnement
-- 4. Jointure entre vue_dashboard_stock et vue_alertes_reapprovisionnement pour avoir
--    le cout de reapprovisionnement par categorie