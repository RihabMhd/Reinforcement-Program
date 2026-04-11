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
class Abonnement
{
    public string $type;
    public float $prixMensuel;
    public ?int $maxSeancesParSemaine;
    public bool $accesSauna;

    public function getDescription(): string
    {
        $max = $this->maxSeancesParSemaine ?? "Illimité";
        $sauna = $this->accesSauna ? "Oui" : "Non";
        return "{$this->type} | {$this->prixMensuel} DH | {$max} séances | Sauna: {$sauna}";
    }
}

class Membre
{
    public string $nom;
    public string $prenom;
    public string $email;
    public Abonnement $abonnement;
    public DateTime $dateDebut;
    public DateTime $dateFin;
    public int $seancesCetteSemaine = 0;
    public bool $actif = true;


    public function estAbonnementValide(): bool {
        $today = new DateTime();
        return $this->actif && $this->dateFin >= $today;
    }

    public function peutAccederSauna(): bool
    {
        return $this->abonnement->accesSauna && $this->estAbonnementValide();
    }

    public function enregistrerSeance(): void
    {
        if (!$this->estAbonnementValide()) {
            throw new RuntimeException("Abonnement invalide");
        }

        $max = $this->abonnement->maxSeancesParSemaine;

        if ($max !== null && $this->seancesCetteSemaine >= $max) {
            throw new RuntimeException("Quota atteint");
        }

        $this->seancesCetteSemaine++;
    }

    public function renouveler(int $mois): float
    {
        // prolonger date
        $this->dateFin->modify("+$mois months");

        $prix = $this->abonnement->prixMensuel * $mois;

        if ($mois >= 4 && $mois <= 11) {
            $prix *= 0.90;
        } elseif ($mois == 12) {
            $prix *= 0.80;
        }

        return $prix;
    }

    public function resetSeancesSemaine(): void
    {
        $this->seancesCetteSemaine = 0;
    }
}

class SalleSport
{
    public string $nom;
    public array $membres = [];
    public int $capaciteMax;

    public function inscrireMembre(Membre $m): void
    {
        if (count($this->membres) >= $this->capaciteMax) {
            throw new RuntimeException("Salle pleine");
        }
        $this->membres[] = $m;
    }

    public function getMembresActifs(): array
    {
        return array_filter($this->membres, fn($m) => $m->estAbonnementValide());
    }

    public function getMembresExpirant(int $joursAvant): array
    {
        $today = new DateTime();

        return array_filter($this->membres, function ($m) use ($today, $joursAvant) {
            $days = $today->diff($m->dateFin)->days;
            return $m->dateFin >= $today && $days <= $joursAvant;
        });
    }

    public function recetteMensuelle(): float
    {
        $total = 0;

        foreach ($this->getMembresActifs() as $m) {
            $total += $m->abonnement->prixMensuel;
        }

        return $total;
    }

    public function statistiquesParType(): array
    {
        $stats = [
            'basic' => 0,
            'premium' => 0,
            'vip' => 0,
            'expire' => 0
        ];

        foreach ($this->membres as $m) {
            if (!$m->estAbonnementValide()) {
                $stats['expire']++;
            } else {
                $stats[$m->abonnement->type]++;
            }
        }

        return $stats;
    }
}

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
