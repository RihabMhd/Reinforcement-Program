<?php
/**
 * EXERCICE 3 - Systeme de livraison avec types de vehicules
 *
 * =====================================================================
 * Classe abstraite VehiculeLivraison :
 *   Proprietes : id, immatriculation, chauffeur (string),
 *                chargeMaxKg (float), volumeMaxM3 (float),
 *                disponible (bool), positionActuelle (string)
 *
 * Methodes abstraites :
 *   calculerCoutLivraison(float $distanceKm, float $poidsKg) : float
 *   getTypevehicule() : string
 *
 * Methodes concretes :
 *   peutTransporter(float $poidsKg, float $volumeM3) : bool
 *   toString() : string
 *
 * =====================================================================
 * Classe Scooter extends VehiculeLivraison :
 *   chargeMax : 30kg max, volume : 0.1m3
 *   calculerCoutLivraison : 5 DH/km, minimum 25 DH
 *   Supplement : +10% si poids > 15kg
 *
 * =====================================================================
 * Classe Camionnette extends VehiculeLivraison :
 *   calculerCoutLivraison : 3.5 DH/km, minimum 50 DH
 *   Supplement : +15% si poids > chargeMax * 0.8
 *
 * =====================================================================
 * Classe PoidLourd extends VehiculeLivraison :
 *   calculerCoutLivraison : 2.2 DH/km, minimum 200 DH
 *   Supplement carburant : +8% fixe
 *   Peage autoroute : 30 DH si distanceKm > 100
 *
 * =====================================================================
 * Classe DispatcheurLivraisons :
 *   Proprietes : vehicules (VehiculeLivraison[])
 *
 * Methodes :
 *
 *   ajouterVehicule(VehiculeLivraison $v) : void
 *
 *   trouverMeilleurVehicule(float $distanceKm, float $poidsKg, float $volumeM3) : ?VehiculeLivraison
 *     Parmi les vehicules disponibles pouvant transporter la charge,
 *     retourner celui qui propose le cout de livraison le plus bas.
 *
 *   planifierLivraison(string $destination, float $distanceKm,
 *                       float $poidsKg, float $volumeM3) : array
 *     Utiliser trouverMeilleurVehicule.
 *     Retourner { 'vehicule' => ..., 'cout' => ..., 'type' => ... }
 *     Retourner null si aucun vehicule disponible.
 *
 *   getCoutsParType() : array
 *     Simuler le cout pour une livraison standard (100 km, 50 kg, 0.5 m3)
 *     pour chaque type de vehicule present dans la flotte.
 *     { Scooter: N, Camionnette: N, PoidLourd: N }
 */

// Votre implementation
abstract class VehiculeLivraison {
    public string $immatriculation;
    public string $chauffeur;
    public float $chargeMaxKg;
    public float $volumeMaxM3;
    public bool $disponible = true;
    public string $positionActuelle = "Depot";

    public function __construct($immat, $chauffeur, $charge, $volume) {
        $this->immatriculation = $immat;
        $this->chauffeur = $chauffeur;
        $this->chargeMaxKg = $charge;
        $this->volumeMaxM3 = $volume;
    }

    abstract public function calculerCoutLivraison(float $distanceKm, float $poidsKg): float;
    abstract public function getTypeVehicule(): string;

    public function peutTransporter(float $poidsKg, float $volumeM3): bool {
        return $poidsKg <= $this->chargeMaxKg && $volumeM3 <= $this->volumeMaxM3;
    }

    public function __toString(): string {
        return "{$this->getTypeVehicule()} - {$this->immatriculation}";
    }
}

class Scooter extends VehiculeLivraison {

    public function calculerCoutLivraison(float $distanceKm, float $poidsKg): float {
        $cout = max(25, $distanceKm * 5);

        if ($poidsKg > 15) {
            $cout *= 1.10;
        }

        return $cout;
    }

    public function getTypeVehicule(): string {
        return "Scooter";
    }
}

class Camionnette extends VehiculeLivraison {

    public function calculerCoutLivraison(float $distanceKm, float $poidsKg): float {
        $cout = max(50, $distanceKm * 3.5);

        if ($poidsKg > $this->chargeMaxKg * 0.8) {
            $cout *= 1.15;
        }

        return $cout;
    }

    public function getTypeVehicule(): string {
        return "Camionnette";
    }
}

class PoidLourd extends VehiculeLivraison {

    public function calculerCoutLivraison(float $distanceKm, float $poidsKg): float {
        $cout = max(200, $distanceKm * 2.2);

        // +8% carburant
        $cout *= 1.08;

        // péage
        if ($distanceKm > 100) {
            $cout += 30;
        }

        return $cout;
    }

    public function getTypeVehicule(): string {
        return "PoidLourd";
    }
}

class DispatcheurLivraisons {
    public array $vehicules = [];

    public function ajouterVehicule(VehiculeLivraison $v): void {
        $this->vehicules[] = $v;
    }

    public function trouverMeilleurVehicule(float $distanceKm, float $poidsKg, float $volumeM3): ?VehiculeLivraison {
        $best = null;
        $minCost = INF;

        foreach ($this->vehicules as $v) {
            if ($v->disponible && $v->peutTransporter($poidsKg, $volumeM3)) {
                $cost = $v->calculerCoutLivraison($distanceKm, $poidsKg);

                if ($cost < $minCost) {
                    $minCost = $cost;
                    $best = $v;
                }
            }
        }

        return $best;
    }

    public function planifierLivraison(string $destination, float $distanceKm,
        float $poidsKg, float $volumeM3): ?array {

        $vehicule = $this->trouverMeilleurVehicule($distanceKm, $poidsKg, $volumeM3);

        if (!$vehicule) return null;

        $cout = $vehicule->calculerCoutLivraison($distanceKm, $poidsKg);

        return [
            'vehicule' => $vehicule,
            'cout' => $cout,
            'type' => $vehicule->getTypeVehicule()
        ];
    }

    public function getCoutsParType(): array {
        $result = [];

        foreach ($this->vehicules as $v) {
            $type = $v->getTypeVehicule();

            if (!isset($result[$type])) {
                $result[$type] = $v->calculerCoutLivraison(100, 50);
            }
        }

        return $result;
    }
}

// Tests
$dispatcheur = new DispatcheurLivraisons();

$dispatcheur->ajouterVehicule(new Scooter("1-SC-001", "Ali K.", 25, 0.08));
$dispatcheur->ajouterVehicule(new Scooter("1-SC-002", "Omar M.", 25, 0.08));
$dispatcheur->ajouterVehicule(new Camionnette("2-CM-010", "Rachid B.", 800, 4.0));
$dispatcheur->ajouterVehicule(new Camionnette("2-CM-011", "Samir A.", 1000, 5.5));
$dispatcheur->ajouterVehicule(new PoidLourd("3-PL-100", "Hassan D.", 12000, 40.0));

// Petite livraison : 8 km, 5 kg
$l1 = $dispatcheur->planifierLivraison("Hay Hassani", 8, 5, 0.03);
echo "Livraison 1 : " . $l1['type'] . " | " . $l1['cout'] . " DH" . PHP_EOL;

// Grosse livraison : 200 km, 5000 kg
$l2 = $dispatcheur->planifierLivraison("Marrakech", 200, 5000, 15.0);
echo "Livraison 2 : " . $l2['type'] . " | " . $l2['cout'] . " DH" . PHP_EOL;

print_r($dispatcheur->getCoutsParType());