<?php
/**
 * EXERCICE 2 - Gestion d'une flotte de vehicules
 *
 * Une entreprise gere sa flotte et les missions de ses chauffeurs.
 *
 * =====================================================================
 * Classe Vehicule :
 *   Proprietes : id, immatriculation, marque, modele, kilometrage,
 *                disponible, dernierControle (date), tarifJourHT
 *
 * Methodes :
 *
 *   effectuerEntretien(int $kmActuels, string $dateControle) : void
 *     Mettre a jour le kilometrage si kmActuels > kilometrage actuel.
 *     Mettre a jour la date de dernier controle.
 *
 *   necessite_Controle() : bool
 *     Retourner true si plus de 180 jours depuis le dernier controle
 *     OU si (kmActuels - kmDernierControle) > 15000.
 *     Pour simplifier : retourner true si date controle > 6 mois.
 *
 *   toString() : string
 *     Format : "[AA-123-AA] Renault Clio | 45 200 km | Disponible"
 *
 * =====================================================================
 * Classe Mission :
 *   Proprietes : id, vehicule, chauffeur (string), depart, destination,
 *                dateDebut, dateFin (nullable), kmDebut, kmFin (nullable),
 *                statut ('planifiee'|'en_cours'|'terminee'|'annulee')
 *
 * Methodes :
 *
 *   demarrer(int $kmActuels) : void
 *     Verifier que la mission est 'planifiee'.
 *     Verifier que le vehicule est disponible.
 *     Passer le statut a 'en_cours', marquer vehicule indisponible.
 *
 *   terminer(int $kmFinaux, string $dateFin) : void
 *     Verifier que la mission est 'en_cours'.
 *     Mettre a jour kmFin, dateFin, statut.
 *     Liberer le vehicule (disponible = true).
 *     Mettre a jour le kilometrage du vehicule.
 *
 *   calculerCout() : float
 *     (dateFin - dateDebut en jours, minimum 1) * tarifJourHT du vehicule.
 *     Ajouter supplement kilometrique : si (kmFin - kmDebut) > 300 km,
 *     chaque km supplementaire coute 2.50 DH.
 *     Retourner le cout TTC (HT * 1.20).
 *
 * =====================================================================
 * Classe Flotte :
 *   Proprietes : nom (string), vehicules (Vehicule[]), missions (Mission[])
 *
 * Methodes :
 *
 *   ajouterVehicule(Vehicule $v) : void
 *
 *   creerMission(string $vehiculeId, string $chauffeur, string $depart,
 *                string $destination, string $dateDebut) : Mission
 *     Verifier que le vehicule existe et est disponible.
 *     Lever RuntimeException sinon.
 *
 *   vehiculesDisponibles() : array
 *
 *   coutTotalMissions() : float
 *     Somme des couts de toutes les missions terminees.
 *
 *   rapportMissions() : array
 *     Pour chaque vehicule : { immatriculation, nbMissions, kmParcourus, coutTotal }
 */

// Votre implementation ci-dessous
Class Vehicule {
    public int $id;
    public string $immatriculation;
    public string $marque;
    public string $modele;
    public int $kilometrage;
    public bool $disponible;
    public DateTime $dernierControle;
    public int $tarifJourHT;

    public function effectuerEntretien(int $kmActuels, string $dateControle) : void {
        if($kmActuels > $this->kilometrage){
            $this->kilometrage=$kmActuels;
            $this->dernierControle = new DateTime($dateControle);
        }
    }

    public function necessite_Controle() : bool {
        $today=new DateTime();
        $interval = $this->dernierControle->diff($today);
        return $interval->days > 180;
    }

    public function __toString()
    {
        $dispo = $this->disponible ? "Disponible" : "Indisponible";
        return "[{$this->immatriculation}] {$this->marque} {$this->modele} | {$this->kilometrage} km | $dispo";
    }

}

Class Mission {
    public int $id;
    public Vehicule $vehicule;
    public string $chauffeur;
    public string $depart;
    public string $destination;
    public DateTime $dateDebut;
    public DateTime|null $dateFin;
    public int $kmDebut;
    public int|null $kmFin;
    public string $statut;

    public function demarrer(int $kmActuels) : void {
        if($this->statut==='planifiee'){
            if($this->vehicule->disponible===true){
                $this->statut='en_cours';
                $this->vehicule->disponible=false;
                $this->kmDebut = $kmActuels;
            }
        }
    }

    public function terminer(int $kmFinaux, string $dateFin) : void{
        if($this->statut==='en_cours'){
            $this->dateFin = new DateTime($dateFin);
            $this->vehicule->kilometrage = $kmFinaux;
            $this->statut='terminee';
            $this->vehicule->disponible=true;
        }
    }

    public function calculerCout() : float {
        $days = max(1, $this->dateDebut->diff($this->dateFin)->days);
        $total = $days * $this->vehicule->tarifJourHT;

        $km = $this->kmFin - $this->kmDebut;
        if ($km > 300) {
            $total += ($km - 300) * 2.50;
        }

        return $total * 1.20;
    }
}


Class Flotte {
    public string $nom;
    public array $vehicules = [];
    public array $missions = [];

    public function ajouterVehicule(Vehicule $v) : void {
        $this->vehicules[]=$v;
    }

    public function creerMission(string $vehiculeId, string $chauffeur, string $depart,
        string $destination, string $dateDebut) : Mission {

        foreach ($this->vehicules as $v) {
            if ($v->immatriculation === $vehiculeId && $v->disponible) {

                $m = new Mission();
                $m->vehicule = $v;
                $m->chauffeur = $chauffeur;
                $m->depart = $depart;
                $m->destination = $destination;
                $m->dateDebut = new DateTime($dateDebut);
                $m->statut = 'planifiee';

                $this->missions[] = $m;
                return $m;
            }
        }

        throw new RuntimeException("Vehicule non disponible");
    }

    public function vehiculesDisponibles() : array {
        return array_filter($this->vehicules, fn($v) => $v->disponible);
    }

    public function coutTotalMissions() : float {
        $total = 0;

        foreach ($this->missions as $m) {
            if ($m->statut === 'terminee') {
                $total += $m->calculerCout();
            }
        }

        return $total;
    }
}

// Tests
$flotte = new Flotte("Transport Express Maroc");

$v1 = new Vehicule("1-AB-2345", "Renault", "Clio", 45200, "2023-06-15", 350);
$v2 = new Vehicule("2-CD-6789", "Peugeot", "308",  32100, "2024-01-10", 420);
$v3 = new Vehicule("3-EF-1122", "Ford",    "Transit", 98000, "2022-08-20", 580);

$flotte->ajouterVehicule($v1);
$flotte->ajouterVehicule($v2);
$flotte->ajouterVehicule($v3);

echo "Vehicules disponibles : " . count($flotte->vehiculesDisponibles()) . PHP_EOL;

$m1 = $flotte->creerMission("1-AB-2345", "Rachid B.", "Casablanca", "Rabat", "2024-04-01");
$m1->demarrer(45200);
$m1->terminer(45285, "2024-04-01");
echo "Cout mission 1 : " . $m1->calculerCout() . " DH TTC" . PHP_EOL;

$m2 = $flotte->creerMission("3-EF-1122", "Khalid M.", "Casablanca", "Marrakech", "2024-04-02");
$m2->demarrer(98000);
$m2->terminer(98480, "2024-04-03");
echo "Cout mission 2 : " . $m2->calculerCout() . " DH TTC" . PHP_EOL;

echo "Cout total : " . $flotte->coutTotalMissions() . " DH" . PHP_EOL;
print_r($flotte->rapportMissions());