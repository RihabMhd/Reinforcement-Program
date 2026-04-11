<?php
/**
 * EXERCICE 1 - Systeme de notifications multi-canal
 *
 * =====================================================================
 * Classe abstraite Notification :
 *   Proprietes : id, destinataire (string), contenu (string),
 *                dateEnvoi (nullable), statut ('en_attente'|'envoye'|'echec')
 *
 * Methodes abstraites :
 *   envoyer() : bool
 *     Doit etre implementee par chaque sous-classe.
 *     Retourne true si l'envoi simule est un succes.
 *
 * Methodes concretes :
 *   getResume() : string
 *     Retourner "[TYPE] A: destinataire | Statut: statut | contenu (30 premiers chars)"
 *     TYPE = nom de la classe (EmailNotification, SmsNotification, etc.)
 *
 *   marquerEnvoye() : void    (protected)
 *   marquerEchec(string $raison) : void   (protected)
 *
 * =====================================================================
 * Classe EmailNotification extends Notification :
 *   Proprietes supplementaires : expediteur (string), sujet (string),
 *                                 piecesJointes (array)
 *
 * envoyer() : bool
 *   Valider que destinataire est un email valide.
 *   Simuler un envoi (sleep(0) ou juste retourner true apres marquage).
 *   Marquer envoye ou echec selon validation.
 *
 * ajouterPieceJointe(string $fichier) : void
 *
 * =====================================================================
 * Classe SmsNotification extends Notification :
 *   Proprietes supplementaires : numeroTelephone (string)
 *
 * envoyer() : bool
 *   Valider le format du telephone (10 chiffres, commence par 06 ou 07).
 *   Verifier que le contenu ne depasse pas 160 caracteres.
 *   Marquer envoye ou echec.
 *
 * =====================================================================
 * Classe PushNotification extends Notification :
 *   Proprietes supplementaires : tokenAppareil (string), titre (string),
 *                                 donnees (array, optionnel)
 *
 * envoyer() : bool
 *   Valider que tokenAppareil n'est pas vide.
 *   Simuler envoi, marquer resultat.
 *
 * =====================================================================
 * Classe NotificationService :
 *   Proprietes : notifications (Notification[])
 *
 * Methodes :
 *
 *   ajouter(Notification $n) : void
 *
 *   envoyerToutes() : array
 *     Envoyer toutes les notifications en attente.
 *     Retourner { 'envoyes' => N, 'echecs' => N, 'details' => [...] }
 *
 *   getParStatut(string $statut) : array
 *
 *   statistiques() : array
 *     { 'total' => N, 'envoyes' => N, 'echecs' => N, 'en_attente' => N,
 *       'parType' => { 'Email' => N, 'SMS' => N, 'Push' => N } }
 */

// Votre implementation

abstract class Notification {
    public int $id;
    public string $destinataire;
    public string $contenu;
    public ?DateTime $dateEnvoi = null;
    public string $statut = 'en_attente';

    public function __construct($dest, $contenu) {
        $this->destinataire = $dest;
        $this->contenu = $contenu;
    }

    abstract public function envoyer() : bool;

    public function getResume() : string {
        $type = (new ReflectionClass($this))->getShortName();
        $contenuCourt = substr($this->contenu, 0, 30);

        return "[$type] A: {$this->destinataire} | Statut: {$this->statut} | {$contenuCourt}";
    }

    protected function marquerEnvoye() : void {
        $this->statut = 'envoye';
        $this->dateEnvoi = new DateTime();
    }

    protected function marquerEchec(string $raison) : void {
        $this->statut = 'echec';
    }
}

class EmailNotification extends Notification {
    public string $expediteur;
    public string $sujet;
    public array $piecesJointes = [];

    public function __construct($dest, $contenu, $expediteur, $sujet) {
        parent::__construct($dest, $contenu);
        $this->expediteur = $expediteur;
        $this->sujet = $sujet;
    }

    public function envoyer(): bool {
        if (!filter_var($this->destinataire, FILTER_VALIDATE_EMAIL)) {
            $this->marquerEchec("Email invalide");
            return false;
        }

        $this->marquerEnvoye();
        return true;
    }

    public function ajouterPieceJointe(string $fichier): void {
        $this->piecesJointes[] = $fichier;
    }
}

class SmsNotification extends Notification {
    public string $numeroTelephone;

    public function __construct($numero, $contenu) {
        parent::__construct($numero, $contenu);
        $this->numeroTelephone = $numero;
    }

    public function envoyer(): bool {
        if (!preg_match('/^(06|07)[0-9]{8}$/', $this->numeroTelephone)) {
            $this->marquerEchec("Numero invalide");
            return false;
        }

        if (strlen($this->contenu) > 160) {
            $this->marquerEchec("Message trop long");
            return false;
        }

        $this->marquerEnvoye();
        return true;
    }
}

class PushNotification extends Notification {
    public string $tokenAppareil;
    public string $titre;
    public array $donnees;

    public function __construct($token, $contenu, $titre, $donnees = []) {
        parent::__construct($token, $contenu);
        $this->tokenAppareil = $token;
        $this->titre = $titre;
        $this->donnees = $donnees;
    }

    public function envoyer(): bool {
        if (empty($this->tokenAppareil)) {
            $this->marquerEchec("Token vide");
            return false;
        }

        $this->marquerEnvoye();
        return true;
    }
}
class NotificationService {
    public array $notifications = [];

    public function ajouter(Notification $n): void {
        $this->notifications[] = $n;
    }

    public function envoyerToutes(): array {
        $envoyes = 0;
        $echecs = 0;
        $details = [];

        foreach ($this->notifications as $n) {
            if ($n->statut === 'en_attente') {
                $result = $n->envoyer();

                if ($result) $envoyes++;
                else $echecs++;

                $details[] = $n->getResume();
            }
        }

        return [
            'envoyes' => $envoyes,
            'echecs' => $echecs,
            'details' => $details
        ];
    }

    public function getParStatut(string $statut): array {
        return array_filter($this->notifications, fn($n) => $n->statut === $statut);
    }

    public function statistiques(): array {
        $stats = [
            'total' => count($this->notifications),
            'envoyes' => 0,
            'echecs' => 0,
            'en_attente' => 0,
            'parType' => [
                'Email' => 0,
                'SMS' => 0,
                'Push' => 0
            ]
        ];

        foreach ($this->notifications as $n) {
            $stats[$n->statut]++;

            if ($n instanceof EmailNotification) $stats['parType']['Email']++;
            elseif ($n instanceof SmsNotification) $stats['parType']['SMS']++;
            elseif ($n instanceof PushNotification) $stats['parType']['Push']++;
        }

        return $stats;
    }
}

// Tests
$service = new NotificationService();

$email1 = new EmailNotification('h.alami@email.ma', 'Confirmation commande',
    'Votre commande CMD-001 a bien ete validee.', 'noreply@shop.ma',
    'Confirmation commande #CMD-001');

$email2 = new EmailNotification('email-invalide', 'Test',
    'Ce message ne sera pas envoye.', 'noreply@shop.ma', 'Test');

$sms1 = new SmsNotification('0612345678', 'Votre code OTP est : 482917. Valable 5 minutes.');

$sms2 = new SmsNotification('0512345678', // Numero invalide
    'Test SMS');

