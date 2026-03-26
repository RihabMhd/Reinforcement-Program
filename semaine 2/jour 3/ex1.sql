CREATE TABLE agence (
    id_agence INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    adresse TEXT,
    horaires VARCHAR(255)
);

CREATE TABLE agent (
    id_agent INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50),
    id_agence INT,
    FOREIGN KEY (id_agence) REFERENCES agence(id_agence)
);

CREATE TABLE client (
    id_client INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100),
    pref_budget_min DECIMAL(15,2),
    pref_budget_max DECIMAL(15,2),
    pref_type_bien VARCHAR(50)
);

CREATE TABLE bien (
    id_bien INT PRIMARY KEY AUTO_INCREMENT,
    type_bien ENUM('appartement', 'villa', 'terrain') NOT NULL,
    superficie FLOAT,
    adresse TEXT,
    prix_base DECIMAL(15,2),
    statut ENUM('disponible', 'vendu', 'loue') DEFAULT 'disponible',
    id_proprietaire INT,
    id_agent INT,
    FOREIGN KEY (id_proprietaire) REFERENCES client(id_client),
    FOREIGN KEY (id_agent) REFERENCES agent(id_agent)
);


CREATE TABLE appartement (
    id_bien INT PRIMARY KEY,
    etage INT,
    nb_pieces INT,
    ascenseur BOOLEAN,
    FOREIGN KEY (id_bien) REFERENCES bien(id_bien)
);

CREATE TABLE villa (
    id_bien INT PRIMARY KEY,
    nb_niveaux INT,
    superficie_jardin FLOAT,
    piscine BOOLEAN,
    FOREIGN KEY (id_bien) REFERENCES bien(id_bien)
);

CREATE TABLE transaction (
    id_transaction INT PRIMARY KEY AUTO_INCREMENT,
    id_bien INT,
    id_acheteur INT,
    id_agent INT,
    date_signature DATE,
    commission DECIMAL(10,2),
    type_trans ENUM('vente', 'location'),
    FOREIGN KEY (id_bien) REFERENCES bien(id_bien),
    FOREIGN KEY (id_acheteur) REFERENCES client(id_client),
    FOREIGN KEY (id_agent) REFERENCES agent(id_agent)
);

CREATE TABLE vente (
    id_transaction INT PRIMARY KEY,
    prix_final DECIMAL(15,2),
    notaire VARCHAR(100),
    FOREIGN KEY (id_transaction) REFERENCES transaction(id_transaction)
);