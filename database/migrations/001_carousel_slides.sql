-- Migration : carousel page d'accueil
-- mysql -u root -p uco_website < database/migrations/001_carousel_slides.sql

USE uco_website;

CREATE TABLE IF NOT EXISTS carousel_slides (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO carousel_slides (title, subtitle, image_path, link_url, link_label, sort_order, is_active)
SELECT * FROM (
    SELECT 'Bienvenue à l''UCO' AS title,
           'Union du Congo Ouest — Adventistes du 7e Jour' AS subtitle,
           '/assets/img/adventist-church-hero.svg' AS image_path,
           '/pages/mission' AS link_url,
           'Notre mission' AS link_label,
           1 AS sort_order,
           1 AS is_active
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM carousel_slides LIMIT 1);

INSERT INTO carousel_slides (title, subtitle, image_path, link_url, link_label, sort_order, is_active)
SELECT 'Une foi vivante', 'Adoration, communauté et espérance', '/assets/img/adventist-worship.svg', '/croyances', 'Nos croyances', 2, 1
FROM DUAL
WHERE (SELECT COUNT(*) FROM carousel_slides) < 2;

INSERT INTO carousel_slides (title, subtitle, image_path, link_url, link_label, sort_order, is_active)
SELECT 'Au service de la communauté', 'Jeunesse, mission et engagement', '/assets/img/adventist-community.svg', '/associations', 'Nos associations', 3, 1
FROM DUAL
WHERE (SELECT COUNT(*) FROM carousel_slides) < 3;
