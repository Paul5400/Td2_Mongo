<?php
/**
 * TD 2 : Mini-application - Ajout de produit
 * 
 */

require_once __DIR__ . "/../src/vendor/autoload.php";

use MongoDB\Client;

$client = new Client("mongodb://mongo");
$db = $client->chopizza;
$produits = $db->produits;

$message = "";

// Traitement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = (int)$_POST['numero'];
    $libelle = $_POST['libelle'];
    $categorie = $_POST['categorie'];
    $description = $_POST['description'];
    
    $tarifs = [];
    if (!empty($_POST['tarif_normale'])) {
        $tarifs[] = ['taille' => 'normale', 'tarif' => (float)$_POST['tarif_normale']];
    }
    if (!empty($_POST['tarif_grande'])) {
        $tarifs[] = ['taille' => 'grande', 'tarif' => (float)$_POST['tarif_grande']];
    }

    try {
        $produits->insertOne([
            'numero' => $numero,
            'libelle' => $libelle,
            'categorie' => $categorie,
            'description' => $description,
            'tarifs' => $tarifs,
            'recettes' => []
        ]);
        $message = "OK !";
    } catch (Exception $e) {
        $message = "Erreur : " . $e->getMessage();
    }
}

$categories = $produits->distinct('categorie');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter</title>
</head>
<body>

    <h1>Ajouter un produit</h1>

    <?php if ($message): ?>
        <p><strong><?php echo $message; ?></strong></p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label>N° :</label>
            <input type="number" name="numero" required>
        </div>
        <div>
            <label>Nom :</label>
            <input type="text" name="libelle" required>
        </div>
        <div>
            <label>Catégorie :</label>
            <select name="categorie" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Description :</label><br>
            <textarea name="description" required></textarea>
        </div>
        <div>
            <label>Prix Normale :</label>
            <input type="number" step="0.01" name="tarif_normale">
        </div>
        <div>
            <label>Prix Grande :</label>
            <input type="number" step="0.01" name="tarif_grande">
        </div>
        <button type="submit">Enregistrer</button>
    </form>

    <p><a href="index.php">Retour</a></p>

</body>
</html>
