<?php
/*session_start();
include 'connexionprojet.php';
$conn=connect();
if (!isset($_SESSION['user_id'])) {
    header("Location: llogin.php");
    exit;
}

if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit;
}

$id_user = $_SESSION['user_id'];
$mode_paiement = $_POST['mode_paiement'] ?? 'à la livraison';

mysqli_query($conn, "INSERT INTO command (date,numclt, mode_paiement)
                     VALUES (NOW() , $id_user, '$mode_paiement')");
$id_commande = mysqli_insert_id($conn);

foreach ($_SESSION['panier'] as $id_prod => $qt) {
    mysqli_query($conn, "INSERT INTO lignedecommande (refprod, nulcmd, quantite)
                         VALUES ( $id_prod,$id_commande, $qt)");
}

unset($_SESSION['panier']);

echo "<h2>Commande validée avec succès !</h2><a href='accueil.php'>Retour à la boutique</a>";
?>*/

session_start();
include 'connexionprojet.php';
$conn = connect();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: llogin.php");
    exit;
}

// Vérifier si le panier est vide
if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
    echo "Votre panier est vide.";
    exit;
}

$id_user = $_SESSION['user_id'];
$mode_paiement = $_POST['mode_paiement'] ?? 'à la livraison';

// Insérer la commande dans la table `command`
$date_actuelle = date("Y-m-d");
$query = "INSERT INTO command (date, numclt, mode_paiement) VALUES ('$date_actuelle', $id_user, '$mode_paiement')";
mysqli_query($conn, $query);

// Récupérer l'ID de la commande insérée
$id_commande = mysqli_insert_id($conn);

// Insérer les produits du panier dans `lignedecommande`
foreach ($_SESSION['panier'] as $refprod => $quantite) {
    // Vérifier que le produit existe avant l'insertion
    $check = mysqli_query($conn, "SELECT reference FROM produit WHERE reference = $refprod");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "INSERT INTO lignedecommande (refprod, nulcmd, quantite) VALUES ($refprod, $id_commande, $quantite)");
    }
}

// Vider le panier
unset($_SESSION['panier']);

echo "<h2>Commande validée avec succès !</h2>";
echo "<a href='accueil.php'>Retour à la boutique</a>";
?>
