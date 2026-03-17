/**
 * EXERCICE 1 - Rapport de facturation mensuel
 *
 * Contexte :
 * Vous travaillez sur le module de reporting d'une plateforme SaaS.
 * Le service comptabilite a besoin d'un rapport mensuel automatique
 * genere a partir du journal des transactions.
 *
 * Travail demande :
 *
 * 1. rapportMensuel(transactions)
 *    Retourner un tableau trie par mois (format 'YYYY-MM') contenant pour chaque mois :
 *    { mois, nombreTransactions, totalHT, totalTVA, totalTTC, transactionMax }
 *    - totalTVA = totalHT * 0.20
 *    - totalTTC = totalHT + totalTVA
 *    - transactionMax : le montant le plus eleve du mois
 *
 * 2. top3Clients(transactions)
 *    Retourner les 3 clients ayant depense le plus au total (sur toute la periode).
 *    Format : [{ clientId, nom, total, nombreAchats }]
 *
 * 3. evolutionMensuelle(transactions)
 *    Retourner un tableau indiquant pour chaque mois (sauf le premier)
 *    le pourcentage d'evolution du CA vs le mois precedent.
 *    Format : [{ mois, totalHT, evolution }]
 *    evolution est un nombre arrondi a 1 decimale (ex: +12.3 ou -5.7)
 *
 * 4. detecterAnomalies(transactions)
 *    Une transaction est consideree anormale si son montant depasse
 *    2.5 fois la moyenne generale. Retourner ces transactions avec un champ
 *    `ecartMoyenne` indiquant le pourcentage de depassement (arrondi).
 */

const transactions = [
    { id: 'T001', clientId: 'C01', nom: 'Alami SA', montant: 1200, date: '2024-01-08' },
    { id: 'T002', clientId: 'C02', nom: 'Benali SARL', montant: 450, date: '2024-01-15' },
    { id: 'T003', clientId: 'C03', nom: 'Chraibi Corp', montant: 8900, date: '2024-01-22' },
    { id: 'T004', clientId: 'C01', nom: 'Alami SA', montant: 2300, date: '2024-02-05' },
    { id: 'T005', clientId: 'C04', nom: 'Drissi SARL', montant: 670, date: '2024-02-14' },
    { id: 'T006', clientId: 'C02', nom: 'Benali SARL', montant: 3100, date: '2024-02-20' },
    { id: 'T007', clientId: 'C05', nom: 'El Fassi Ltd', montant: 980, date: '2024-02-28' },
    { id: 'T008', clientId: 'C03', nom: 'Chraibi Corp', montant: 15000, date: '2024-03-03' },
    { id: 'T009', clientId: 'C01', nom: 'Alami SA', montant: 4200, date: '2024-03-11' },
    { id: 'T010', clientId: 'C04', nom: 'Drissi SARL', montant: 890, date: '2024-03-19' },
    { id: 'T011', clientId: 'C02', nom: 'Benali SARL', montant: 1750, date: '2024-03-25' },
    { id: 'T012', clientId: 'C05', nom: 'El Fassi Ltd', montant: 630, date: '2024-03-30' },
];
function getMonth(dateStr) {
    return dateStr.slice(0, 7);
}
function rapportMensuel(transactions) {
    return transactions.reduce((acc, t) => {
        const mois = getMonth(t.date);
        if (!acc[mois]) {
            acc[mois] = { mois, nombreTransactions: 0, totalHT: 0, totalTVA: 0, totalTTC: 0, transactionMax: 0 }
        }
        acc[mois].nombreTransactions++;
        acc[mois].totalHT += tontant;
        acc[mois].totalTVA += totalHT * 0.20;
        acc[mois].totalTTC += totalHT + totalTVA;
        acc[mois].transactionMax = Math.max(acc[mois].transactionMax, t.montant);
        return acc;

    }, {}).sort((a, b) => a.mois.localeCompare(b.mois));
}

function top3Clients(transactions) {
    return transactions.reduce((acc, t) => {
        if (!acc[t.clientId]) {
            acc[t.clientId] = { nom: t.nom, total: 0, nombreAchats: 0 };
        }
        acc[t.clientId].total += montant;
        acc[t.clientId].nombreAchats++;
        return acc;
    }, {}).sort((a, b) => a.total - b.total).limit(3);
}

function evolutionMensuelle(transactions) {

    const grouped = transactions.reduce((acc, t) => {
        const mois = getMonth(t.date);

        if (!acc[mois]) {
            acc[mois] = { mois, totalHT: 0 };
        }

        acc[mois].totalHT += t.montant;

        return acc;
    }, {});

    const sorted = Object.values(grouped)
        .sort((a, b) => a.mois.localeCompare(b.mois));

    const result = [];

    for (let i = 1; i < sorted.length; i++) {
        const current = sorted[i];
        const previous = sorted[i - 1];

        const evolution = ((current.totalHT - previous.totalHT) / previous.totalHT) * 100;

        result.push({
            mois: current.mois,
            totalHT: current.totalHT,
            evolution: Number(evolution.toFixed(1))
        });
    }

    return result;
}

function detecterAnomalies(transactions) {
    
}

// Tests
console.log('--- Rapport mensuel ---');
console.log(JSON.stringify(rapportMensuel(transactions), null, 2));

console.log('--- Top 3 clients ---');
console.log(top3Clients(transactions));

console.log('--- Evolution mensuelle ---');
console.log(evolutionMensuelle(transactions));

console.log('--- Anomalies ---');
console.log(detecterAnomalies(transactions));