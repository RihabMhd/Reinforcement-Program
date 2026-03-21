/**
 * EXERCICE 2 - Systeme de suivi de projet agile
 *
 * Contexte :
 * Vous integrez une equipe qui developpe un outil de gestion de projet.
 * Les sprints contiennent des user stories, chaque user story peut avoir
 * des sous-taches et des commentaires. Vous devez implanter des fonctions
 * d'analyse et de reporting.
 *
 * Travail demande :
 *
 * 1. chargerSprint(sprints, idSprint)
 *    Retourner le sprint complet avec un champ calcule `completion` (% de stories done).
 *
 * 2. storiesBloquees(sprints)
 *    Retourner toutes les stories avec statut 'bloque' de tous les sprints actifs,
 *    enrichies avec { nomSprint, idSprint }.
 *
 * 3. velociteParSprint(sprints)
 *    La velocite = somme des points des stories 'done'.
 *    Retourner [{ idSprint, nom, velocite, storiesDone, totalStories }]
 *
 * 4. rechercherStory(sprints, motCle)
 *    Chercher dans le titre ET la description des stories (insensible a la casse).
 *    Retourner les stories trouvees avec { idSprint, nomSprint, story }
 *
 * 5. tachesSansResponsable(sprints)
 *    Retourner toutes les sous-taches sans assignee,
 *    avec { storyId, storyTitre, tache }.
 */

const sprints = [
  {
    id: 'SP1', nom: 'Sprint 1 - Auth', actif: false,
    stories: [
      {
        id: 'US1', titre: 'Inscription utilisateur', points: 5, statut: 'done',
        description: 'En tant que visiteur, je peux creer un compte.',
        taches: [
          { id: 'T1', titre: 'Form HTML', assignee: 'Karim', fait: true },
          { id: 'T2', titre: 'Validation PHP', assignee: 'Sara', fait: true },
        ]
      },
      {
        id: 'US2', titre: 'Connexion JWT', points: 8, statut: 'done',
        description: 'Authentification via token JWT.',
        taches: [
          { id: 'T3', titre: 'Route login', assignee: 'Karim', fait: true },
          { id: 'T4', titre: 'Middleware auth', assignee: null, fait: true },
        ]
      },
    ]
  },
  {
    id: 'SP2', nom: 'Sprint 2 - Dashboard', actif: true,
    stories: [
      {
        id: 'US3', titre: 'Tableau de bord admin', points: 13, statut: 'en_cours',
        description: 'Afficher les statistiques principales.',
        taches: [
          { id: 'T5', titre: 'API statistiques', assignee: 'Omar', fait: true  },
          { id: 'T6', titre: 'Composant graphique', assignee: null, fait: false },
          { id: 'T7', titre: 'Tests unitaires', assignee: null, fait: false },
        ]
      },
      {
        id: 'US4', titre: 'Notifications temps reel', points: 8, statut: 'bloque',
        description: 'WebSocket pour les notifications utilisateur.',
        taches: [
          { id: 'T8', titre: 'Config WebSocket', assignee: 'Karim', fait: false },
        ]
      },
      {
        id: 'US5', titre: 'Export rapport PDF', points: 5, statut: 'a_faire',
        description: 'Generer un rapport en PDF via une librairie.',
        taches: [
          { id: 'T9', titre: 'Integration librairie PDF', assignee: null, fait: false },
        ]
      },
    ]
  },
  {
    id: 'SP3', nom: 'Sprint 3 - Mobile', actif: true,
    stories: [
      {
        id: 'US6', titre: 'Vue mobile du dashboard', points: 8, statut: 'en_cours',
        description: 'Adapter le tableau de bord pour mobile.',
        taches: [
          { id: 'T10', titre: 'Responsive CSS', assignee: 'Sara', fait: true },
          { id: 'T11', titre: 'Tests sur appareils', assignee: null, fait: false },
        ]
      },
      {
        id: 'US7', titre: 'Notifications push mobile', points: 13, statut: 'bloque',
        description: 'Integrer Firebase pour les push notifications.',
        taches: [
          { id: 'T12', titre: 'Config Firebase', assignee: null, fait: false },
        ]
      },
    ]
  }
];

function chargerSprint(sprints, idSprint) {
  // TODO
}

function storiesBloquees(sprints) {
  // TODO
}

function velociteParSprint(sprints) {
  // TODO
}

function rechercherStory(sprints, motCle) {
  // TODO
}

function tachesSansResponsable(sprints) {
  // TODO
}

// Tests
console.log(chargerSprint(sprints, 'SP2'));
console.log('Bloquees:', storiesBloquees(sprints));
console.log('Velocite:', velociteParSprint(sprints));
console.log('Recherche:', rechercherStory(sprints, 'mobile'));
console.log('Sans responsable:', tachesSansResponsable(sprints));