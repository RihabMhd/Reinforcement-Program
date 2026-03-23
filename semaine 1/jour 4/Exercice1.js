/**
 * EXERCICE 1 - Registre d'abonnes avec gestion des abonnements
 *
 * Contexte :
 * Vous travaillez sur la plateforme d'abonnement d'un service de streaming.
 * Vous devez implementer le module de gestion des abonnes.
 *
 * Travail demande :
 *
 * 1. ajouterAbonne(registre, abonne)
 *    Retourner un nouveau registre avec l'abonne ajoute.
 *    Generer un id unique (incrementiel ou timestamp).
 *    Verifier qu'un abonne avec le meme email n'existe pas deja (lancer une Error si doublon).
 *
 * 2. mettreAJourAbonne(registre, id, modifications)
 *    Retourner un nouveau registre avec l'abonne mis a jour.
 *    Seuls les champs fournis dans `modifications` sont modifies.
 *    Lancer une Error si l'id n'existe pas.
 *
 * 3. supprimerAbonne(registre, id)
 *    Retourner un nouveau registre sans l'abonne. Error si non trouve.
 *
 * 4. renouvelerAbonnements(registre, dateReference)
 *    Retourner un nouveau registre ou :
 *    - Les abonnements expires (dateExpiration < dateReference) sont marques statut='expire'
 *    - Les abonnements expirant dans moins de 7 jours ont un champ `alerteRenouvellement: true`
 *
 * 5. tableauDeBord(registre)
 *    Retourner :
 *    {
 *      totalAbonnes, actifs, expires, suspendu,
 *      revenuesMensuelsTotaux,   // somme des tarifs des abonnes actifs
 *      repartitionParFormule,    // { basic: N, premium: N, business: N }
 *      top3Villes                // les 3 villes avec le plus d'abonnes
 *    }
 */

let registre = [
  { id: 1, nom: 'Alami Hassan',   email: 'h.alami@email.ma',  ville: 'Casablanca', formule: 'premium', tarif: 99,  statut: 'actif',    dateExpiration: '2024-06-15' },
  { id: 2, nom: 'Benali Sara',    email: 's.benali@email.ma', ville: 'Rabat',      formule: 'basic',   tarif: 49,  statut: 'actif',    dateExpiration: '2024-12-01' },
  { id: 3, nom: 'Chraibi Omar',   email: 'o.chraibi@web.ma',  ville: 'Casablanca', formule: 'business',tarif: 199, statut: 'actif',    dateExpiration: '2024-04-01' },
  { id: 4, nom: 'Drissi Fatima',  email: 'f.drissi@web.ma',   ville: 'Fes',        formule: 'basic',   tarif: 49,  statut: 'suspendu', dateExpiration: '2024-03-20' },
  { id: 5, nom: 'Ennaji Youssef', email: 'y.ennaji@mail.ma',  ville: 'Casablanca', formule: 'premium', tarif: 99,  statut: 'actif',    dateExpiration: '2024-08-30' },
  { id: 6, nom: 'Fassi Leila',    email: 'l.fassi@mail.ma',   ville: 'Marrakech',  formule: 'premium', tarif: 99,  statut: 'actif',    dateExpiration: '2024-04-10' },
  { id: 7, nom: 'Ghazali Mehdi',  email: 'm.ghazali@pro.ma',  ville: 'Rabat',      formule: 'business',tarif: 199, statut: 'expire',   dateExpiration: '2024-02-28' },
  { id: 8, nom: 'Hamdaoui Nadia', email: 'n.hmdaoui@web.ma',  ville: 'Tanger',     formule: 'basic',   tarif: 49,  statut: 'actif',    dateExpiration: '2024-07-15' },
];
function ajouterAbonne(registre, abonne) {
  const existe = registre.some(r => r.email === abonne.email);
  if (existe) {
    throw new Error("Email deja existant");
  }
  const newId = Math.max(...registre.map(r => r.id)) + 1;
  const nouvelAbonne = {
    ...abonne,
    id: newId
  };
  return [...registre, nouvelAbonne];
}

function mettreAJourAbonne(registre, id, modifications) {
  const existe = registre.some(a => a.id === id);
  if (!existe) {
    throw new Error("Abonne introuvable");
  }

  return registre.map(a => 
    a.id === id ? { ...a, ...modifications } : a
  );
}

function supprimerAbonne(registre, id) {
  const existe = registre.some(a => a.id === id);
  if (!existe) {
    throw new Error("Abonne introuvable");
  }

  return registre.filter(a => a.id !== id);
}

function renouvelerAbonnements(registre, dateReference) {
  // TODO
}

function tableauDeBord(registre) {
  // TODO
}

// Tests
const r1 = ajouterAbonne(registre, { nom: 'Test User', email: 'test@test.ma', ville: 'Agadir', formule: 'basic', tarif: 49, statut: 'actif', dateExpiration: '2024-09-01' });
console.log('Nouveau registre:', r1.length); // 9

const r2 = mettreAJourAbonne(registre, 3, { formule: 'premium', tarif: 99 });
console.log('Mis a jour:', r2.find(a => a.id === 3));

const r3 = renouvelerAbonnements(registre, '2024-04-05');
console.log('Apres renouvellement:', r3.filter(a => a.alerteRenouvellement || a.statut === 'expire').map(a => a.nom));

console.log('Tableau de bord:', tableauDeBord(registre));