# Jour 05 - Mini-Projet : Gestionnaire de Produits Avance

## Contexte

Vous etes recrute comme developpeur JavaScript dans une PME de distribution.
Le directeur commercial a besoin d'un gestionnaire de catalogue produits
en ligne de commande (Node.js). Il n'a pas de budget pour une base de donnees :
toutes les donnees seront en memoire (tableau d'objets).

## Specifications fonctionnelles

### Module 1 - CRUD produits

- Ajouter un produit (validation : nom requis, prix > 0, stock >= 0)
- Modifier un produit par ID (modification partielle, sans ecraser les champs non fournis)
- Supprimer un produit par ID
- Rechercher des produits (multi-criteres : nom, categorie, fourchette de prix, stock > 0)

### Module 2 - Gestion du stock

- Approvisionner : ajouter une quantite au stock d'un produit
- Vente : deduire une quantite, refuser si stock insuffisant
- Alerte stock critique : lister les produits avec stock <= seuilCritique
- Valorisation du stock : prix * stock, avec sous-total par categorie

### Module 3 - Panier et commandes

- Ajouter un article au panier (verifier le stock)
- Retirer un article du panier
- Calculer le total : remise 5% si total >= 500 DH, 10% si >= 1000 DH, 15% si >= 2000 DH
- Valider la commande : decremente les stocks, vide le panier, genere un recapitulatif

### Module 4 - Rapports

- Top N produits par chiffre d'affaires (quantite vendue * prix)
- Repartition du stock par categorie (% du total)
- Produits jamais commandes
- Historique des commandes avec total et date

## Contraintes techniques

- Aucune librairie externe
- Aucune mutation directe des tableaux originaux (toujours retourner de nouveaux tableaux/objets)
- Chaque fonction doit etre testable independamment
- Les erreurs doivent etre gerees proprement (try/catch ou codes d'erreur explicites)

## Livrables

- `gestionnaire.js` : toutes les fonctions implementees et exportees
- `data.js` : jeu de donnees initial (minimum 15 produits, 4 categories)
- `tests.js` : demonstration de chaque fonctionnalite avec console.log

## Evaluation (20 points)

| Critere | Points |
|---------|--------|
| Module CRUD complet et fonctionnel | 5 |
| Gestion du stock correcte | 4 |
| Logique panier et remises | 4 |
| Rapports et agregations | 4 |
| Qualite du code (lisibilite, absence de mutations) | 3 |