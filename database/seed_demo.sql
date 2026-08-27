-- Données de démonstration pour que le site et l'admin aient du contenu réel.
USE uco_website;

UPDATE pages
SET subtitle = 'Union du Congo Ouest',
    body = 'L’Union du Congo Ouest (UCO) rassemble les Églises adventistes du 7e Jour de Kinshasa et de l’Ouest de la République démocratique du Congo. Elle accompagne les communautés locales dans la proclamation de l’Évangile, l’éducation, la santé et le service.'
WHERE slug = 'histoire';

UPDATE pages
SET subtitle = 'Un peuple d’espérance',
    body = 'L’UCO vise une Église vivante, missionnaire et unie, où chaque membre grandit dans la foi, sert son prochain et prépare le retour de Jésus-Christ.'
WHERE slug = 'vision';

UPDATE pages
SET subtitle = 'Proclamer, servir, former',
    body = 'Notre mission est de faire connaître Jésus-Christ, de former des disciples et de servir la société à travers les ministères de la jeunesse, de la femme, de l’éducation, de la santé et de l’évangélisation.'
WHERE slug = 'mission';

UPDATE departments SET description = 'Accompagne les enfants, adolescents et jeunes adultes dans la foi, le leadership et le service.' WHERE slug = 'jeunesse';
UPDATE departments SET description = 'Soutient les femmes dans la vie spirituelle, familiale et communautaire.' WHERE slug = 'femme';
UPDATE departments SET description = 'Promeut une éducation adventiste de qualité, de l’école à l’université.' WHERE slug = 'education';
UPDATE departments SET description = 'Encourage un mode de vie sain et des actions de santé publique.' WHERE slug = 'sante';
UPDATE departments SET description = 'Coordonne l’annonce de l’Évangile et les campagnes d’évangélisation.' WHERE slug = 'evangelisation';
UPDATE departments SET description = 'Anime l’étude de la Bible et la vie de l’Église le jour du sabbat.' WHERE slug = 'sabbat-ecole';

INSERT INTO beliefs (number, title, summary, body, sort_order, is_published) VALUES
(1, 'Les Saintes Écritures', 'La Bible est la Parole de Dieu, révélation fiable pour la foi et la vie.', 'Les Saintes Écritures, Ancien et Nouveau Testaments, sont la Parole écrite de Dieu, donnée par inspiration divine.', 1, 1),
(2, 'La Trinité', 'Il y a un seul Dieu : Père, Fils et Saint-Esprit, unité de trois personnes coéternelles.', 'Dieu est amour, éternel, tout-puissant, omniscient et présent partout.', 2, 1),
(4, 'Le Fils', 'Dieu le Fils éternel s’est incarné en Jésus-Christ.', 'Par lui tout a été créé. Il a vécu parmi nous, est mort pour nos péchés et est ressuscité.', 4, 1),
(6, 'La création', 'Dieu est le Créateur de toutes choses et a institué le sabbat comme mémorial.', 'En six jours, le Seigneur a fait « les cieux et la terre » et s’est reposé le septième jour.', 6, 1),
(10, 'L’expérience du salut', 'Le salut est un don de Dieu, reçu par la grâce au moyen de la foi en Jésus-Christ.', 'Le Saint-Esprit nous convainc de péché et nous donne une vie nouvelle.', 10, 1),
(20, 'Le sabbat', 'Le sabbat du septième jour est un jour de repos, d’adoration et de service.', 'Il est un signe perpétuel de l’alliance entre Dieu et son peuple.', 20, 1),
(25, 'La seconde venue du Christ', 'Le retour de Jésus est proche, visible et glorieux.', 'C’est l’espérance bienheureuse de l’Église.', 25, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

INSERT INTO associations (association_type_id, department_id, slug, name, short_name, description, meeting_info, sort_order, is_active)
SELECT 1, d.id, 'eclaireurs-uco', 'Éclaireurs de l’UCO', 'Éclaireurs',
       'Mouvement des Éclaireurs adventistes : nature, discipline, service et vie chrétienne.',
       'Samedis après-midi, campus de l’Union', 1, 1
FROM departments d WHERE d.slug = 'jeunesse'
AND NOT EXISTS (SELECT 1 FROM associations WHERE slug = 'eclaireurs-uco');

INSERT INTO associations (association_type_id, department_id, slug, name, short_name, description, sort_order, is_active)
SELECT 4, NULL, 'choeur-union', 'Chœur de l’Union', 'Chœur',
       'Ensemble vocal au service de l’adoration et des grands rassemblements de l’Union.',
       2, 1
WHERE NOT EXISTS (SELECT 1 FROM associations WHERE slug = 'choeur-union');

INSERT INTO leaders (slug, first_name, last_name, title_prefix, gender, bio, is_pastor, is_published)
SELECT 'pr-jean-mukendi', 'Jean', 'Mukendi', 'Pr.', 'M',
       'Pasteur et administrateur, au service de l’Union du Congo Ouest.',
       1, 1
WHERE NOT EXISTS (SELECT 1 FROM leaders WHERE slug = 'pr-jean-mukendi');

INSERT INTO leaders (slug, first_name, last_name, title_prefix, gender, bio, is_pastor, is_published)
SELECT 'pr-marie-kalala', 'Marie', 'Kalala', 'Pr.', 'F',
       'Directrice du ministère de la jeunesse, engagée auprès des jeunes de l’Union.',
       1, 1
WHERE NOT EXISTS (SELECT 1 FROM leaders WHERE slug = 'pr-marie-kalala');

INSERT INTO leader_assignments (leader_id, position_id, scope_type, scope_id, status, start_date)
SELECT l.id, p.id, 'union', NULL, 'current', '2022-01-15'
FROM leaders l
JOIN positions p ON p.code = 'union_president'
WHERE l.slug = 'pr-jean-mukendi'
AND NOT EXISTS (
    SELECT 1 FROM leader_assignments a WHERE a.leader_id = l.id AND a.position_id = p.id AND a.status = 'current'
);

INSERT INTO leader_assignments (leader_id, position_id, scope_type, scope_id, status, start_date)
SELECT l.id, p.id, 'department', d.id, 'current', '2023-03-01'
FROM leaders l
JOIN positions p ON p.code = 'dept_director'
JOIN departments d ON d.slug = 'jeunesse'
WHERE l.slug = 'pr-marie-kalala'
AND NOT EXISTS (
    SELECT 1 FROM leader_assignments a WHERE a.leader_id = l.id AND a.position_id = p.id AND a.status = 'current'
);

INSERT INTO posts (author_id, department_id, title, slug, excerpt, body, status, published_at)
SELECT 1, d.id, 'Bienvenue sur le nouveau site de l’UCO', 'bienvenue-nouveau-site-uco',
       'L’Union du Congo Ouest met en ligne son site officiel pour mieux servir les Églises et le public.',
       'Le site présente la direction, les départements, les associations, les actualités, les événements et la médiathèque. L’administration peut désormais publier le contenu en toute autonomie.',
       'published', NOW()
FROM departments d WHERE d.slug = 'evangelisation'
AND NOT EXISTS (SELECT 1 FROM posts WHERE slug = 'bienvenue-nouveau-site-uco');

INSERT INTO events (department_id, title, slug, description, location, starts_at, ends_at, is_published, created_by)
SELECT d.id, 'Camp-meeting de l’Union', 'camp-meeting-union-2026',
       'Grand rassemblement annuel : prédication, ateliers, jeunesse et communion fraternelle.',
       'Kinshasa', '2026-09-18 08:00:00', '2026-09-26 18:00:00', 1, 1
FROM departments d WHERE d.slug = 'evangelisation'
AND NOT EXISTS (SELECT 1 FROM events WHERE slug = 'camp-meeting-union-2026');

INSERT INTO events (department_id, title, slug, description, location, starts_at, ends_at, is_published, created_by)
SELECT d.id, 'Journée de la jeunesse', 'journee-jeunesse-2026',
       'Célébration, formation et projets de service pour les jeunes de l’UCO.',
       'Campus UCO, Kinshasa', '2026-10-10 09:00:00', '2026-10-10 17:00:00', 1, 1
FROM departments d WHERE d.slug = 'jeunesse'
AND NOT EXISTS (SELECT 1 FROM events WHERE slug = 'journee-jeunesse-2026');

INSERT INTO media_items (category_id, type, title, slug, description, speaker, external_url, is_published, published_at, created_by)
SELECT c.id, 'video', 'Message de bienvenue de l’Union', 'message-bienvenue-union',
       'Allocution de rentrée de la direction de l’UCO.', 'Pr. Jean Mukendi',
       'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, NOW(), 1
FROM media_categories c WHERE c.slug = 'videos'
AND NOT EXISTS (SELECT 1 FROM media_items WHERE slug = 'message-bienvenue-union');

INSERT INTO committees (slug, name, description, term_label, start_date, end_date, is_active)
SELECT 'executif', 'Comité exécutif', 'Instance de gouvernance de l’Union entre les sessions.',
       'Mandat 2022-2027', '2022-01-01', '2027-12-31', 1
WHERE NOT EXISTS (SELECT 1 FROM committees WHERE slug = 'executif');

INSERT INTO committee_members (committee_id, leader_id, position_id, status, start_date, sort_order)
SELECT c.id, l.id, p.id, 'current', '2022-01-15', 1
FROM committees c
JOIN leaders l ON l.slug = 'pr-jean-mukendi'
JOIN positions p ON p.code = 'committee_chair'
WHERE c.slug = 'executif'
AND NOT EXISTS (
    SELECT 1 FROM committee_members cm WHERE cm.committee_id = c.id AND cm.leader_id = l.id
);

INSERT INTO galleries (slug, title, description, event_date, is_published)
SELECT 'assemblee-2025', 'Assemblée de l’Union 2025', 'Quelques souvenirs du rassemblement annuel.',
       '2025-08-15', 1
WHERE NOT EXISTS (SELECT 1 FROM galleries WHERE slug = 'assemblee-2025');
