/**
 * EXERCICE 3 - Systeme de tournoi et classement
 *
 * Contexte :
 * Vous developpez le module de gestion des resultats d'une ligue de football
 * regionale. Le systeme doit calculer automatiquement les classements selon
 * les regles officielles.
 *
 * Regles de calcul :
 * - Victoire : 3 points
 * - Match nul : 1 point
 * - Defaite : 0 points
 * - Egalite de points : departager par difference de buts, puis buts marques,
 *   puis confrontation directe, puis ordre alphabetique
 *
 * Travail demande :
 *
 * 1. calculerClassement(matchs)
 *    A partir du tableau de matchs joues, retourner le classement complet.
 *    Chaque entree du classement contient :
 *    { rang, equipe, joues, victoires, nuls, defaites, bpour, bcontre, diff, points }
 *
 * 2. meilleureAttaque(classement)
 *    Retourner l'equipe ayant marque le plus de buts (objet complet du classement).
 *
 * 3. meilleureDefense(classement)
 *    Retourner l'equipe ayant encaisse le moins de buts.
 *
 * 4. serieInvaincue(matchs, equipe)
 *    Retourner le nombre de matchs consecutifs sans defaite (en partant du match le plus recent).
 */

const matchs = [
  { journee: 1, domicile: 'FUS Rabat',     bDomicile: 2, bExterieur: 1, exterieur: 'WAC'         },
  { journee: 1, domicile: 'Raja',          bDomicile: 1, bExterieur: 1, exterieur: 'MAS'         },
  { journee: 1, domicile: 'FAR',           bDomicile: 3, bExterieur: 0, exterieur: 'HUSA'        },
  { journee: 2, domicile: 'WAC',           bDomicile: 2, bExterieur: 2, exterieur: 'Raja'        },
  { journee: 2, domicile: 'MAS',           bDomicile: 1, bExterieur: 0, exterieur: 'FAR'         },
  { journee: 2, domicile: 'HUSA',          bDomicile: 1, bExterieur: 3, exterieur: 'FUS Rabat'   },
  { journee: 3, domicile: 'Raja',          bDomicile: 2, bExterieur: 0, exterieur: 'FAR'         },
  { journee: 3, domicile: 'FUS Rabat',     bDomicile: 1, bExterieur: 1, exterieur: 'MAS'         },
  { journee: 3, domicile: 'WAC',           bDomicile: 4, bExterieur: 1, exterieur: 'HUSA'        },
  { journee: 4, domicile: 'FAR',           bDomicile: 2, bExterieur: 2, exterieur: 'WAC'         },
  { journee: 4, domicile: 'MAS',           bDomicile: 0, bExterieur: 1, exterieur: 'FUS Rabat'   },
  { journee: 4, domicile: 'HUSA',          bDomicile: 2, bExterieur: 3, exterieur: 'Raja'        },
];

function calculerClassement(matchs) {
  // TODO
}

function meilleureAttaque(classement) {
  // TODO
}

function meilleureDefense(classement) {
  // TODO
}

function serieInvaincue(matchs, equipe) {
  // TODO
}

const classement = calculerClassement(matchs);
console.log('--- Classement ---');
classement.forEach(e => console.log(
  `${e.rang}. ${e.equipe.padEnd(12)} | J:${e.joues} V:${e.victoires} N:${e.nuls} D:${e.defaites} | ${e.bpour}:${e.bcontre} (${e.diff > 0 ? '+' : ''}${e.diff}) | ${e.points} pts`
));
console.log('Meilleure attaque:', meilleureAttaque(classement).equipe);
console.log('Meilleure defense:', meilleureDefense(classement).equipe);
console.log('Serie WAC:', serieInvaincue(matchs, 'WAC'));