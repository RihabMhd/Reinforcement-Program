-- Schema du systeme scolaire
-- A executer avant les exercices

CREATE TABLE IF NOT EXISTS promotions (
    id INT PRIMARY KEY,
    nom VARCHAR(100),
    date_debut DATE,
    date_fin DATE,
    formation VARCHAR(50)
);

CREATE TABLE IF NOT EXISTS formateurs (
    id INT PRIMARY KEY,
    nom VARCHAR(100),
    specialite VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS modules (
    id INT PRIMARY KEY,
    nom VARCHAR(100),
    coefficient INT,
    formateur_id INT,
    heures_totales INT,
    FOREIGN KEY (formateur_id) REFERENCES formateurs(id)
);

CREATE TABLE IF NOT EXISTS apprenants (
    id INT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    promotion_id INT,
    statut VARCHAR(20) DEFAULT 'actif',
    FOREIGN KEY (promotion_id) REFERENCES promotions(id)
);

CREATE TABLE IF NOT EXISTS presences (
    id INT PRIMARY KEY,
    apprenant_id INT,
    module_id INT,
    date_seance DATE,
    present BOOLEAN,
    FOREIGN KEY (apprenant_id) REFERENCES apprenants(id),
    FOREIGN KEY (module_id) REFERENCES modules(id)
);

CREATE TABLE IF NOT EXISTS notes (
    id INT PRIMARY KEY,
    apprenant_id INT,
    module_id INT,
    type_evaluation VARCHAR(50),
    note DECIMAL(5,2),
    date_eval DATE,
    FOREIGN KEY (apprenant_id) REFERENCES apprenants(id),
    FOREIGN KEY (module_id) REFERENCES modules(id)
);

INSERT INTO promotions VALUES
(1,'DWWM-2024-A','2024-01-08','2024-10-31','DWWM'),
(2,'DWWM-2024-B','2024-04-02','2025-01-31','DWWM'),
(3,'CDA-2024','2024-01-15','2024-12-20','CDA');

INSERT INTO formateurs VALUES
(1,'Alami Soufiane','PHP/Laravel, JavaScript'),
(2,'Benali Karim','Java, Spring Boot'),
(3,'Chraibi Omar','SQL, BDD'),
(4,'Drissi Sara','HTML/CSS, UX/UI');

INSERT INTO modules VALUES
(1,'PHP POO',4,1,40),(2,'JavaScript',3,1,30),(3,'SQL et BDD',3,3,30),
(4,'HTML/CSS',2,4,20),(5,'Algorithmique',4,2,40),(6,'Projet fil rouge',4,1,60);

INSERT INTO apprenants VALUES
(1,'Hassan Alami','h.alami@promo.ma',1,'actif'),
(2,'Sara Benali','s.benali@promo.ma',1,'actif'),
(3,'Omar Chraibi','o.chraibi@promo.ma',1,'actif'),
(4,'Fatima Drissi','f.drissi@promo.ma',1,'actif'),
(5,'Youssef Ennaji','y.ennaji@promo.ma',1,'abandonne'),
(6,'Leila Fassi','l.fassi@promo.ma',2,'actif'),
(7,'Mehdi Ghazali','m.ghazali@promo.ma',2,'actif'),
(8,'Nadia Hamdaoui','n.hamdaoui@promo.ma',2,'actif');

INSERT INTO notes VALUES
(1,1,1,'QCM',14,'2024-03-10'),(2,1,1,'Projet',16,'2024-03-25'),
(3,2,1,'QCM',8,'2024-03-10'),(4,2,1,'Projet',11,'2024-03-25'),
(5,3,1,'QCM',17,'2024-03-10'),(6,3,1,'Projet',15,'2024-03-25'),
(7,4,1,'QCM',12,'2024-03-10'),(8,4,1,'Projet',13,'2024-03-25'),
(9,1,2,'QCM',15,'2024-04-05'),(10,1,2,'Projet',14,'2024-04-20'),
(11,2,2,'QCM',9,'2024-04-05'),(12,2,2,'Projet',10,'2024-04-20'),
(13,3,2,'QCM',16,'2024-04-05'),(14,3,2,'Projet',17,'2024-04-20'),
(15,4,2,'QCM',13,'2024-04-05'),(16,4,2,'Projet',14,'2024-04-20'),
(17,1,3,'QCM',11,'2024-05-10'),(18,1,3,'Projet',12,'2024-05-25'),
(19,2,3,'QCM',7,'2024-05-10'),(20,2,3,'Projet',8,'2024-05-25'),
(21,3,3,'QCM',18,'2024-05-10'),(22,3,3,'Projet',19,'2024-05-25'),
(23,4,3,'QCM',10,'2024-05-10'),(24,4,3,'Projet',11,'2024-05-25');

INSERT INTO presences VALUES
(1,1,1,'2024-03-01',TRUE),(2,1,1,'2024-03-05',TRUE),(3,1,1,'2024-03-08',FALSE),
(4,2,1,'2024-03-01',TRUE),(5,2,1,'2024-03-05',FALSE),(6,2,1,'2024-03-08',FALSE),
(7,3,1,'2024-03-01',TRUE),(8,3,1,'2024-03-05',TRUE),(9,3,1,'2024-03-08',TRUE),
(10,4,1,'2024-03-01',TRUE),(11,4,1,'2024-03-05',TRUE),(12,4,1,'2024-03-08',FALSE),
(13,1,2,'2024-04-01',TRUE),(14,1,2,'2024-04-04',TRUE),(15,1,2,'2024-04-08',TRUE),
(16,2,2,'2024-04-01',FALSE),(17,2,2,'2024-04-04',FALSE),(18,2,2,'2024-04-08',TRUE),
(19,3,2,'2024-04-01',TRUE),(20,3,2,'2024-04-04',TRUE),(21,3,2,'2024-04-08',TRUE),
(22,4,2,'2024-04-01',TRUE),(23,4,2,'2024-04-04',FALSE),(24,4,2,'2024-04-08',TRUE);