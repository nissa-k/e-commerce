# E-Courses — Site e-commerce dynamique PHP (Bachelor 2)

Projet e-commerce dynamique en PHP (procedural + PDO) :  
- Front-office : catalogue des cours, fiche cours, panier, checkout (paiement simulé), accès “Mes cours”.
- Auth : inscription / connexion / déconnexion (sessions).
- Back-office : CRUD cours + gestion utilisateurs (admin).
- Base de données MySQL (PDO) + script SQL d’installation.

## Prérequis
- WampServer (ou XAMPP/MAMP) avec :
  - PHP 8.x recommandé
  - MySQL/MariaDB
  - Extension PDO MySQL activée
- phpMyAdmin (ou accès mysql en CLI)

## Installation

### 1 Placer le projet
Copie le dossier du projet dans ton répertoire web Wamp :

- `C:\wamp64\www\e-commerce` (exemple)

Ensuite tu dois pouvoir accéder au site via :
- `http://localhost/e-commerce/`

> Si tu utilises un VirtualHost (ex: `http://ecourses/`), adapte juste l’URL.

### 2 Configuration `.env`
Le projet lit ses identifiants DB depuis un fichier `.env`.

1. Duplique `.envexample` et renomme en `.env`
2. Remplis selon ta config MySQL

Exemple :
DB_HOST=127.0.0.1
DB_NAME=ecommerce_php
DB_USER=root
DB_PASS=

markdown
Copier le code

### 3 Créer la base de données
Importe le fichier SQL :

- `config/db.sql`

Méthode phpMyAdmin :
1. Ouvre phpMyAdmin
2. Onglet **Importer**
3. Sélectionne `config/db.sql`
4. Exécute

### 4 Créer le dossier d’uploads
Assure-toi que le dossier existe :
- `public/uploads`

(Le back-office peut uploader une image thumbnail de cours.)

### 5 Tester la connexion DB
Ouvre :
- `http://localhost/e-commerce/test_db.php`

Résultat attendu :
- `✅ DB OK`

Si erreur :
- vérifie `.env`
- vérifie que MySQL est démarré
- vérifie le nom de DB

## Comptes & accès

### Compte utilisateur
1. Va sur : `register.php`
2. Crée un compte
3. Connecte-toi via : `login.php`

### Compte admin
Pour accéder au back-office :
- `admin/index.php`

Tu dois avoir un utilisateur avec `role=admin` dans la table `users`.

Si tu n’as pas d’admin :
1. crée-toi un compte via `register.php`
2. puis dans phpMyAdmin :
   - table `users`
   - mets `role = admin` sur ton utilisateur

## Parcours de test (checklist)

### Front-office
- [ ] Accueil : `index.php`
- [ ] Catalogue + filtre : `courses.php`
- [ ] Fiche cours : `course.php?slug=...`
- [ ] Ajouter au panier : bouton “Ajouter”
- [ ] Panier : `cart.php`
- [ ] Checkout (paiement simulé) : `checkout.php`
- [ ] Mes cours : `my_courses.php`
- [ ] Accès contenu protégé : `learn.php?slug=...`

### Back-office (admin)
- [ ] Connexion admin : `admin/login.php` (ou `login.php` si déjà admin)
- [ ] Liste cours : `admin/courses_list.php`
- [ ] Ajouter cours : `admin/courses_create.php`
- [ ] Modifier cours : `admin/course_edit.php?id=...`
- [ ] Supprimer cours : `admin/course_delete.php?id=...`
- [ ] Liste users : `admin/users_list.php`
- [ ] Supprimer user : `admin/user_delete.php?id=...`

## Structure du projet

/admin Back-office (CRUD cours + users)
/config DB + SQL
/includes header/footer + fonctions + auth
/public/assets CSS
/public/uploads Upload images cours
/*.php Front-office (pages)
.envexample Exemple de config (commit)
.env Config réelle (ne pas commit)

markdown
Copier le code

## Notes de sécurité (niveau projet)
- Requêtes préparées (PDO) pour éviter SQL injection
- Sessions pour authentification
- Accès admin protégé (requireAdmin)
- Upload limité à jpg/jpeg/png/webp (basique)

## Dépannage
### “404 Not Found”
- Vérifie que le projet est bien dans `wamp64/www/`
- Vérifie l’URL (ex: `http://localhost/e-commerce/`)

### “DB ERROR”
- Vérifie `.env`
- Vérifie que la base et tables existent (import SQL)
- Vérifie MySQL démarré

### Upload ne marche pas
- Vérifie que `public/uploads` existe
- Vérifie les permissions (Windows : dossier non bloqué)

---

### Auteur
Karadag Nissa
Projet réalisé dans le cadre de l’évaluation PHP Bachelor 2.