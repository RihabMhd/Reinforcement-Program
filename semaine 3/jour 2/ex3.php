<?php

/**
 * EXERCICE 3 - Gestion d'une salle de sport (abonnements et seances)
 *
 * =====================================================================
 * Classe Abonnement :
 *   Proprietes : type ('basic'|'premium'|'vip'), prixMensuel,
 *                maxSeancesParSemaine (null = illimite), accesSauna (bool)
 *
 * Methodes :
 *   getDescription() : string
 *
 * =====================================================================
 * Classe Membre :
 *   Proprietes : id, nom, prenom, email, abonnement (Abonnement),
 *                dateDebut, dateFin, seancesCetteSemaine (int), actif
 *
 * Methodes :
 *
 *   estAbonnementValide() : bool
 *     Retourner true si dateFin >= aujourd'hui ET actif.
 *
 *   peutAccederSauna() : bool
 *     Retourner true si abonnement.accesSauna ET abonnementValide.
 *
 *   enregistrerSeance() : void
 *     Verifier que l'abonnement est valide.
 *     Verifier que le quota hebdomadaire n'est pas atteint
 *     (si maxSeancesParSemaine n'est pas null).
 *     Incrementer seancesCetteSemaine.
 *     Lever RuntimeException si acces refuse.
 *
 *   renouveler(int $mois) : void
 *     Prolonger dateFin de $mois mois.
 *     Calculer et retourner le montant paye :
 *     - 1 a 3 mois : prix normal
 *     - 4 a 11 mois : 10% de remise
 *     - 12 mois : 20% de remise
 *
 *   resetSeancesSemaine() : void
 *     Remettre seancesCetteSemaine a 0 (appele chaque Jour 01).
 *
 * =====================================================================
 * Classe SalleSport :
 *   Proprietes : nom, membres (Membre[]), capaciteMax
 *
 * Methodes :
 *
 *   inscrireMembre(Membre $m) : void
 *
 *   getMembresActifs() : array
 *     Membres dont l'abonnement est valide.
 *
 *   getMembresExpirant(int $joursAvant) : array
 *     Membres dont l'abonnement expire dans moins de $joursAvant jours.
 *
 *   recetteMensuelle() : float
 *     Somme des prixMensuel de tous les membres actifs.
 *
 *   statistiquesParType() : array
 *     { 'basic' => N, 'premium' => N, 'vip' => N, 'expire' => N }
 */

// Votre implementation ci-dessous

// Tests
$basicAbo   = new Abonnement('basic',   199, 3, false);
$premiumAbo = new Abonnement('premium', 349, null, true);
$vipAbo     = new Abonnement('vip',     499, null, true);

$salle = new SalleSport("FitPro Casablanca", 200);

$m1 = new Membre("Alami", "Hassan", "h.alami@email.ma", $premiumAbo, "2024-01-01", "2024-12-31");
$m2 = new Membre("Benali", "Sara",  "s.benali@email.ma", $basicAbo,  "2024-02-01", "2024-03-31");
$m3 = new Membre("Chraibi", "Omar", "o.chraibi@web.ma",  $vipAbo,    "2024-01-15", "2024-07-15");

$salle->inscrireMembre($m1);
$salle->inscrireMembre($m2);
$salle->inscrireMembre($m3);

// Enregistrer seances
$m1->enregistrerSeance();
$m1->enregistrerSeance();

// Test quota basic
try {
    $m2->enregistrerSeance(); // 1
    $m2->enregistrerSeance(); // 2
    $m2->enregistrerSeance(); // 3
    $m2->enregistrerSeance(); // doit echouer
} catch (RuntimeException $e) {
    echo "Limite atteinte : " . $e->getMessage() . PHP_EOL;
}

echo "Recette mensuelle : " . $salle->recetteMensuelle() . " DH" . PHP_EOL;
print_r($salle->statistiquesParType());

$montantRenouvellement = $m1->renouveler(12);
echo "Renouvellement 12 mois : " . $montantRenouvellement . " DH" . PHP_EOL;