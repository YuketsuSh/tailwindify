# Tailwindify - Plugin Azuriom

**Développez vos thèmes Azuriom avec TailwindCSS.**

Tailwindify est un plugin destiné aux développeurs de thèmes Azuriom souhaitant utiliser **TailwindCSS** à la place de **Bootstrap**. Il prend en charge automatiquement la compilation de Tailwind à partir du fichier `style.css` du thème actif et supporte les configurations personnalisées via un `tailwind.config.js`.

---

## 🚀 Fonctionnalités

- 🔧 Compilation automatique de TailwindCSS pour le thème actif
- ⚙️ Support natif du fichier `tailwind.config.js` dans le thème
- 📄 Injection des directives Tailwind dans `style.css`
- 🛠️ Commande Artisan `tailwindify:compile` pour compiler manuellement
- 🖥️ Interface d'administration :
  - ✅ Recompilation manuelle
  - 📜 Affichage et gestion des logs
- 🧩 Mise à disposition d’un chemin CSS compilé à inclure dans le thème
- 🗂️ Chargement conditionnel (non injecté dans l'admin)

---

## 📦 Installation

1. Placez le plugin dans le dossier `plugins`
2. Activez-le via l'administration d'Azuriom
3. Créez votre thème dans `resources/themes/mon-theme` avec :
   - `assets/css/style.css`
   - (optionnel) `tailwind.config.js`

---

## 🖥️ Interface admin

Accédez à **Admin > Tailwindify** pour :

- Voir le thème actif
- Forcer une compilation Tailwind
- Lire ou vider les logs d'activité

---

## 💡 Chargement du CSS compilé dans le thème

Le plugin met automatiquement à disposition la variable `$tailwindify_css` dans **toutes les vues utilisateur** (hors admin).

Pour l’utiliser, il suffit d’ajouter cette ligne une fois dans votre `resources/themes/votre-theme/views/layouts/base.blade.php` :

```blade
@if ($tailwindify_css)
    <link rel="stylesheet" href="{{ $tailwindify_css }}">
@endif
```

### 👉 À savoir :
- Si un fichier `output.css` compilé existe dans votre thème, c’est celui-là qui sera utilisé.
- Sinon, un fichier `output.css` par défaut (fourni par le plugin) sera utilisé à la place.
- Aucun CSS n’est injecté côté administration pour éviter les conflits.

---

## 📂 Exemple de structure de thème

```
resources/themes/mon-theme/
├── assets/
│   └── css/
│       └── style.css     ← Contient les @tailwind directives
├── tailwind.config.js    ← (facultatif)
```

---

## 🧪 Commande Artisan

```bash
php artisan tailwindify:compile
```

Compile manuellement Tailwind pour le thème actif.

---

## 📝 Journalisation

Toutes les actions importantes (compilation, erreurs, logs...) sont enregistrées dans :

```
storage/logs/tailwindify.log
```

---

## 🛠️ Dépendances

- Node.js et npm doivent être installés
- Utilisation de `npx tailwindcss` pour compiler automatiquement (css minifié)