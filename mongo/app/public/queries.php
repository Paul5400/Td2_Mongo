<?php
/**
 * TD 2 : Requêtes PHP sur MongoDB
 */

require_once __DIR__ . "/../src/vendor/autoload.php";

use MongoDB\Client;

// Connexion à la base de données
$client = new Client("mongodb://mongo");
$db = $client->chopizza;
$produits = $db->produits;
$recettes = $db->recettes;

echo "<h1>TD 2 : Requêtes en PHP</h1>";

// 1. afficher la liste des produits: numero, categorie, libelle
echo "<h2>1. Liste des produits (numéro, catégorie, libellé)</h2>";
$cursor = $produits->find([], ['projection' => ['numero' => 1, 'categorie' => 1, 'libelle' => 1, '_id' => 0]]);
echo "<ul>";
foreach ($cursor as $doc) {
    echo "<li>#" . $doc['numero'] . " [" . $doc['categorie'] . "] " . $doc['libelle'] . "</li>";
}
echo "</ul>";

// 2. afficher le produit numéro 6, préciser : libellé, catégorie, description, tarifs
echo "<h2>2. Détails du produit numéro 6</h2>";
$p6 = $produits->findOne(['numero' => 6]);
if ($p6) {
    echo "<p><strong>Libellé :</strong> " . $p6['libelle'] . "</p>";
    echo "<p><strong>Catégorie :</strong> " . $p6['categorie'] . "</p>";
    echo "<p><strong>Description :</strong> " . $p6['description'] . "</p>";
    echo "<strong>Tarifs :</strong><ul>";
    foreach ($p6['tarifs'] as $t) {
        echo "<li>" . $t['taille'] . " : " . $t['tarif'] . "€</li>";
    }
    echo "</ul>";
}

// 3. liste des produits dont le tarif en taille normale est <= 3.0
echo "<h2>3. Produits avec tarif taille 'normale' <= 3.0€</h2>";
$cursor = $produits->find(['tarifs' => ['$elemMatch' => ['taille' => 'normale', 'tarif' => ['$lte' => 3.0]]]]);
echo "<ul>";
foreach ($cursor as $doc) {
    echo "<li>" . $doc['libelle'] . "</li>";
}
echo "</ul>";

// 4. liste des produits associés à 4 recettes
echo "<h2>4. Produits associés à exactement 4 recettes</h2>";
$cursor = $produits->find(['recettes' => ['$size' => 4]]);
echo "<ul>";
foreach ($cursor as $doc) {
    echo "<li>" . $doc['libelle'] . " (" . count($doc['recettes']) . " recettes)</li>";
}
echo "</ul>";

// 5. afficher le produit n°6, compléter en listant les recettes associées (nom et difficulté)
echo "<h2>5. Recettes associées au produit n°6</h2>";
if ($p6) {
    echo "<p>Produit : <strong>" . $p6['libelle'] . "</strong></p>";
    if (isset($p6['recettes']) && count($p6['recettes']) > 0) {
        $associated_recettes = $recettes->find(['_id' => ['$in' => $p6['recettes']]]);
        echo "<ul>";
        foreach ($associated_recettes as $r) {
            echo "<li>" . $r['nom'] . " (Difficulté : " . $r['difficulte'] . ")</li>";
        }
        echo "</ul>";
    }
}

// 6. créer une fonction qui reçoit en paramètre un numéro de produit et une taille et retourne un
// tableau contenant les données descriptives de ce produit : numéro, libellé, catégorie, taille,
// tarif ; utiliser cette fonction et afficher le résultat en json.
echo "<h2>6. Fonction descriptive (retour JSON)</h2>";

function getProductInfo($produitsCollection, $numero, $taille) {
    // Recherche du produit avec le filtre sur le numéro et la taille dans le tableau des tarifs
    $p = $produitsCollection->findOne(
        ['numero' => $numero, 'tarifs.taille' => $taille],
        ['projection' => ['numero' => 1, 'libelle' => 1, 'categorie' => 1, 'tarifs.$' => 1, '_id' => 0]]
    );
    
    if ($p) {
        return [
            'numero' => $p['numero'],
            'libelle' => $p['libelle'],
            'categorie' => $p['categorie'],
            'taille' => $p['tarifs'][0]['taille'],
            'tarif' => $p['tarifs'][0]['tarif']
        ];
    }
    return null;
}

$info = getProductInfo($produits, 6, 'normale');
echo "<pre>" . json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "</pre>";
