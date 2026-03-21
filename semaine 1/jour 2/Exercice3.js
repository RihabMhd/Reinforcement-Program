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
  { journee: 1, domicile: 'FUS Rabat', bDomicile: 2, bExterieur: 1, exterieur: 'WAC' },
  { journee: 1, domicile: 'Raja', bDomicile: 1, bExterieur: 1, exterieur: 'MAS' },
  { journee: 1, domicile: 'FAR', bDomicile: 3, bExterieur: 0, exterieur: 'HUSA' },
  { journee: 2, domicile: 'WAC', bDomicile: 2, bExterieur: 2, exterieur: 'Raja' },
  { journee: 2, domicile: 'MAS', bDomicile: 1, bExterieur: 0, exterieur: 'FAR' },
  { journee: 2, domicile: 'HUSA', bDomicile: 1, bExterieur: 3, exterieur: 'FUS Rabat' },
  { journee: 3, domicile: 'Raja', bDomicile: 2, bExterieur: 0, exterieur: 'FAR' },
  { journee: 3, domicile: 'FUS Rabat', bDomicile: 1, bExterieur: 1, exterieur: 'MAS' },
  { journee: 3, domicile: 'WAC', bDomicile: 4, bExterieur: 1, exterieur: 'HUSA' },
  { journee: 4, domicile: 'FAR', bDomicile: 2, bExterieur: 2, exterieur: 'WAC' },
  { journee: 4, domicile: 'MAS', bDomicile: 0, bExterieur: 1, exterieur: 'FUS Rabat' },
  { journee: 4, domicile: 'HUSA', bDomicile: 2, bExterieur: 3, exterieur: 'Raja' },
];

function calculerClassement(matchs) {
  const equipes = {};

  function initEquipe(nom) {
    if (!equipes[nom]) {
      equipes[nom] = {
        equipe: nom,
        joues: 0,
        victoires: 0,
        nuls: 0,
        defaites: 0,
        bpour: 0,
        bcontre: 0,
        diff: 0,
        points: 0
      };
    }
  }

  matchs.forEach(m => {
    const { domicile, exterieur, bDomicile, bExterieur } = m;

    initEquipe(domicile);
    initEquipe(exterieur);

    const home = equipes[domicile];
    const away = equipes[exterieur];

    home.joues++;
    away.joues++;


    home.bpour += bDomicile;
    home.bcontre += bExterieur;

    away.bpour += bExterieur;
    away.bcontre += bDomicile;

    if (bDomicile > bExterieur) {
      home.victoires++;
      away.defaites++;
      home.points += 3;
    } else if (bDomicile < bExterieur) {
      away.victoires++;
      home.defaites++;
      away.points += 3;
    } else {
      home.nuls++;
      away.nuls++;
      home.points += 1;
      away.points += 1;
    }
  });

  Object.values(equipes).forEach(e => {
    e.diff = e.bpour - e.bcontre;
  });


  const classement = Object.values(equipes).sort((a, b) => {
    return (
      b.points - a.points ||
      b.diff - a.diff ||
      b.bpour - a.bpour ||
      a.equipe.localeCompare(b.equipe)
    );
  });

  classement.forEach((e, i) => {
    e.rang = i + 1;
  });

  return classement;
}

function meilleureAttaque(classement) {
  return classement.reduce((max, e) =>
    e.bpour > max.bpour ? e : max
  );
}

function meilleureDefense(classement) {
  return classement.reduce((best, e) =>
    e.bcontre < best.bcontre ? e : best
  );
}

function serieInvaincue(matchs, equipe) {
  const sorted = [...matchs].sort((a, b) => b.journee - a.journee);

  let count = 0;

  for (const m of sorted) {
    if (m.domicile !== equipe && m.exterieur !== equipe) continue;

    const estDomicile = m.domicile === equipe;
    const butsPour = estDomicile ? m.bDomicile : m.bExterieur;
    const butsContre = estDomicile ? m.bExterieur : m.bDomicile;

    if (butsPour >= butsContre) {
      count++;
    } else {
      break;
    }
  }

  return count;
}

const classement = calculerClassement(matchs);
console.log('--- Classement ---');
classement.forEach(e => console.log(
  `${e.rang}. ${e.equipe.padEnd(12)} | J:${e.joues} V:${e.victoires} N:${e.nuls} D:${e.defaites} | ${e.bpour}:${e.bcontre} (${e.diff > 0 ? '+' : ''}${e.diff}) | ${e.points} pts`
));
console.log('Meilleure attaque:', meilleureAttaque(classement).equipe);
console.log('Meilleure defense:', meilleureDefense(classement).equipe);
console.log('Serie WAC:', serieInvaincue(matchs, 'WAC'));