# Projet Final — ElectroShop 

   
**Technologies** : PHP 8+, Symfony 6, Doctrine ORM, EasyAdminBundle, Bootstrap 5, PayPal Checkout SDK, Symfony Mailer

---

## 1. Description

ElectroShop est une boutique e-commerce d’appareils électroniques (PC, écrans, claviers, souris, disques SSD/HDD), développée en **Programmation Orientée Objet (POO)** avec Symfony. Elle couvre :

- Gestion des utilisateurs (inscription, connexion, profil, OTP, mot de passe oublié, auto-déconnexion)
- Espace **administrateur** (CRUD produits, catégories, commandes)
- Panier et calcul automatique du total (incluant taxes québécoises)
- Paiement électronique via **PayPal** sandbox
- Respect des bonnes pratiques : SOLID, DRY, KISS, Clean Code

---
          
## 2. Prérequis

- PHP 8.1+
- Composer 2+
- MySQL (ou MariaDB)
- Symfony CLI (optionnel)
- Clonage du dépôt Git

---

## 3. Installation & Configuration

1. **Cloner le projet** 


2. **Installer les dépendances** :
   ```bash
   composer install
   ```

3. **Configurer l’environnement** :
   Copiez `.env` en `.env.local` et ajustez :
   ```dotenv
   APP_ENV=dev
   APP_SECRET=<clé-aléatoire>      # ex. `php -r "echo bin2hex(random_bytes(16));"`
   DATABASE_URL=mysql://db_user:db_pass@127.0.0.1:3306/electroshop

   ###> symfony/mailer ###
   MAILER_DSN=smtp://localhost
   ###< symfony/mailer ###

   MAILER_FROM_ADDRESS=no-reply@electroshop.com
   INACTIVITY_TIMEOUT=120

   PAYPAL_SANDBOX_CLIENT_ID=<votre-client-id>
   PAYPAL_SANDBOX_CLIENT_SECRET=<votre-secret>
   ```

4. **Base de données & migrations** :
  - Script SQL initial (racine) : `schema.sql` (création tables)
  - Ou via Doctrine :
    ```bash
    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    ```

5. **Démarrer le serveur** :
   ```bash
   symfony server:start
   # ou php -S 127.0.0.1:8000 -t public
   ```

---

## 4. Fonctionnalités implémentées

### Tâche 1 – SQL & Doctrine
- Fichier `schema.sql` à la racine (tables: user, product, category, cart, order, order_details).
- Entités Symfony mappées via annotations Doctrine (User, Product, Category, Order, OrderDetails).

### Tâche 2 – Gestion des utilisateurs (4 pts)
- **Inscription / Connexion / Déconnexion** : formulaires sécurisés (CSRF, validation).
- **Mot de passe oublié** : workflow de reset par e‑mail avec token.
- **Profil / Modification** : formulaire `ProfileType` pour modifier nom, prénom, e‑mail et mot de passe.
- **Auto-déconnexion** : listener `LastActivityListener` invalide la session après 120 s d’inactivité.
- **Suppression / Réactivation de compte** : soft‑delete avec possibilité de réactiver.

### Tâche 3 – Gestion administrateurs (3 pts)
- **Back-office** via EasyAdminBundle : CRUD complet pour utilisateurs, produits, catégories et commandes.
- Menu personnalisé incluant un lien vers le front‑office.

### Tâche 4 – Gestion des produits (2,5 pts)
- **CRUD Produits** : titre, image, description, prix en cents, stock.
- **Validation** : vérification qu’un produit ne passe jamais en stock négatif.

### Tâche 5 – Panier (3 pts)
- **Service `Cart`** : stockage en session, ajout, suppression, modification des quantités.
- **Calcul du total** : prix totaux incluant taxes québécoises.
- **Badge dynamique** : affichage du nombre d’articles sur l’icône panier.

### Tâche 6 – Paiement électronique (3 pts)
- **Intégration PayPal sandbox** : SDK `paypal/paypal-checkout-sdk`, contrôleur `PaymentController`.
- **Flux complet** : création de commande PayPal, capture du paiement, pages `success`/`fail`.
- **Entity `Order` enrichie** : propriétés `paypalOrderId`, `state`.

### Tâche 7 – Rapport & README (3 pts)
- Ce document récapitule les démarches, les commandes utilisées, l’architecture et le travail de chacun.

## 5. Fonctionnalités supplémentaires
- **Recherche & filtres** : sidebar Twig pour filtrer par catégories ou attributs.
- **Service Mail centralisé** : classe `Mail` injectée via DI pour tous les envois d’e‑mail.
- **Design personnalisé** : CSS custom (`btn-dark`, navbar dégradée, footer stylé, cartes Bootstrap).
- **Formulaires améliorés** : application des thèmes Bootstrap 5 pour Twig (`form_theme`).
- **Contact formulaire stylisé** : design de la page contact avec card, validation, messages flash.
- **Navigation** : menu responsive, dropdown pour compte, badge sur panier.

## 6. Architecture & bonnes pratiques

## 7. Ressources & commandes clés

Architecture & bonnes pratiques

- **Pattern MVC** : séparation contrôleurs, entités, vues Twig.
- **SOLID** : responsabilités uniques (services, entités, listeners).
- **DRY** : services réutilisables (`Mail`, `Cart`, `LastActivityListener`).
- **KISS** : code clair, peu de complexité inutile.

---

## 6. Ressources & commandes clés

- Créer une migration : `php bin/console make:migration`
- Appliquer migrations : `php bin/console doctrine:migrations:migrate`
- Vider cache : `php bin/console cache:clear`
- Générer clé secrète : `php -r "echo bin2hex(random_bytes(16));"`

---

## 7. Screenshots

-Dans dossier /ScreenShots

© 2025 ElectroShop — Projet final POO Symfony

