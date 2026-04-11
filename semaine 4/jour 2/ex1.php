<?php
/**
 * EXERCICE 1 — Interfaces et polymorphisme
 *
 * Contexte :
 * Un systeme de gestion documentaire produit des rapports dans plusieurs formats.
 * Le meme rapport doit pouvoir etre exporte en PDF, en CSV et en JSON
 * sans changer le code appelant.
 *
 * A implementer :
 *
 * Interface Exportable
 *   exporter(array $donnees, string $titre) : string
 *   getFormatLabel() : string
 *
 * Interface Loggable
 *   logAction(string $action, string $details) : void
 *   getLog() : array
 *
 * Classe ExporteurPDF implements Exportable, Loggable
 *   exporter() : simule un export PDF
 *     Retourner une chaine : "[PDF] titre
+-----+
| col1 | col2 |
+-----+
| val1 | val2 |
..."
 *     Format : entetes en majuscules, lignes de donnees separees
 *   getFormatLabel() : retourne "PDF"
 *   logAction() : enregistre dans un tableau interne avec timestamp (date('H:i:s'))
 *   getLog() : retourne le tableau de logs
 *
 * Classe ExporteurCSV implements Exportable, Loggable
 *   exporter() : retourne le titre en commentaire (#titre), puis les cles en entete, puis les valeurs
 *   getFormatLabel() : retourne "CSV"
 *   (meme logique de log)
 *
 * Classe ExporteurJSON implements Exportable, Loggable
 *   exporter() : retourne un JSON encode (JSON_PRETTY_PRINT) avec "titre" et "data" comme cles
 *   getFormatLabel() : retourne "JSON"
 *   (meme logique de log)
 *
 * Classe RapportService
 *   Proprietes : exporteurs (tableau d Exportable), rapport courant (array)
 *   Methodes :
 *     ajouterExporteur(Exportable $e) : void
 *     chargerDonnees(array $donnees) : void
 *     exporterTout(string $titre) : array
 *       => Appelle exporter() sur chaque exporteur, retourne un tableau associatif format=>resultat
 *       => Appelle logAction() si l exporteur est aussi Loggable
 *     getFormatsDisponibles() : array => ['PDF', 'CSV', 'JSON']
 *
 * Tests a realiser :
 * Donnees :
 * $ventes = [
 *   ['produit' => 'Laptop', 'quantite' => 12, 'total' => 84000],
 *   ['produit' => 'Souris', 'quantite' => 45, 'total' => 6750],
 *   ['produit' => 'Clavier', 'quantite' => 30, 'total' => 9000],
 * ];
 *
 * 1. Creer un RapportService avec les 3 exporteurs
 * 2. Charger $ventes et exporter avec le titre "Rapport Ventes Q1"
 * 3. Afficher chaque export et verifier les logs de chaque exporteur
 * 4. Verifier que getFormatsDisponibles() retourne bien les 3 formats
 */

// Votre code ici