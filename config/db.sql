CREATE DATABASE ecommerce_php CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ecommerce_php;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('user','admin') NOT NULL DEFAULT 'user',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  published_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_id INT NOT NULL UNIQUE,
  quantity INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_stock_item FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  item_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_orders_item FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE
);

CREATE TABLE invoice (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  transaction_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  amount DECIMAL(10,2) NOT NULL,
  billing_address VARCHAR(255) NOT NULL,
  city VARCHAR(100) NOT NULL,
  postal_code VARCHAR(20) NOT NULL,
  CONSTRAINT fk_invoice_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(50) NOT NULL UNIQUE,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  description TEXT NOT NULL,
  price DECIMAL(10,2) NOT NULL DEFAULT 0,
  level ENUM('debutant','intermediaire','avance') NOT NULL DEFAULT 'debutant',
  thumbnail VARCHAR(255) DEFAULT NULL,
  published TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS course_sections (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  position INT NOT NULL DEFAULT 1,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS course_lessons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  section_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  content LONGTEXT NOT NULL,
  resource_path VARCHAR(255) NULL,
  position INT NOT NULL DEFAULT 1,
  is_preview TINYINT(1) NOT NULL DEFAULT 0,
  FOREIGN KEY (section_id) REFERENCES course_sections(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS orders_shop (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  status ENUM('pending','paid','failed','cancelled') NOT NULL DEFAULT 'pending',
  total_amount DECIMAL(10,2) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  course_id INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders_shop(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id)
);

CREATE TABLE IF NOT EXISTS payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  provider ENUM('stripe','paypal','fake') NOT NULL DEFAULT 'fake',
  provider_ref VARCHAR(255) NULL,
  status ENUM('pending','succeeded','failed') NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders_shop(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  course_id INT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_enroll (user_id, course_id),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

INSERT INTO categories (slug, name) VALUES
('dev','Développement'),
('programmation','Programmation'),
('cyber','Cybersécurité'),
('reseaux','Réseaux')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO courses (category_id, title, slug, description, price, level, published) VALUES
-- DÉVELOPPEMENT (5)
((SELECT id FROM categories WHERE slug='dev'), 'PHP – Site dynamique (PDO + Sessions)', 'dev-php-pdo-sessions',
 'PDO, requêtes préparées, sessions, login/register, pages dynamiques.', 29.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='dev'), 'PHP – CRUD Admin (Back-office)', 'dev-php-crud-admin',
 'Créer un back-office : ajouter/modifier/supprimer, validation, sécurité.', 34.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='dev'), 'HTML/CSS – Site responsive', 'dev-html-css-responsive',
 'Mise en page, flexbox, grid, responsive, bonnes pratiques.', 19.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='dev'), 'JavaScript – DOM & Formulaires', 'dev-js-dom-formulaires',
 'Events, DOM, validation, UX simple, mini-projets.', 22.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='dev'), 'API REST – Bases (HTTP/JSON)', 'dev-api-rest-bases',
 'Méthodes HTTP, routes, JSON, statuts, tests avec Postman.', 39.90, 'intermediaire', 1),

-- PROGRAMMATION (5)
((SELECT id FROM categories WHERE slug='programmation'), 'Python – Bases + exercices', 'prog-python-bases-exos',
 'Variables, conditions, boucles, fonctions + exercices corrigés.', 19.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='programmation'), 'Python – POO & fichiers', 'prog-python-poo-fichiers',
 'Classes, héritage, fichiers, modules + mini-projet.', 24.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='programmation'), 'C# – POO (bases solides)', 'prog-csharp-poo-bases',
 'Classes, interfaces, exceptions, collections + pratique.', 29.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='programmation'), 'Java – POO & Collections', 'prog-java-poo-collections',
 'POO, collections, exceptions, exercices progressifs.', 29.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='programmation'), 'Algorithmes – Tri/Recherche (bases)', 'prog-algo-tri-recherche',
 'Complexité, tris simples, recherche, structures de base.', 24.90, 'debutant', 1),

-- CYBERSÉCURITÉ (5) (formation/défensif)
((SELECT id FROM categories WHERE slug='cyber'), 'Wireshark – Analyse de trafic (défensif)', 'cyber-wireshark-analyse',
 'Filtres, TCP/UDP, DNS/HTTP, lecture de captures + cas pratiques.', 27.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='cyber'), 'Analyse de logs – Windows/Linux (bases)', 'cyber-analyse-logs',
 'Comprendre logs, authentification, erreurs, corrélation simple.', 24.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='cyber'), 'Nmap – Découverte réseau (cadre légal)', 'cyber-nmap-legal',
 'Découverte d’hôtes/services, lecture des résultats, éthique.', 21.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='cyber'), 'VPN – Principes & usages', 'cyber-vpn-principes',
 'Tunnel, chiffrement, scénarios, bonnes pratiques.', 24.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='cyber'), 'Sécurité Web – OWASP (bases)', 'cyber-owasp-bases',
 'Injections, XSS, CSRF, sessions, bonnes pratiques côté dev.', 34.90, 'intermediaire', 1),

-- RÉSEAUX (5)
((SELECT id FROM categories WHERE slug='reseaux'), 'Adressage IP & Subnetting', 'reseaux-ip-subnetting',
 'IPv4, CIDR, calcul de sous-réseaux + exercices corrigés.', 18.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='reseaux'), 'Bases TCP/IP & OSI', 'reseaux-tcpip-osi',
 'Modèles OSI/TCP-IP, ports, DNS/DHCP, ARP, ping/traceroute.', 19.90, 'debutant', 1),
((SELECT id FROM categories WHERE slug='reseaux'), 'Cisco – VLAN & Switching (Packet Tracer)', 'reseaux-cisco-vlan-switch',
 'VLAN, trunk, inter-VLAN (bases), config et vérifications.', 29.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='reseaux'), 'Cisco – Routage statique (lab)', 'reseaux-cisco-routing-statique',
 'Routes statiques, passerelles, tests, dépannage simple.', 24.90, 'intermediaire', 1),
((SELECT id FROM categories WHERE slug='reseaux'), 'Dépannage réseau – Méthode', 'reseaux-depannage-methode',
 'Méthode de diagnostic, commandes, symptômes, cas pratiques.', 29.90, 'intermediaire', 1);
