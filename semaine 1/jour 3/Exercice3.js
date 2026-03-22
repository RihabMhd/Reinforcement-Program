/**
 * EXERCICE 3 - Systeme de gestion des notes etudiantes
 *
 * Contexte :
 * Vous developpez le module de calcul et d'affichage des resultats
 * d'un etablissement de formation. Les regles de calcul sont precises
 * et doivent etre implementees rigoureusement.
 *
 * Regles de l'etablissement :
 * - Chaque module a un coefficient
 * - La moyenne generale = somme(note * coeff) / somme(coeff)
 * - Mention : TB >= 16, B >= 14, AB >= 12, P >= 10, Echec < 10
 * - Rattrapage possible si 8 <= moyenne < 10
 * - Exclusion si une note de module < 5 (note eliminatoire)
 *
 * Travail demande :
 *
 * 1. calculerMoyenne(etudiant)
 *    Retourner la moyenne ponderee de l'etudiant (arrondie a 2 decimales).
 *
 * 2. determinerStatut(etudiant)
 *    Retourner { moyenne, mention, statut, modulesEliminatoires }
 *    statut : 'admis', 'rattrapage', 'exclu', 'elimine'
 *    elimine = a une note eliminatoire (< 5)
 *
 * 3. classementPromotion(etudiants)
 *    Retourner le tableau trie par moyenne decroissante avec rang.
 *
 * 4. statistiquesModule(etudiants, nomModule)
 *    Pour un module donne, retourner :
 *    { module, moyenne, min, max, tauxReussite, distribution }
 *    distribution : { '<10': N, '10-12': N, '12-14': N, '14-16': N, '>=16': N }
 *
 * 5. etudiantsARisque(etudiants)
 *    Retourner les etudiants ayant au moins 2 modules en dessous de 10.
 *    Inclure la liste de leurs modules en difficulte.
 */

const etudiants = [
  {
    id: 'ETU01', nom: 'Alami', prenom: 'Karim',
    notes: [
      { module: 'Algorithmique', note: 14, coeff: 4 },
      { module: 'PHP POO', note: 12, coeff: 4 },
      { module: 'JavaScript', note: 16, coeff: 3 },
      { module: 'BDD SQL', note: 11, coeff: 3 },
      { module: 'HTML/CSS', note: 15, coeff: 2 },
      { module: 'Projet', note: 13, coeff: 4 },
    ]
  },
  {
    id: 'ETU02', nom: 'Benali', prenom: 'Sara',
    notes: [
      { module: 'Algorithmique', note: 7, coeff: 4 },
      { module: 'PHP POO', note: 9, coeff: 4 },
      { module: 'JavaScript', note: 11, coeff: 3 },
      { module: 'BDD SQL', note: 8, coeff: 3 },
      { module: 'HTML/CSS', note: 14, coeff: 2 },
      { module: 'Projet', note: 10, coeff: 4 },
    ]
  },
  {
    id: 'ETU03', nom: 'Chraibi', prenom: 'Omar',
    notes: [
      { module: 'Algorithmique', note: 4, coeff: 4 },
      { module: 'PHP POO', note: 15, coeff: 4 },
      { module: 'JavaScript', note: 13, coeff: 3 },
      { module: 'BDD SQL', note: 16, coeff: 3 },
      { module: 'HTML/CSS', note: 18, coeff: 2 },
      { module: 'Projet', note: 14, coeff: 4 },
    ]
  },
  {
    id: 'ETU04', nom: 'Drissi', prenom: 'Fatima',
    notes: [
      { module: 'Algorithmique', note: 17, coeff: 4 },
      { module: 'PHP POO', note: 18, coeff: 4 },
      { module: 'JavaScript', note: 16, coeff: 3 },
      { module: 'BDD SQL', note: 19, coeff: 3 },
      { module: 'HTML/CSS', note: 17, coeff: 2 },
      { module: 'Projet', note: 18, coeff: 4 },
    ]
  },
  {
    id: 'ETU05', nom: 'Ennaji', prenom: 'Youssef',
    notes: [
      { module: 'Algorithmique', note: 6, coeff: 4 },
      { module: 'PHP POO', note: 7, coeff: 4 },
      { module: 'JavaScript', note: 9, coeff: 3 },
      { module: 'BDD SQL', note: 5, coeff: 3 },
      { module: 'HTML/CSS', note: 12, coeff: 2 },
      { module: 'Projet', note: 8, coeff: 4 },
    ]
  },
];
/*La moyenne generale = somme(note * coeff) / somme(coeff)*/
function calculerMoyenne(etudiant) {
  const etu = etudiants.find(e => e.id === etudiant);
  let total = 0;
  let totalCoeff = 0;
  etu.notes.forEach(n => {
    total += n.note * n.coeff;
    totalCoeff += n.coeff;
  });
  return total / totalCoeff;

}
// - Mention : TB >= 16, B >= 14, AB >= 12, P >= 10, Echec < 10
function determinerStatut(idEtudiant) {
  const etu = etudiants.find(e => e.id === idEtudiant);
  const moyenne = calculerMoyenne(idEtudiant);

  const modulesEliminatoires = etu.notes
    .filter(n => n.note < 5)
    .map(n => n.module);

  let mention;
  if (moyenne >= 16) mention = 'TB';
  else if (moyenne >= 14) mention = 'B';
  else if (moyenne >= 12) mention = 'AB';
  else if (moyenne >= 10) mention = 'P';
  else mention = 'Echec';
  let statut;
  if (modulesEliminatoires.length > 0) {
    statut = 'elimine';
  } else if (moyenne < 8) {
    statut = 'exclu';
  } else if (moyenne < 10) {
    statut = 'rattrapage';
  } else {
    statut = 'admis';
  }

  return {
    moyenne: Number(moyenne.toFixed(2)),
    mention,
    statut,
    modulesEliminatoires
  };
}
/* 3. classementPromotion(etudiants)
 *    Retourner le tableau trie par moyenne decroissante avec rang. */
function classementPromotion(etudiants) {
  const liste = etudiants.map(e => {
    const statut = determinerStatut(e.id);

    return {
      id: e.id,
      nom: e.nom,
      prenom: e.prenom,
      moyenne: statut.moyenne
    };
  });

  liste.sort((a, b) => b.moyenne - a.moyenne);

  return liste.map((e, index) => ({
    ...e,
    rang: index + 1
  }));
}

function statistiquesModule(etudiants, nomModule) {
  // TODO
}

function etudiantsARisque(etudiants) {
  // TODO
}

// Tests
console.log('Statut ETU02:', determinerStatut(etudiants[1]));
console.log('Statut ETU03 (eliminatoire):', determinerStatut(etudiants[2]));
console.log('Classement:');
classementPromotion(etudiants).forEach(e => console.log(e.rang, e.nom, e.moyenne, e.mention));
console.log('Stats PHP POO:', statistiquesModule(etudiants, 'PHP POO'));
console.log('A risque:', etudiantsARisque(etudiants).map(e => e.nom));