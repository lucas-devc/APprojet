-- ============================================================
--  Script d'installation – Mairie de Francilly-Selency
--  Exécuter ce fichier une seule fois pour créer la BDD
-- ============================================================

CREATE DATABASE IF NOT EXISTS francilly_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE francilly_db;

-- ----------------------------------------------------------
-- TABLE 1 : articles  (actualités / infos de la mairie)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS articles (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(255)  NOT NULL,
    contenu     TEXT          NOT NULL,
    categorie   VARCHAR(100)  NOT NULL DEFAULT 'Général',
    date_pub    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    visible     TINYINT(1)    NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO articles (titre, contenu, categorie) VALUES
('Conseil municipal – juin 2025',
 'Le prochain conseil municipal se tiendra le 10 juin 2025 à 19h00 en mairie. Ordre du jour : budget, voirie, projets communaux.',
 'Vie municipale'),
('Travaux rue de la Paix',
 'Des travaux d\'enfouissement des réseaux électriques débuteront le 15 mai 2025. Circulation alternée prévue pendant 3 semaines.',
 'Travaux'),
('Fête du village – 14 juillet',
 'La commune vous invite à célébrer la Fête Nationale le 14 juillet : feu d\'artifice à 22h30, bal populaire jusqu\'à minuit.',
 'Événements'),
('Ramassage des encombrants',
 'Le ramassage des encombrants aura lieu le samedi 7 juin 2025. Dépôt possible sur le parking de la salle des fêtes la veille.',
 'Environnement'),
('Inscription école primaire 2025-2026',
 'Les inscriptions pour la rentrée 2025-2026 sont ouvertes du 2 au 30 juin. Munissez-vous du carnet de santé et d\'un justificatif de domicile.',
 'École');

-- ----------------------------------------------------------
-- TABLE 2 : services  (services proposés par la mairie)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS services (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(255)  NOT NULL,
    description TEXT          NOT NULL,
    icone       VARCHAR(50)   NOT NULL DEFAULT '',
    categorie   VARCHAR(100)  NOT NULL DEFAULT 'Administratif',
    actif       TINYINT(1)    NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO services (nom, description, icone, categorie) VALUES
('État civil',
 'Délivrance des actes de naissance, mariage, décès. Demande en ligne ou sur rendez-vous en mairie.',
 '', 'Administratif'),
('Urbanisme & permis',
 'Dépôt des demandes de permis de construire, déclarations préalables, certificats d\'urbanisme.',
 '', 'Urbanisme'),
('Cantine scolaire',
 'Inscription et règlement des repas de la cantine. Menus disponibles chaque semaine sur le tableau d\'affichage.',
 '', 'Enfance'),
('Bibliothèque',
 'La bibliothèque municipale est ouverte le mercredi de 14h à 17h. Prêt gratuit pour tous les habitants.',
 '', 'Culture'),
('Collecte des déchets',
 'Calendrier de collecte des poubelles jaunes (recyclables) et grises (ordures ménagères). Déchetterie à Saint-Quentin.',
 '', 'Environnement'),
('Salle des fêtes',
 'Location de la salle des fêtes pour les associations et particuliers. Capacité : 120 personnes. Tarifs sur demande.',
 '', 'Culture');

-- ----------------------------------------------------------
-- TABLE 3 : contacts  (messages envoyés via le formulaire)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS contacts (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    nom         VARCHAR(150)  NOT NULL,
    email       VARCHAR(255)  NOT NULL,
    sujet       VARCHAR(255)  NOT NULL,
    message     TEXT          NOT NULL,
    date_envoi  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    lu          TINYINT(1)    NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
