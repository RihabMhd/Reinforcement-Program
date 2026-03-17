/**
 * EXERCICE 1 - Traitement d'un journal de commandes
 *
 * Contexte :
 * Vous etes developpeur dans une startup logistique. Vous recevez chaque jour
 * un tableau de commandes issues de l'API. Vous devez extraire des informations
 * utiles pour le tableau de bord du responsable operations.
 *
 * Travail demande :
 *
 * 1. filtrerParStatut(commandes, statut)
 *    Retourner uniquement les commandes ayant le statut donne.
 *
 * 2. calculerChiffreAffaires(commandes)
 *    Retourner la somme totale des montants de toutes les commandes.
 *
 * 3. commandeLaPlusElevee(commandes)
 *    Retourner l'objet commande avec le montant le plus eleve.
 *
 * 4. marquerPrioritaires(commandes)
 *    Retourner un nouveau tableau ou chaque commande a un champ `prioritaire: true`
 *    si son montant > 800 ET son statut est 'en_attente'.
 *
 * 5. resumeParStatut(commandes)
 *    Retourner un objet du type :
 *    { en_attente: { count: N, total: X }, livree: { count: N, total: X }, ... }
 */

const commandes = [
    { id: 'CMD-001', client: 'Alami Hassan', montant: 450, statut: 'livree', ville: 'Casablanca' },
    { id: 'CMD-002', client: 'Benali Sara', montant: 1200, statut: 'en_attente', ville: 'Rabat' },
    { id: 'CMD-003', client: 'Chraibi Omar', montant: 320, statut: 'en_cours', ville: 'Marrakech' },
    { id: 'CMD-004', client: 'Drissi Fatima', montant: 875, statut: 'en_attente', ville: 'Fes' },
    { id: 'CMD-005', client: 'El Amrani Youssef', montant: 95, statut: 'annulee', ville: 'Casablanca' },
    { id: 'CMD-006', client: 'Fassi Leila', montant: 2100, statut: 'livree', ville: 'Tanger' },
    { id: 'CMD-007', client: 'Ghazali Mehdi', montant: 560, statut: 'en_attente', ville: 'Agadir' },
    { id: 'CMD-008', client: 'Hamdaoui Nadia', montant: 430, statut: 'en_cours', ville: 'Casablanca' },
];

// --- Vos implementations ---

function filtrerParStatut(commandes, statut) {
    return commandes.filter(cmd => cmd.statut === statut);
}

function calculerChiffreAffaires(commandes) {
    return commandes.reduce((total, cmd) => total + cmd.montant, 0);
}

function commandeLaPlusElevee(commandes) {
    return commandes.reduce((max, cmd) => {
        return cmd.montant > max.montant ? cmd : max;
    })
}

function marquerPrioritaires(commandes) {
    return commandes.map(cmd => ({
        ...cmd,
        prioritaire: cmd.montant > 800 && cmd.statut === 'en_attente'
    }));
}

function resumeParStatut(commandes) {
    return commandes.reduce((acc, cmd) => {
        if (!acc[cmd.statut]) {
            acc[cmd.statut] = { count: 0, total: 0 };
        }

        acc[cmd.statut].count++;
        acc[cmd.total]+=cmd.montant;
        return acc
    },{})
}

// --- Tests ---
console.log('--- filtrerParStatut ---');
console.log(filtrerParStatut(commandes, 'en_attente'));

console.log('--- calculerChiffreAffaires ---');
console.log(calculerChiffreAffaires(commandes)); // 6030

console.log('--- commandeLaPlusElevee ---');
console.log(commandeLaPlusElevee(commandes)); // CMD-006

console.log('--- marquerPrioritaires ---');
console.log(marquerPrioritaires(commandes));

console.log('--- resumeParStatut ---');
console.log(resumeParStatut(commandes));