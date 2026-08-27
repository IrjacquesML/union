-- =============================================================================
-- UCO — Union du Congo Ouest des Adventistes du 7e Jour (Kinshasa)
-- Script SQL : création de la base de données (Étape 1)
-- MySQL 8+ / InnoDB / utf8mb4
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS uco_website
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE uco_website;

-- -----------------------------------------------------------------------------
-- 1. Administration & sécurité
-- -----------------------------------------------------------------------------

CREATE TABLE roles (
    id          TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(50)  NOT NULL UNIQUE,   -- super_admin, editor, moderator
    label       VARCHAR(100) NOT NULL,
    description VARCHAR(255) NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE users (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role_id         TINYINT UNSIGNED NOT NULL,
    email           VARCHAR(190) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,      -- password_hash() PHP
    first_name      VARCHAR(100) NOT NULL,
    last_name       VARCHAR(100) NOT NULL,
    is_active       TINYINT(1) NOT NULL DEFAULT 1,
    last_login_at   DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_users_role
        FOREIGN KEY (role_id) REFERENCES roles(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE csrf_tokens (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id    INT UNSIGNED NULL,
    token      CHAR(64) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_csrf_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED NULL,
    action      VARCHAR(50)  NOT NULL,          -- create, update, delete, login...
    entity_type VARCHAR(80)  NOT NULL,          -- leaders, departments...
    entity_id   INT UNSIGNED NULL,
    old_values  JSON NULL,
    new_values  JSON NULL,
    ip_address  VARCHAR(45) NULL,
    user_agent  VARCHAR(255) NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_audit_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_audit_entity (entity_type, entity_id),
    INDEX idx_audit_created (created_at)
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- 2. Contenu institutionnel (présentation de l'Église)
-- -----------------------------------------------------------------------------

CREATE TABLE pages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(120) NOT NULL UNIQUE,   -- histoire, vision, mission, contact...
    title       VARCHAR(200) NOT NULL,
    subtitle    VARCHAR(255) NULL,
    body        LONGTEXT NOT NULL,
    cover_image VARCHAR(255) NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    sort_order  INT NOT NULL DEFAULT 0,
    updated_by  INT UNSIGNED NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_pages_updated_by
        FOREIGN KEY (updated_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE beliefs (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    number      TINYINT UNSIGNED NULL,          -- n° de croyance (1..28 Adventistes)
    title       VARCHAR(200) NOT NULL,
    summary     TEXT NULL,
    body        LONGTEXT NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    is_published TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_beliefs_number (number)
) ENGINE=InnoDB;

CREATE TABLE site_settings (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT NULL,
    value_type  ENUM('string','text','int','bool','json') NOT NULL DEFAULT 'string',
    label       VARCHAR(150) NULL,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- 3. Structure organisationnelle
-- -----------------------------------------------------------------------------

CREATE TABLE departments (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    name        VARCHAR(150) NOT NULL,
    short_name  VARCHAR(80) NULL,
    description TEXT NULL,
    mission     TEXT NULL,
    icon        VARCHAR(100) NULL,
    cover_image VARCHAR(255) NULL,
    email       VARCHAR(190) NULL,
    phone       VARCHAR(40) NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Types : Éclaireurs, Compagnons, Ambassadeurs, Chœurs, Groupes d'action...
CREATE TABLE association_types (
    id          TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(50) NOT NULL UNIQUE,
    label       VARCHAR(120) NOT NULL,
    description VARCHAR(255) NULL,
    sort_order  INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE associations (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    association_type_id TINYINT UNSIGNED NOT NULL,
    department_id       INT UNSIGNED NULL,     -- rattachement optionnel à un département
    slug                VARCHAR(120) NOT NULL UNIQUE,
    name                VARCHAR(150) NOT NULL,
    short_name          VARCHAR(80) NULL,
    description         TEXT NULL,
    cover_image         VARCHAR(255) NULL,
    email               VARCHAR(190) NULL,
    phone               VARCHAR(40) NULL,
    meeting_info        VARCHAR(255) NULL,
    sort_order          INT NOT NULL DEFAULT 0,
    is_active           TINYINT(1) NOT NULL DEFAULT 1,
    created_at          DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_associations_type
        FOREIGN KEY (association_type_id) REFERENCES association_types(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_associations_department
        FOREIGN KEY (department_id) REFERENCES departments(id)
        ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Postes / titres (Président d'Union, Pasteur de district, Directeur Jeunesse...)
CREATE TABLE positions (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code        VARCHAR(80) NOT NULL UNIQUE,
    title       VARCHAR(150) NOT NULL,
    description VARCHAR(255) NULL,
    level       ENUM('union','department','association','district','local','committee')
                NOT NULL DEFAULT 'union',
    sort_order  INT NOT NULL DEFAULT 0,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- 4. Dirigeants / Pasteurs (personne ≠ mandat)
--    Principe clé : on ne supprime jamais l'historique des affectations.
-- -----------------------------------------------------------------------------

CREATE TABLE leaders (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug            VARCHAR(120) NOT NULL UNIQUE,
    first_name      VARCHAR(100) NOT NULL,
    last_name       VARCHAR(100) NOT NULL,
    title_prefix    VARCHAR(50) NULL,          -- Pr., Pasteur, Dr...
    gender          ENUM('M','F','other') NULL,
    photo           VARCHAR(255) NULL,
    bio             TEXT NULL,
    email           VARCHAR(190) NULL,
    phone           VARCHAR(40) NULL,
    ordination_year YEAR NULL,
    is_pastor       TINYINT(1) NOT NULL DEFAULT 0,
    is_published    TINYINT(1) NOT NULL DEFAULT 1,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_leaders_name (last_name, first_name)
) ENGINE=InnoDB;

-- Mandats / affectations (historique conservé)
-- scope_type + scope_id = polymorphisme contrôlé :
--   union | department | association | committee
CREATE TABLE leader_assignments (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    leader_id       INT UNSIGNED NOT NULL,
    position_id     INT UNSIGNED NOT NULL,
    scope_type      ENUM('union','department','association','committee') NOT NULL,
    scope_id        INT UNSIGNED NULL,         -- NULL si scope = union
    status          ENUM('current','former','interim') NOT NULL DEFAULT 'current',
    start_date      DATE NOT NULL,
    end_date        DATE NULL,                 -- NULL = mandat en cours
    notes           TEXT NULL,
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_assign_leader
        FOREIGN KEY (leader_id) REFERENCES leaders(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_assign_position
        FOREIGN KEY (position_id) REFERENCES positions(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_assign_created_by
        FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_assign_leader (leader_id),
    INDEX idx_assign_scope (scope_type, scope_id),
    INDEX idx_assign_status (status),
    INDEX idx_assign_dates (start_date, end_date)
) ENGINE=InnoDB;

-- Comités (exécutif, nominations, etc.)
CREATE TABLE committees (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    name        VARCHAR(150) NOT NULL,
    description TEXT NULL,
    term_label  VARCHAR(100) NULL,             -- ex: "Mandat 2022-2027"
    start_date  DATE NULL,
    end_date    DATE NULL,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Membres de comité via leader_assignments (scope_type = committee)
-- Table de liaison optionnelle si besoin de rôles spécifiques hors positions :
CREATE TABLE committee_members (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    committee_id  INT UNSIGNED NOT NULL,
    leader_id     INT UNSIGNED NOT NULL,
    position_id   INT UNSIGNED NOT NULL,
    status        ENUM('current','former') NOT NULL DEFAULT 'current',
    start_date    DATE NOT NULL,
    end_date      DATE NULL,
    sort_order    INT NOT NULL DEFAULT 0,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cm_committee
        FOREIGN KEY (committee_id) REFERENCES committees(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_cm_leader
        FOREIGN KEY (leader_id) REFERENCES leaders(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_cm_position
        FOREIGN KEY (position_id) REFERENCES positions(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_cm_status (committee_id, status)
) ENGINE=InnoDB;

-- -----------------------------------------------------------------------------
-- 5. Médiathèque / Actualités / Événements
-- -----------------------------------------------------------------------------

CREATE TABLE media_categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    name        VARCHAR(120) NOT NULL,
    description VARCHAR(255) NULL,
    sort_order  INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE media_items (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id     INT UNSIGNED NULL,
    department_id   INT UNSIGNED NULL,
    association_id  INT UNSIGNED NULL,
    type            ENUM('sermon','video','audio','photo','document','bulletin','other')
                    NOT NULL DEFAULT 'other',
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(160) NOT NULL UNIQUE,
    description     TEXT NULL,
    speaker         VARCHAR(150) NULL,         -- prédicateur / intervenant
    file_path       VARCHAR(255) NULL,         -- fichier local
    external_url    VARCHAR(500) NULL,         -- YouTube, Drive...
    thumbnail       VARCHAR(255) NULL,
    mime_type       VARCHAR(100) NULL,
    file_size       INT UNSIGNED NULL,        -- octets
    published_at    DATETIME NULL,
    is_published    TINYINT(1) NOT NULL DEFAULT 0,
    download_count  INT UNSIGNED NOT NULL DEFAULT 0,
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_media_category
        FOREIGN KEY (category_id) REFERENCES media_categories(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_media_department
        FOREIGN KEY (department_id) REFERENCES departments(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_media_association
        FOREIGN KEY (association_id) REFERENCES associations(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_media_created_by
        FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_media_type (type),
    INDEX idx_media_published (is_published, published_at)
) ENGINE=InnoDB;

CREATE TABLE galleries (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug        VARCHAR(120) NOT NULL UNIQUE,
    title       VARCHAR(200) NOT NULL,
    description TEXT NULL,
    cover_image VARCHAR(255) NULL,
    event_date  DATE NULL,
    is_published TINYINT(1) NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE gallery_images (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gallery_id  INT UNSIGNED NOT NULL,
    file_path   VARCHAR(255) NOT NULL,
    caption     VARCHAR(255) NULL,
    alt_text    VARCHAR(255) NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_gallery_image
        FOREIGN KEY (gallery_id) REFERENCES galleries(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE posts (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id       INT UNSIGNED NULL,
    department_id   INT UNSIGNED NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(160) NOT NULL UNIQUE,
    excerpt         VARCHAR(500) NULL,
    body            LONGTEXT NOT NULL,
    cover_image     VARCHAR(255) NULL,
    status          ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
    published_at    DATETIME NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_author
        FOREIGN KEY (author_id) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_posts_department
        FOREIGN KEY (department_id) REFERENCES departments(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_posts_status (status, published_at)
) ENGINE=InnoDB;

CREATE TABLE events (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id   INT UNSIGNED NULL,
    association_id  INT UNSIGNED NULL,
    title           VARCHAR(255) NOT NULL,
    slug            VARCHAR(160) NOT NULL UNIQUE,
    description     TEXT NULL,
    location        VARCHAR(255) NULL,
    cover_image     VARCHAR(255) NULL,
    starts_at       DATETIME NOT NULL,
    ends_at         DATETIME NULL,
    is_all_day      TINYINT(1) NOT NULL DEFAULT 0,
    is_published    TINYINT(1) NOT NULL DEFAULT 0,
    created_by      INT UNSIGNED NULL,
    created_at      DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_events_department
        FOREIGN KEY (department_id) REFERENCES departments(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_events_association
        FOREIGN KEY (association_id) REFERENCES associations(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_events_created_by
        FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_events_dates (starts_at, ends_at),
    INDEX idx_events_published (is_published, starts_at)
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    email       VARCHAR(190) NOT NULL,
    phone       VARCHAR(40) NULL,
    subject     VARCHAR(200) NOT NULL,
    message     TEXT NOT NULL,
    is_read     TINYINT(1) NOT NULL DEFAULT 0,
    ip_address  VARCHAR(45) NULL,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contact_read (is_read, created_at)
) ENGINE=InnoDB;

CREATE TABLE carousel_slides (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(200) NULL,
    subtitle    VARCHAR(255) NULL,
    image_path  VARCHAR(255) NOT NULL,
    link_url    VARCHAR(500) NULL,
    link_label  VARCHAR(120) NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    is_active   TINYINT(1) NOT NULL DEFAULT 1,
    created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_carousel_active (is_active, sort_order)
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================================================
-- Données de référence (seed minimal)
-- =============================================================================

INSERT INTO roles (code, label, description) VALUES
('super_admin', 'Super administrateur', 'Accès total au back-office'),
('editor',      'Éditeur',             'Gestion du contenu éditorial et médias'),
('moderator',   'Modérateur',          'Publication limitée et messages');

-- Mot de passe temporaire : password
-- À remplacer immédiatement : php bin/set-admin-password.php "NouveauMotDePasse"
INSERT INTO users (role_id, email, password_hash, first_name, last_name, is_active) VALUES
(1, 'admin@uco.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'UCO', 1);

INSERT INTO association_types (code, label, sort_order) VALUES
('eclaireurs',   'Éclaireurs',        1),
('compagnons',   'Compagnons',        2),
('ambassadeurs', 'Ambassadeurs',      3),
('choeur',       'Chœurs / Chorales', 4),
('action',       'Groupes d''action', 5),
('autre',        'Autre',             99);

INSERT INTO positions (code, title, level, sort_order) VALUES
('union_president',     'Président d''Union',           'union',       1),
('union_secretary',     'Secrétaire d''Union',          'union',       2),
('union_treasurer',     'Trésorier d''Union',           'union',       3),
('dept_director',       'Directeur de département',     'department',  10),
('dept_associate',      'Directeur associé',            'department',  11),
('assoc_leader',        'Responsable d''association',   'association', 20),
('district_pastor',     'Pasteur de district',          'district',    30),
('local_pastor',        'Pasteur local',                'local',       31),
('committee_chair',     'Président de comité',          'committee',   40),
('committee_member',    'Membre de comité',             'committee',   41);

INSERT INTO departments (slug, name, short_name, sort_order) VALUES
('jeunesse',      'Ministère de la Jeunesse', 'Jeunesse',      1),
('femme',         'Ministère de la Femme',    'Femme',         2),
('education',     'Éducation',                'Éducation',     3),
('sante',         'Santé',                    'Santé',         4),
('evangelisation','Évangélisation',           'Évangélisation',5),
('sabbat-ecole',  'École du Sabbat',          'École du Sabbat',6);

INSERT INTO pages (slug, title, body, is_published, sort_order) VALUES
('histoire', 'Histoire', 'Contenu à compléter : histoire de l''UCO.', 1, 1),
('vision',   'Vision',   'Contenu à compléter : vision de l''UCO.',   1, 2),
('mission',  'Mission',  'Contenu à compléter : mission de l''UCO.',  1, 3);

INSERT INTO site_settings (setting_key, setting_value, value_type, label) VALUES
('site_name',        'Union du Congo Ouest — UCO', 'string', 'Nom du site'),
('site_tagline',     'Adventistes du 7e Jour — Kinshasa', 'string', 'Slogan'),
('contact_email',    'contact@uco.local', 'string', 'Email de contact'),
('contact_phone',    '', 'string', 'Téléphone'),
('address',          'Kinshasa, RDC', 'text', 'Adresse'),
('social_facebook',  '', 'string', 'Facebook'),
('social_youtube',   '', 'string', 'YouTube');

INSERT INTO media_categories (slug, name, sort_order) VALUES
('sermons',   'Sermons',    1),
('bulletins', 'Bulletins',  2),
('videos',    'Vidéos',     3),
('documents', 'Documents',  4);

INSERT INTO carousel_slides (title, subtitle, image_path, link_url, link_label, sort_order, is_active) VALUES
('Bienvenue à l''UCO', 'Union du Congo Ouest — Adventistes du 7e Jour', '/assets/img/adventist-church-hero.svg', '/pages/mission', 'Notre mission', 1, 1),
('Une foi vivante', 'Adoration, communauté et espérance', '/assets/img/adventist-worship.svg', '/croyances', 'Nos croyances', 2, 1),
('Au service de la communauté', 'Jeunesse, mission et engagement', '/assets/img/adventist-community.svg', '/associations', 'Nos associations', 3, 1);

-- =============================================================================
-- Vues utiles pour le front / back-office
-- =============================================================================

CREATE OR REPLACE VIEW v_current_leaders AS
SELECT
    l.id AS leader_id,
    l.slug,
    l.title_prefix,
    l.first_name,
    l.last_name,
    l.photo,
    l.is_pastor,
    p.title AS position_title,
    p.code  AS position_code,
    a.scope_type,
    a.scope_id,
    a.start_date,
    a.status,
    CASE a.scope_type
        WHEN 'department'  THEN d.name
        WHEN 'association' THEN ass.name
        WHEN 'committee'   THEN c.name
        ELSE 'Union'
    END AS scope_name
FROM leader_assignments a
JOIN leaders l   ON l.id = a.leader_id
JOIN positions p ON p.id = a.position_id
LEFT JOIN departments  d   ON a.scope_type = 'department'  AND d.id   = a.scope_id
LEFT JOIN associations ass ON a.scope_type = 'association' AND ass.id = a.scope_id
LEFT JOIN committees   c   ON a.scope_type = 'committee'   AND c.id   = a.scope_id
WHERE a.status = 'current'
  AND a.end_date IS NULL
  AND l.is_published = 1;

-- =============================================================================
-- Fin du script
-- =============================================================================
