# tailwindify

## 📝 Description

`tailwindify` est un plugin Azuriom innovant conçu pour simplifier et optimiser le développement de thèmes en intégrant nativement Tailwind CSS. Fini les configurations complexes ! Ce plugin prend en charge la compilation automatique de votre CSS, l'injection conditionnelle des feuilles de style optimisées, et offre une interface d'administration intuitive avec un journal détaillé des actions. Développez des thèmes Azuriom modernes, performants et hautement personnalisables avec la puissance de Tailwind CSS.

## ✨ Fonctionnalités

*   **Compilation CSS automatique** : Gère la compilation de votre code Tailwind CSS en arrière-plan, sans intervention manuelle.
*   **Injection CSS conditionnelle** : Injecte les feuilles de style Tailwind compilées uniquement sur les pages et thèmes configurés, optimisant ainsi les performances.
*   **Interface d'administration dédiée** : Un panneau simple pour configurer le plugin, gérer les thèmes et surveiller les compilations.
*   **Journalisation des actions** : Gardez une trace de toutes les opérations de compilation et d'injection pour un débogage facile.
*   **Support multi-thèmes** : Permet de gérer la compilation Tailwind CSS pour plusieurs thèmes Azuriom simultanément.
*   **Intégration facile** : S'intègre de manière transparente avec l'écosystème Azuriom existant.

## 📦 Installation

Suivez ces étapes pour installer et activer le plugin `tailwindify` sur votre instance Azuriom :

1.  **Télécharger la dernière version** : Rendez-vous sur la page des [releases](https://github.com/YuketsuSh/tailwindify/releases) du dépôt et téléchargez le fichier `.zip` de la dernière version stable.
2.  **Uploader dans vos plugins Azuriom** :
    *   Connectez-vous à votre FTP.
    *   Naviguez vers `/plugins`.
    *   Uploadez le contenu du fichier .zip
3.  **Activer le plugin** : Une fois le plugin uploadé, assurez-vous de l'activer depuis la liste des plugins installés dans votre panneau d'administration Azuriom.
4.  **Dépendances Node.js (requis et recommandé)** : Pour que la compilation Tailwind CSS fonctionne correctement, assurez-vous que `Node.js` et `npm` (ou `yarn`) sont installés sur votre serveur. Le plugin tentera d'utiliser ces outils automatiquement.

    ```bash
    # Vérifier l'installation
    node -v
    npm -v
    ```

## 🚀 Utilisation

Après l'installation et l'activation, vous pouvez commencer à utiliser `tailwindify` pour vos thèmes :

1.  **Accéder à l'interface d'administration du plugin** :
    *   Dans votre panneau d'administration Azuriom, naviguez vers `Admin > Plugins > tailwindify`.
    *   Vous y trouverez les options de configuration.
2.  **Configurer un thème** :
    *   Dans l'interface de `tailwindify`, sélectionnez le thème pour lequel vous souhaitez activer Tailwind CSS.
    *   Spécifiez les chemins des fichiers source Tailwind (`.css` ou `.scss` avec `@tailwind` directives) et le répertoire de sortie pour le CSS compilé (généralement dans le dossier `public` de votre thème).
    *   Le plugin lancera automatiquement la compilation lors des modifications ou de la première activation.
3.  **Développer votre thème** :
    *   Créez vos fichiers de style Tailwind CSS (e.g., `resources/themes/votretheme/assets/css/style.css` dans votre thème) en utilisant les directives `@tailwind`.
    *   Utilisez les classes Tailwind directement dans vos vues Blade.
    *   Le plugin détectera les changements et recompilera le CSS.
4.  **Visualiser les journaux** :
    *   L'interface d'administration de `tailwindify` vous permettra de consulter les journaux de compilation pour diagnostiquer d'éventuels problèmes.

Exemple de fichier `style.css` de votre thème :

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Vos styles CSS personnalisés ici */
.my-custom-class {
    @apply text-blue-500 font-bold;
}
```

## 🧪 Scripts disponibles

Bien que `tailwindify` gère la compilation automatiquement, vous pourriez trouver utile de comprendre les commandes sous-jacentes ou de les utiliser manuellement pour le développement local :

*   **Installation des dépendances Node.js** :

    ```bash
    npm install # ou yarn install
    ```

*   **Compilation Tailwind CSS** (similaire à ce que fait le plugin en arrière-plan) :

    ```bash
    npx tailwindcss -i ./resources/themes/votretheme/assets/css/style.css -o ./public/css/output.css --minify
    ```

*   **Compilation en mode veille (watch)** pour le développement local :

    ```bash
    npx tailwindcss -i ./resources/themes/votretheme/assets/css/style.css -o ./public/css/output.css --watch
    ```

Ces scripts sont des exemples génériques. Les chemins exacts dépendront de la configuration spécifique de votre thème et du plugin.

## ⚙️ Stack technique

*   **Langage principal** : PHP (pour le plugin Azuriom)
*   **Frontend / Compilation** : JavaScript (Node.js)
*   **CSS Framework** : Tailwind CSS
*   **Moteur de compilation CSS** : PostCSS, Autoprefixer
*   **Plateforme** : Azuriom CMS

## 🗂️ Structure du projet

Voici une structure simplifiée du plugin `tailwindify` et comment il s'interface avec un thème Azuriom typique :

```
/
├── src/                          # Code source PHP du plugin
│   ├── Providers/                # Service Providers
│   └── Controllers/              # Contrôleurs pour l'administration
├── resources/
│   ├── views/                    # Vues Blade de l'interface admin du plugin
│   └── lang/                     # Fichiers de traduction
├── public/                       # Assets publics du plugin
├── config/
│   └── tailwindify.php           # Fichier de configuration du plugin
├── composer.json                 # Dépendances PHP
├── plugin.json                   # Métadonnées du plugin Azuriom
└── README.md
```

**Structure d'un thème Azuriom (impacté par tailwindify) :**

```
/
├── resources/
│   ├── views/                    # Vues Blade du thème
│   └── assets/
│       ├── css/
│       │   └── style.css           # <- Fichier CSS source de Tailwind (@tailwind directives)
│       │   └── custm.css           # <- Fichier CSS compilé et minifier de Tailwind
│       └── js/
├── config/
├── theme.json                    # Métadonnées du thème
└── tailwind.config.js            # Configuration de Tailwind CSS pour le thème
```

## 🤝 Contribution

Nous accueillons avec plaisir toutes les contributions ! Si vous souhaitez aider au développement de `tailwindify`, voici comment procéder :

1.  **Fork** le dépôt `tailwindify` sur GitHub.
2.  **Clonez** votre fork localement :
    ```bash
    git clone https://github.com/YuketsuSh/tailwindify.git
    cd tailwindify
    ```
3.  **Créez une nouvelle branche** pour vos modifications :
    ```bash
    git checkout -b feature/votre-nouvelle-fonctionnalite
    ```
4.  **Effectuez vos modifications** et testez-les.
5.  **Commit** vos changements avec un message clair :
    ```bash
    git commit -m "feat: Ajout d'une nouvelle fonctionnalité X"
    ```
6.  **Push** votre branche vers votre fork GitHub :
    ```bash
    git push origin feature/votre-nouvelle-fonctionnalite
    ```
7.  **Ouvrez une Pull Request (PR)** depuis votre fork vers le dépôt principal.
    *   Assurez-vous de décrire clairement les changements que vous avez apportés et pourquoi.

Nous apprécions votre aide pour rendre ce projet encore meilleur !

## 🪪 Licence

Ce projet est sous licence MIT. Pour plus d'informations, consultez le fichier [LICENSE](https://github.com/YuketsuSh/tailwindify/blob/main/LICENSE).
