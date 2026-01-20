# TD 2 MongoDB - Projet Chopizza

Ce projet est une application web de démonstration utilisant **MongoDB** comme base de données NoSQL pour la gestion d'un catalogue de pizzas.

## 🚀 Stack Technique

*   **Backend** : PHP 8.3 (CLI Server)
*   **Base de données** : MongoDB (NoSQL orienté document)
*   **Conteneurisation** : Docker & Docker Compose
*   **Interface outils** : Mongo-Express (administration BDD)

## 📁 Structure du Projet

*   `app/` : Code source de l'application PHP.
    *   `public/index.php` : Catalogue dynamique des produits avec filtrage.
    *   `public/add_product.php` : Formulaire d'ajout de nouveaux produits.
    *   `public/queries.php` : Scripts de test des requêtes MongoDB.
*   `build/` : Configuration de l'image Docker PHP.
*   `data/` : Jeux de données JSON pour l'importation.
*   `rapport_td_2.html` : Compte rendu complet du TD (Style PratiLib).

## 🛠️ Installation et Lancement

### 1. Démarrer l'infrastructure
```bash
docker-compose up -d
```

### 2. Accéder aux services
*   **Application Web** : [http://localhost:12080](http://localhost:12080)
*   **Mongo-Express** : [http://localhost:8081](http://localhost:8081)

### 3. Importation des données initiales
Si la base est vide, vous pouvez importer les données via le conteneur mongo :
```bash
docker exec -it mongo mongoimport --db chopizza --collection produits --jsonArray < /var/data/pizzashop.produits.json
```

## 📝 Rapport de TD
Le rapport détaillé incluant les requêtes Shell, les analyses PHP et les captures d'écran est disponible dans le fichier [rapport_td_2.html](./rapport_td_2.html).

---
*Projet réalisé par Paul Andrieu (DWM-2)*
