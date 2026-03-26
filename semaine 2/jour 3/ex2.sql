CREATE TABLE utilisateurs (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    email VARCHAR(150) UNIQUE NOT NULL,
    password_hash VARCHAR(255),
    is_instructeur BOOLEAN DEFAULT FALSE,
    is_apprenant BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cours (
    id_cours INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    prix DECIMAL(10,2) DEFAULT 0.00,
    niveau ENUM('debutant', 'intermediaire', 'avance'),
    id_instructeur INT,
    FOREIGN KEY (id_instructeur) REFERENCES utilisateurs(id_user),
    INDEX (id_instructeur) 
);

CREATE TABLE cours_prerequis (
    id_cours INT,
    id_prerequis INT,
    PRIMARY KEY (id_cours, id_prerequis),
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours),
    FOREIGN KEY (id_prerequis) REFERENCES cours(id_cours)
);

-- 3. Structure pédagogique
CREATE TABLE sections (
    id_section INT PRIMARY KEY AUTO_INCREMENT,
    id_cours INT,
    titre VARCHAR(255),
    ordre INT,
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours) ON DELETE CASCADE
);

CREATE TABLE lecons (
    id_lecon INT PRIMARY KEY AUTO_INCREMENT,
    id_section INT,
    titre VARCHAR(255),
    type_lecon ENUM('video', 'article', 'quiz', 'exercice'),
    contenu_media VARCHAR(255), -- URL ou Path
    ordre INT,
    FOREIGN KEY (id_section) REFERENCES sections(id_section) ON DELETE CASCADE,
    INDEX (id_section)
);

CREATE TABLE inscriptions (
    id_inscription INT PRIMARY KEY AUTO_INCREMENT,
    id_apprenant INT,
    id_cours INT,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    certificat_genere BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_apprenant) REFERENCES utilisateurs(id_user),
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours),
    UNIQUE(id_apprenant, id_cours) -- Un seul achat par cours
);

CREATE TABLE progression_lecons (
    id_inscription INT,
    id_lecon INT,
    statut ENUM('non_commence', 'en_cours', 'termine') DEFAULT 'non_commence',
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id_inscription, id_lecon),
    FOREIGN KEY (id_inscription) REFERENCES inscriptions(id_inscription),
    FOREIGN KEY (id_lecon) REFERENCES lecons(id_lecon)
);

CREATE TABLE avis (
    id_avis INT PRIMARY KEY AUTO_INCREMENT,
    id_cours INT,
    id_apprenant INT,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    signale BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_cours) REFERENCES cours(id_cours),
    FOREIGN KEY (id_apprenant) REFERENCES utilisateurs(id_user)
);

CREATE TABLE paiements (
    id_paiement INT PRIMARY KEY AUTO_INCREMENT,
    id_inscription INT,
    montant_total DECIMAL(10,2),
    part_instructeur DECIMAL(10,2), -- 70%
    part_plateforme DECIMAL(10,2), -- 30%
    date_paiement DATETIME,
    FOREIGN KEY (id_inscription) REFERENCES inscriptions(id_inscription)
);