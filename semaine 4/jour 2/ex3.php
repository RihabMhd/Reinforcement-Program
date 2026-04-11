<?php
/**
 * EXERCICE 3 — Polymorphisme avance : systeme de notifications multi-canal
 *
 * Contexte :
 * Une plateforme e-learning envoie des notifications aux apprenants
 * via differents canaux selon leurs preferences.
 *
 * A implementer :
 *
 * Interface Sendable
 *   envoyer() : string
 *     => Retourne une chaine decrivant l envoi (ex: "[EMAIL -> amine@email.com] : Votre cours X est disponible")
 *   getCanal() : string
 *   getPriorite() : int (1=basse, 2=normale, 3=haute, 4=urgente)
 *
 * Interface Schedulable
 *   planifier(DateTime $date) : void
 *   getDatePlanifiee() : ?DateTime
 *   estPlanifie() : bool
 *
 * Classe abstraite Notification implements Sendable, Schedulable
 *   Proprietes (protected) : id, destinataire, sujet, contenu, datePlanifiee
 *   Constructeur : id, destinataire, sujet, contenu
 *   planifier() / getDatePlanifiee() / estPlanifie() : implementation concrete ici
 *   getId() / getDestinataire() etc : getters
 *
 * Classe EmailNotification extends Notification
 *   Propriete : adresseEmail (string)
 *   Propriete : pieceJointe (string|null)
 *   envoyer() : "[EMAIL -> adresseEmail] sujet : contenu (+ PJ: fichier si pieceJointe)"
 *   getCanal() : "email"
 *   getPriorite() : 2 (normale)
 *   attacherFichier(string $fichier) : void
 *
 * Classe SmsNotification extends Notification
 *   Propriete : numeroDeTelephone (string)
 *   Propriete : smsContent (string = 160 premiers caracteres du contenu max)
 *   envoyer() : "[SMS -> numero] : smsContent"
 *   getCanal() : "sms"
 *   getPriorite() : 3 (haute — immediat)
 *   Note : tronquer le contenu a 160 chars automatiquement dans le constructeur
 *
 * Classe PushNotification extends Notification
 *   Propriete : deviceToken (string)
 *   Propriete : icone (string, defaut "bell")
 *   envoyer() : "[PUSH -> deviceToken] (icone) sujet : contenu"
 *   getCanal() : "push"
 *   getPriorite() : 2
 *
 * Classe AlerteCritique extends EmailNotification
 *   getPriorite() : 4 (urgente)
 *   envoyer() : "[ALERTE CRITIQUE] " + parent::envoyer()
 *
 * Classe NotificationService
 *   Proprietes : file (array de Sendable), historique (array)
 *   Methodes :
 *     ajouter(Sendable $n) : void => ajoute dans la file triee par priorite decroissante
 *     envoyerTout() : void => vide la file, appelle envoyer() sur chaque, stocke dans historique
 *     envoyerParCanal(string $canal) : void => envoie seulement celles d un canal donne
 *     getHistorique() : array
 *     getNbEnAttente() : int
 *     getNbParCanal() : array => ['email' => 2, 'sms' => 1, ...]
 *
 * Tests a realiser :
 * 1. Creer plusieurs notifications de types differents
 * 2. Ajouter une AlerteCritique et verifier qu elle est traitee en premier (priorite 4)
 * 3. Planifier une notification pour demain, verifier estPlanifie()
 * 4. Utiliser envoyerParCanal('sms') et verifier que seuls les SMS sont traites
 * 5. Afficher l historique complet et le rapport getNbParCanal()
 */

// Votre code ici