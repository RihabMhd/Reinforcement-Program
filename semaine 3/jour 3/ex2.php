<?php
/**
 * EXERCICE 2 - Systeme de gestion des documents administratifs
 *
 * =====================================================================
 * Classe abstraite Document :
 *   Proprietes : id, titre, contenu (string), auteur (string),
 *                dateCreation, dateDerniereModif, statut, version (int)
 *   statut : 'brouillon' | 'soumis' | 'approuve' | 'rejete' | 'archive'
 *
 * Methodes abstraites :
 *   valider() : bool
 *     Chaque sous-classe definit ses propres criteres de validation.
 *
 *   getTypeDocument() : string
 *     Retourner le nom du type ('Contrat', 'Rapport', 'Facture', etc.)
 *
 * Methodes concretes :
 *   soumettre() : void
 *     Passer en 'soumis' si le document est valide ET en 'brouillon'.
 *     Lever LogicException si conditions non reunies.
 *
 *   approuver(string $approbateur) : void
 *     Possible uniquement si statut = 'soumis'.
 *
 *   rejeter(string $motif) : void
 *     Possible uniquement si statut = 'soumis'.
 *     Repasser en 'brouillon' pour correction.
 *
 *   archiver() : void
 *     Possible si statut = 'approuve'.
 *
 *   incrementerVersion() : void  (protected)
 *
 * =====================================================================
 * Classe Contrat extends Document :
 *   Proprietes supplementaires : partieA, partieB, dateDebut, dateFin,
 *                                 montant (float), typeContrat (CDI/CDD/Prestation)
 *
 *   valider() : bool
 *     Contenu >= 100 chars, partieA et partieB non vides,
 *     dateDebut < dateFin, montant > 0.
 *
 * =====================================================================
 * Classe RapportMensuel extends Document :
 *   Proprietes supplementaires : mois, annee, departement,
 *                                 kpis (array), fichierJoint (string|null)
 *
 *   valider() : bool
 *     Titre non vide, mois entre 1 et 12, annee entre 2020 et annee actuelle,
 *     au moins un KPI present.
 *
 *   ajouterKpi(string $nom, $valeur) : void
 *
 * =====================================================================
 * Classe Facture extends Document :
 *   Proprietes supplementaires : client, lignes (array), tauxTVA (float),
 *                                 estPayee (bool)
 *   ligne = { description, quantite, prixUnitaire }
 *
 *   ajouterLigne(string $desc, int $qte, float $pu) : void
 *
 *   calculerTotalHT() : float
 *   calculerTotalTTC() : float
 *
 *   valider() : bool
 *     Client non vide, au moins une ligne, totalHT > 0.
 *
 *   marquerPayee() : void
 *     Possible uniquement si statut = 'approuve'.
 *
 * =====================================================================
 * Classe GestionnaireDocuments :
 *   Methodes :
 *     ajouter(Document $d) : void
 *     getParType(string $type) : array
 *     getParStatut(string $statut) : array
 *     getDocumentsAApprouver() : array
 *     statistiques() : array
 */

// Votre implementation
abstract class Document {
    public string $titre;
    public string $contenu;
    public string $auteur;
    public DateTime $dateCreation;
    public DateTime $dateDerniereModif;
    public string $statut = 'brouillon';
    public int $version = 1;

    public function __construct($titre, $contenu, $auteur) {
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->auteur = $auteur;
        $this->dateCreation = new DateTime();
        $this->dateDerniereModif = new DateTime();
    }

    abstract public function valider(): bool;
    abstract public function getTypeDocument(): string;

    public function soumettre(): void {
        if ($this->statut !== 'brouillon' || !$this->valider()) {
            throw new LogicException("Impossible de soumettre");
        }
        $this->statut = 'soumis';
    }

    public function approuver(string $approbateur): void {
        if ($this->statut !== 'soumis') {
            throw new LogicException("Doit etre soumis");
        }
        $this->statut = 'approuve';
    }

    public function rejeter(string $motif): void {
        if ($this->statut !== 'soumis') {
            throw new LogicException("Doit etre soumis");
        }
        $this->statut = 'brouillon';
    }

    public function archiver(): void {
        if ($this->statut !== 'approuve') {
            throw new LogicException("Doit etre approuve");
        }
        $this->statut = 'archive';
    }

    protected function incrementerVersion(): void {
        $this->version++;
        $this->dateDerniereModif = new DateTime();
    }

    public function getStatut(): string {
        return $this->statut;
    }
}

class Contrat extends Document {
    public string $partieA;
    public string $partieB;
    public DateTime $dateDebut;
    public DateTime $dateFin;
    public float $montant;
    public string $typeContrat;

    public function __construct($titre, $contenu, $auteur,
        $partieA, $partieB, $dateDebut, $dateFin, $montant, $type) {

        parent::__construct($titre, $contenu, $auteur);
        $this->partieA = $partieA;
        $this->partieB = $partieB;
        $this->dateDebut = new DateTime($dateDebut);
        $this->dateFin = new DateTime($dateFin);
        $this->montant = $montant;
        $this->typeContrat = $type;
    }

    public function valider(): bool {
        return strlen($this->contenu) >= 100 &&
               !empty($this->partieA) &&
               !empty($this->partieB) &&
               $this->dateDebut < $this->dateFin &&
               $this->montant > 0;
    }

    public function getTypeDocument(): string {
        return "Contrat";
    }
}

class RapportMensuel extends Document {
    public int $mois;
    public int $annee;
    public string $departement;
    public array $kpis = [];
    public ?string $fichierJoint = null;

    public function ajouterKpi(string $nom, $valeur): void {
        $this->kpis[$nom] = $valeur;
    }

    public function setMoisAnnee(int $mois, int $annee): void {
        $this->mois = $mois;
        $this->annee = $annee;
    }

    public function setDepartement(string $dep): void {
        $this->departement = $dep;
    }

    public function valider(): bool {
        $currentYear = (int)date("Y");

        return !empty($this->titre) &&
               $this->mois >= 1 && $this->mois <= 12 &&
               $this->annee >= 2020 && $this->annee <= $currentYear &&
               count($this->kpis) > 0;
    }

    public function getTypeDocument(): string {
        return "Rapport";
    }
}

class Facture extends Document {
    public string $client;
    public array $lignes = [];
    public float $tauxTVA = 0.20;
    public bool $estPayee = false;

    public function __construct($client, $auteur) {
        parent::__construct("", "", $auteur);
        $this->client = $client;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function ajouterLigne(string $desc, int $qte, float $pu): void {
        $this->lignes[] = [
            'description' => $desc,
            'quantite' => $qte,
            'prixUnitaire' => $pu
        ];
    }

    public function calculerTotalHT(): float {
        $total = 0;
        foreach ($this->lignes as $l) {
            $total += $l['quantite'] * $l['prixUnitaire'];
        }
        return $total;
    }

    public function calculerTotalTTC(): float {
        return $this->calculerTotalHT() * (1 + $this->tauxTVA);
    }

    public function valider(): bool {
        return !empty($this->client) &&
               count($this->lignes) > 0 &&
               $this->calculerTotalHT() > 0;
    }

    public function marquerPayee(): void {
        if ($this->statut !== 'approuve') {
            throw new LogicException("Doit etre approuve");
        }
        $this->estPayee = true;
    }

    public function getTypeDocument(): string {
        return "Facture";
    }
}

class GestionnaireDocuments {
    public array $documents = [];

    public function ajouter(Document $d): void {
        $this->documents[] = $d;
    }

    public function getParType(string $type): array {
        return array_filter($this->documents, fn($d) => $d->getTypeDocument() === $type);
    }

    public function getParStatut(string $statut): array {
        return array_filter($this->documents, fn($d) => $d->getStatut() === $statut);
    }

    public function getDocumentsAApprouver(): array {
        return $this->getParStatut('soumis');
    }

    public function statistiques(): array {
        $stats = [
            'total' => count($this->documents),
            'brouillon' => 0,
            'soumis' => 0,
            'approuve' => 0,
            'rejete' => 0,
            'archive' => 0
        ];

        foreach ($this->documents as $d) {
            $stats[$d->getStatut()]++;
        }

        return $stats;
    }
}
// Tests
$gestionnaire = new GestionnaireDocuments();

$contrat = new Contrat(
    "Contrat de prestation IT", "Description complete du contrat de prestation pour le developpement d'une application web.",
    "Sys Admin",
    "TechCorp SARL", "Benali Consulting",
    "2024-01-01", "2024-12-31",
    120000.00, "Prestation"
);

$rapport = new RapportMensuel("Rapport Janvier 2024", "Bilan mensuel complet des operations", "RH Manager");
$rapport->ajouterKpi("Effectif", 145);
$rapport->ajouterKpi("Recrutements", 3);
$rapport->ajouterKpi("Departs", 1);
$rapport->setMoisAnnee(1, 2024);
$rapport->setDepartement("Ressources Humaines");

$facture = new Facture("Alami Hassan", "Dev Backend");
$facture->setTitre("Facture 2024-001");
$facture->ajouterLigne("Developpement API REST", 5, 1500);
$facture->ajouterLigne("Tests et documentation", 2, 800);

$gestionnaire->ajouter($contrat);
$gestionnaire->ajouter($rapport);
$gestionnaire->ajouter($facture);

$contrat->soumettre();
$contrat->approuver("Direction");
echo "Statut contrat : " . $contrat->getStatut() . PHP_EOL;

$facture->soumettre();
$facture->approuver("Comptabilite");
$facture->marquerPayee();
echo "Facture TTC : " . $facture->calculerTotalTTC() . " DH" . PHP_EOL;

print_r($gestionnaire->statistiques());