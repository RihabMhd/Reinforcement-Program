/**
 * EXERCICE 2 - Simulation de transactions bancaires
 *
 * Contexte :
 * Vous developpez le module de traitement des transactions d'une fintech.
 * Chaque compte a un solde et un historique de transactions. Vous devez
 * implementer le moteur de traitement et les controles associes.
 *
 * Regles metier :
 * - Un retrait est refuse si solde - montant < decouvertAutorise
 * - Frais de retrait : 0.5% si compte type 'courant', 0 si 'epargne'
 * - Frais mensuels : 25 DH si solde moyen du mois < 1000 DH (compte courant uniquement)
 * - Virement entre comptes : atomique (les deux operations reussissent ou aucune)
 *
 * Travail demande :
 *
 * 1. traiterTransaction(comptes, transaction)
 *    Retourner un nouvel etat des comptes apres traitement de la transaction.
 *    transaction = { type: 'depot'|'retrait'|'virement', compteId, montant, compteDestId? }
 *    Retourner { succes: false, erreur: '...' } si la transaction echoue.
 *    Retourner { succes: true, comptes: [...] } si elle reussit.
 *
 * 2. appliquerFraisMensuels(comptes, mois)
 *    mois au format 'YYYY-MM'.
 *    Calculer la moyenne du solde sur le mois pour chaque compte courant,
 *    puis deduire les frais si applicable.
 *    Retourner le nouvel etat des comptes.
 *
 * 3. releveCompte(compte)
 *    Retourner un objet releve avec :
 *    { solde, totalDebits, totalCredits, nombreOperations, derniereOperation }
 *
 * 4. detecterSuspects(comptes)
 *    Retourner les transactions suspects : montant > 3x la moyenne des transactions du compte.
 *    Format : [{ compteId, transaction, ecartMoyenne }]
 */

const comptes = [
  {
    id: 'CPT001', titulaire: 'Alami Hassan',  type: 'courant', solde: 5200,
    decouvertAutorise: -1000,
    transactions: [
      { id: 'TR001', type: 'depot',   montant: 5000, date: '2024-03-01', soldeApres: 5000 },
      { id: 'TR002', type: 'retrait', montant: 800,  date: '2024-03-05', soldeApres: 4200 },
      { id: 'TR003', type: 'depot',   montant: 2500, date: '2024-03-15', soldeApres: 6700 },
      { id: 'TR004', type: 'retrait', montant: 1500, date: '2024-03-22', soldeApres: 5200 },
    ]
  },
  {
    id: 'CPT002', titulaire: 'Benali Sara', type: 'epargne', solde: 18500,
    decouvertAutorise: 0,
    transactions: [
      { id: 'TR005', type: 'depot',   montant: 20000, date: '2024-01-10', soldeApres: 20000 },
      { id: 'TR006', type: 'retrait', montant: 1500,  date: '2024-02-20', soldeApres: 18500 },
    ]
  },
  {
    id: 'CPT003', titulaire: 'Chraibi Omar', type: 'courant', solde: 350,
    decouvertAutorise: -500,
    transactions: [
      { id: 'TR007', type: 'depot',   montant: 1200, date: '2024-03-02', soldeApres: 1200 },
      { id: 'TR008', type: 'retrait', montant: 850,  date: '2024-03-10', soldeApres: 350  },
    ]
  }
];

function traiterTransaction(comptes, transaction) {
  // TODO
}

function appliquerFraisMensuels(comptes, mois) {
  // TODO
}

function releveCompte(compte) {
  // TODO
}

function detecterSuspects(comptes) {
  // TODO
}

// Tests
const result = traiterTransaction(comptes, { type: 'virement', compteId: 'CPT001', compteDestId: 'CPT003', montant: 500 });
console.log('Virement:', result.succes);

const resultEchec = traiterTransaction(comptes, { type: 'retrait', compteId: 'CPT003', montant: 1000 });
console.log('Retrait refuse:', resultEchec);

console.log('Releve CPT001:', releveCompte(comptes[0]));
console.log('Transactions suspectes:', detecterSuspects(comptes));