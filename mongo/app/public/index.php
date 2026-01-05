<?php
/**
 * TD 2 : Mini-application - Catalogue de produits
 * 
 */

require_once __DIR__ . "/../src/vendor/autoload.php";

use MongoDB\Client;

// Connexion MongoDB
$client = new Client("mongodb://mongo");
$db = $client->chopizza;
$produits = $db->produits;

// Catégorie sélectionnée
$category = $_GET['category'] ?? 'Pizzas';

// Liste des catégories
$categories = $produits->distinct('categorie');

// Produits de la catégorie
$categoryProducts = $produits->find(['categorie' => $category]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chopizza</title>
    <style>
        /* CSS Minimal */
        body { font-family: sans-serif; }
        .active { font-weight: bold; color: red; }
        .card { border: 1px solid #ccc; margin: 10px 0; padding: 10px; }
    </style>
</head>
<body>

    <h1>Chopizza</h1>

    <nav>
        <?php foreach ($categories as $cat): ?>
            <a href="?category=<?php echo urlencode($cat); ?>" class="<?php echo $category === $cat ? 'active' : ''; ?>">
                <?php echo htmlspecialchars($cat); ?>
            </a> |
        <?php endforeach; ?>
    </nav>

    <h2><?php echo htmlspecialchars($category); ?></h2>

    <div>
        <?php foreach ($categoryProducts as $p): ?>
            <div class="card">
                <h3>#<?php echo $p['numero']; ?> - <?php echo htmlspecialchars($p['libelle']); ?></h3>
                <p><?php echo htmlspecialchars($p['description']); ?></p>
                <ul>
                    <?php foreach ($p['tarifs'] as $t): ?>
                        <li><?php echo htmlspecialchars($t['taille']); ?> : <?php echo $t['tarif']; ?>€</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <p><a href="add_product.php">Ajouter un produit</a></p>

</body>
</html>
