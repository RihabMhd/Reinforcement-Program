<?php

/**
 * EXERCICE 1 - Gestion d'une pharmacie
 *
 * Une pharmacie gere son stock de medicaments et les ordonnances patients.
 *
 * =====================================================================
 * Classe Medicament :
 *   Proprietes : id, nom, prixUnitaire, stockActuel, stockMinimum, surOrdonnance
 *
 * Methodes :
 *
 *   approvisionner(int $quantite) : void
 *     Augmenter le stock. Quantite doit etre > 0.
 *
 *   estEnRupture() : bool
 *     Retourner true si stockActuel == 0.
 *
 *   estEnStockCritique() : bool
 *     Retourner true si 0 < stockActuel <= stockMinimum.
 *
 *   toString() : string
 *     Format : "Doliprane 500mg | Prix: 12.50 DH | Stock: 45 unites"
 *
 * =====================================================================
 * Classe LignePrescription :
 *   Proprietes : medicament (Medicament), quantitePrescrite, posologie (string)
 *
 * =====================================================================
 * Classe Ordonnance :
 *   Proprietes : id, nomPatient, medecin, date, lignes (LignePrescription[]),
 *                dispensee (bool)
 *
 * Methodes :
 *
 *   ajouterMedicament(Medicament $med, int $quantite, string $posologie) : void
 *     Verifier que le medicament n'est pas deja dans l'ordonnance.
 *     Lever une LogicException si doublon.
 *
 *   calculerCout() : float
 *     Calculer le total brut des medicaments de l'ordonnance.
 *     Appliquer une prise en charge securite sociale : 70% du total.
 *     Retourner le montant restant a charge patient (30%).
 *
 *   dispenser(Pharmacie $pharmacie) : array
 *     Verifier pour chaque medicament :
 *       - Disponible en stock
 *       - Non en rupture
 *     Decremente les stocks si tout est OK.
 *     Retourner ['succes' => bool, 'message' => string, 'manquants' => array]
 *     Marquer l'ordonnance comme dispensee.
 *
 * =====================================================================
 * Classe Pharmacie :
 *   Proprietes : nom, medicaments (Medicament[])
 *
 * Methodes :
 *
 *   ajouterMedicament(Medicament $med) : void
 *
 *   rechercherMedicament(string $nom) : ?Medicament
 *     Recherche insensible a la casse. Retourner null si non trouve.
 *
 *   getMedicamentsEnRupture() : array
 *   getMedicamentsEnStockCritique() : array
 *
 *   valeurStockTotal() : float
 *     Retourner sum(prixUnitaire * stockActuel) pour tous les medicaments.
 */

// Votre implementation ci-dessous
class Medicament
{
    public int $id;
    public string $nom;
    public float $prixUnitaire;
    public int $stockActuel;
    public int $stockMinimum;
    public bool $surOrdonnance;

    public function approvisionner(int $quantite): void
    {
        if ($quantite <= 0) {
            throw new InvalidArgumentException("Quantité invalide");
        }
        $this->stockActuel += $quantite;
    }

    public function estEnRupture(): bool
    {
        return $this->stockActuel === 0;
    }


    public function estEnStockCritique(): bool
    {
        return $this->stockActuel > 0 && $this->stockActuel <= $this->stockMinimum;
    }

    public function toString(): string
    {
        return printf($this->nom . $this->surOrdonnance . $this->prixUnitaire . $this->stockActuel);
    }
}

class LignePrescription
{
    public Medicament $medicament;
    public int $quantitePrescrite;
    public string $posologie;

    public function __construct(Medicament $medicament, int $quantitePrescrite, string $posologie)
    {
        $this->medicament = $medicament;
        $this->quantitePrescrite = $quantitePrescrite;
        $this->posologie = $posologie;
    }
}

Class Ordonnance {
    public int $id;
    public string $nomPatient;
    public string $medecin;
    public DateTime $date;
    public array $lignes=[];
    public bool $dispensee = false;

    public function __construct($nomPatient, $medecin)
    {
        $this->nomPatient = $nomPatient;
        $this->medecin = $medecin;
        $this->date = new DateTime();
    }

    public function ajouterMedicament(Medicament $med, int $quantite, string $posologie) : void{
        foreach($this->lignes as $lg){
            if($lg->medicament===$med){
                throw new LogicException("Médicament déjà ajouté");
            }
        }
        $this->lignes[] = new LignePrescription($med, $quantite, $posologie);
    }

    public function calculerCout() : float {

        $total=0;

        foreach($this->lignes as $lg){
            $total+=$lg->medicament->prixUnitaire * $lg->quantitePrescrite;
        }

        return $total *0.3;
    }

    public function dispenser(Pharmacie $pharmacie) : array {
         $manquants = [];

        foreach ($this->lignes as $ligne) {
            if ($ligne->medicament->stockActuel < $ligne->quantitePrescrite) {
                $manquants[] = $ligne->medicament->nom;
            }
        }

        if (!empty($manquants)) {
            return [
                'succes' => false,
                'message' => 'Stock insuffisant',
                'manquants' => $manquants
            ];
        }

        foreach ($this->lignes as $ligne) {
            $ligne->medicament->stockActuel -= $ligne->quantitePrescrite;
        }

        $this->dispensee = true;

        return [
            'succes' => true,
            'message' => 'Ordonnance dispensée',
            'manquants' => []
        ];
    
    }
    
}

class Pharmacie
{
    public string $nom;
    public array $medicaments = [];

    public function __construct($nom)
    {
        $this->nom = $nom;
    }

    public function ajouterMedicament(Medicament $med): void
    {
        $this->medicaments[] = $med;
    }

    public function rechercherMedicament(string $nom): ?Medicament
    {
        foreach ($this->medicaments as $med) {
            if (strtolower($med->nom) === strtolower($nom)) {
                return $med;
            }
        }
        return null;
    }

    public function getMedicamentsEnRupture(): array
    {
        return array_filter($this->medicaments, fn($m) => $m->estEnRupture());
    }

    public function getMedicamentsEnStockCritique(): array
    {
        return array_filter($this->medicaments, fn($m) => $m->estEnStockCritique());
    }

    public function valeurStockTotal(): float
    {
        $total = 0;
        foreach ($this->medicaments as $med) {
            $total += $med->prixUnitaire * $med->stockActuel;
        }
        return $total;
    }
}
// Tests attendus
$pharmacie = new Pharmacie("Pharmacie Centrale");

$paracetamol = new Medicament("Paracetamol 500mg", 12.50, 80, 15, false);
$amoxicilline = new Medicament("Amoxicilline 500mg", 35.00, 8, 20, true);
$ibuprofene = new Medicament("Ibuprofene 400mg", 18.00, 0, 10, false);
$metformine = new Medicament("Metformine 850mg", 28.00, 3, 15, true);

$pharmacie->ajouterMedicament($paracetamol);
$pharmacie->ajouterMedicament($amoxicilline);
$pharmacie->ajouterMedicament($ibuprofene);
$pharmacie->ajouterMedicament($metformine);

echo "=== Etat du stock ===" . PHP_EOL;
echo "Ruptures : " . count($pharmacie->getMedicamentsEnRupture()) . PHP_EOL;
echo "Critiques : " . count($pharmacie->getMedicamentsEnStockCritique()) . PHP_EOL;
echo "Valeur stock : " . $pharmacie->valeurStockTotal() . " DH" . PHP_EOL;

$ordonnance = new Ordonnance("Alami Hassan", "Dr. Benali");
$ordonnance->ajouterMedicament($paracetamol, 2, "1 comprime toutes les 8h");
$ordonnance->ajouterMedicament($amoxicilline, 1, "1 gelule 3 fois par jour");

echo PHP_EOL . "=== Ordonnance ===" . PHP_EOL;
echo "Reste a charge : " . $ordonnance->calculerCout() . " DH" . PHP_EOL;

$resultat = $ordonnance->dispenser($pharmacie);
echo "Dispensation : " . ($resultat['succes'] ? 'OK' : 'ECHEC') . PHP_EOL;
if (!$resultat['succes']) echo "Message : " . $resultat['message'] . PHP_EOL;
