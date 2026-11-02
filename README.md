# 🍾 AlcoGest — Application de gestion de commandes d'alcool

Version initiale déclarée à cette révision : **0.1.0** (voir [package.json](package.json)). Cette déclaration ne correspond pas à une release publiée.

## Stack

- **Backend** : PHP 8.2 / Laravel 11
- **Base de données** : PostgreSQL 15+
- **Frontend** : Blade + Tailwind CSS 3
- **Auth** : Laravel Breeze
- **Queue** : Redis + Laravel Queue
- **PDF** : barryvdh/laravel-dompdf
- **Import** : maatwebsite/excel
- **Paiement** : Stripe PHP SDK
- **SMS** : Twilio SDK

---

## Installation

### 1. Cloner et configurer

```bash
cp .env.example .env
# Éditer .env avec vos paramètres DB, Stripe, Twilio, Mail
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Générer la clé et migrer

```bash
php artisan key:generate
php artisan migrate
# Pour une base de démonstration uniquement :
php artisan db:seed
php artisan storage:link
```

### 4. Compiler les assets

```bash
npm run build
# ou en développement :
npm run dev
```

### 5. Démarrer le serveur et le worker de queue

Dans un terminal :

```bash
php artisan serve
```

Dans un autre terminal, après avoir configuré Redis :

```bash
php artisan queue:work --queue=notifications,default
```

---

## Comptes par défaut (après le seeder)

| Email | Mot de passe | Rôle |
|-------|-------------|------|
| admin@alcogest.fr | password | super_admin |
| client@alcogest.fr | password | client |

> Ces comptes sont destinés aux données de démonstration. Ne lancez pas ce seeder sur une base de production ; créez des comptes avec des mots de passe sûrs.

---

## Structure

```
app/
├── Http/Controllers/
│   ├── Admin/          # Dashboard, Product, Order, Category, SupplierOrder
│   ├── Client/         # Cart, Order, Profile
│   ├── Settings/       # SettingsController
│   ├── CatalogController.php
│   └── WebhookController.php
├── Models/             # User, Product, Order, OrderItem, Cart, Setting...
├── Services/           # OrderService, CartService, PaymentService...
├── Jobs/               # Notifications email + SMS (async)
├── Mail/               # OrderStatusUpdated, SupplierOrderMail
├── Policies/           # OrderPolicy, ProductPolicy
└── Imports/            # ProductsImport (CSV/Excel)

database/migrations/    # migrations du schéma
resources/views/
├── layouts/            # admin.blade.php, client.blade.php
├── admin/              # dashboard, orders, products, categories, supplier-orders
├── client/             # catalog, cart, orders, profile
├── mails/              # templates HTML email
└── settings/           # paramètres super admin
routes/
├── web.php             # toutes les routes (public, client, admin, super_admin)
└── auth.php            # routes Breeze auth
```

---

## Routes principales

### Catalogue (compte authentifié et actif)
- `GET /catalogue` — Catalogue produits
- `GET /catalogue/{slug}` — Fiche produit

### Client (authentifié)
- `GET /mon-compte/panier` — Panier
- `GET /mon-compte/checkout` — Finaliser commande
- `GET /mon-compte/commandes` — Mes commandes
- `GET /mon-compte/profil` — Mon profil

### Admin
- `GET /admin` — Dashboard
- `GET /admin/commandes` — Liste commandes (filtres)
- `GET /admin/produits` — Gestion produits
- `GET /admin/bons-fournisseur` — Bons fournisseur

### Super Admin
- `GET /admin/parametres` — Paramètres (Stripe, SMS, RIB, fournisseur)

---

## Webhook Stripe

URL à configurer dans Stripe Dashboard :
```
https://votre-domaine.fr/webhooks/stripe
```

Événement à activer : `checkout.session.completed`

---

## Production (Supervisor pour la queue)

```ini
[program:alcogest-worker]
command=php /var/www/alcogest/artisan queue:work redis --queue=notifications,default --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/alcogest-worker.log
```
