/**
 * EXERCICE 3 - Analyse de donnees logistiques
 *
 * Contexte :
 * Une entreprise de livraison vous confie l'analyse de ses donnees operationnelles.
 * Vous devez produire des indicateurs de performance (KPIs) pour la direction.
 *
 * Travail demande :
 *
 * 1. kpiLivraisons(livraisons)
 *    Retourner :
 *    {
 *      totalLivraisons,
 *      tauxReussite,          // % livraisonsStatut 'livre' / total
 *      delaiMoyenLivraison,   // en heures, arrondi a 1 decimale
 *      livraisonsEnRetard,    // count des livraisons ou delaiReel > delaiPrevu
 *      tauxPonctualite        // % livraisons dans les delais
 *    }
 *
 * 2. performanceParLivreur(livraisons)
 *    Pour chaque livreur, calculer :
 *    { livreurId, nom, totalLivraisons, reussies, tauxReussite, delaiMoyen, rating }
 *    Trier par rating decroissant (ou tauxReussite si rating egal).
 *
 * 3. heatmapGeographique(livraisons)
 *    Regrouper par ville :
 *    { ville, total, reussies, echecs, enAttente, delaiMoyen }
 *    Trier par total decroissant.
 *
 * 4. anomaliesDelais(livraisons)
 *    Retourner les livraisons dont le delai reel depasse 2x le delai prevu.
 *    Inclure un champ `depassement` = delaiReel - delaiPrevu (en heures).
 */

const livraisons = [
  { id: 'LIV001', livreurId: 'L1', livreur: 'Rachid B.', ville: 'Casablanca', statut: 'livre',    delaiPrevu: 2, delaiReel: 2.5, rating: 4.5 },
  { id: 'LIV002', livreurId: 'L2', livreur: 'Amine K.',  ville: 'Rabat',      statut: 'livre',    delaiPrevu: 3, delaiReel: 2.8, rating: 5.0 },
  { id: 'LIV003', livreurId: 'L1', livreur: 'Rachid B.', ville: 'Casablanca', statut: 'echec',    delaiPrevu: 2, delaiReel: 3.5, rating: 2.0 },
  { id: 'LIV004', livreurId: 'L3', livreur: 'Khalid M.', ville: 'Marrakech',  statut: 'livre',    delaiPrevu: 4, delaiReel: 4.1, rating: 4.8 },
  { id: 'LIV005', livreurId: 'L2', livreur: 'Amine K.',  ville: 'Casablanca', statut: 'livre',    delaiPrevu: 2, delaiReel: 2.2, rating: 4.7 },
  { id: 'LIV006', livreurId: 'L3', livreur: 'Khalid M.', ville: 'Marrakech',  statut: 'echec',    delaiPrevu: 4, delaiReel: 9.0, rating: 1.5 },
  { id: 'LIV007', livreurId: 'L1', livreur: 'Rachid B.', ville: 'Rabat',      statut: 'en_route', delaiPrevu: 3, delaiReel: null,rating: null},
  { id: 'LIV008', livreurId: 'L4', livreur: 'Samir A.',  ville: 'Fes',        statut: 'livre',    delaiPrevu: 5, delaiReel: 4.8, rating: 4.9 },
  { id: 'LIV009', livreurId: 'L2', livreur: 'Amine K.',  ville: 'Fes',        statut: 'livre',    delaiPrevu: 5, delaiReel: 11.0,rating: 2.5 },
  { id: 'LIV010', livreurId: 'L4', livreur: 'Samir A.',  ville: 'Casablanca', statut: 'livre',    delaiPrevu: 2, delaiReel: 2.1, rating: 5.0 },
];

function kpiLivraisons(livraisons) {
  // TODO
}

function performanceParLivreur(livraisons) {
  // TODO
}

function heatmapGeographique(livraisons) {
  // TODO
}

function anomaliesDelais(livraisons) {
  // TODO
}

// Tests
console.log('KPIs:', kpiLivraisons(livraisons));
console.log('Performance livreurs:');
performanceParLivreur(livraisons).forEach(l => console.log(l.nom, l.tauxReussite + '%', l.delaiMoyen + 'h'));
console.log('Heatmap:', heatmapGeographique(livraisons));
console.log('Anomalies:', anomaliesDelais(livraisons));