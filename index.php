<?php
require_once '_connec.php';

$pdo = new \PDO(DSN, USER, PASS);

// A exécuter afin de tester le contenu de votre table friend
$query = "SELECT * FROM friend";
$statement = $pdo->query($query);

// On veut afficher notre résultat via un tableau associatif (PDO::FETCH_ASSOC)
$friendsArray = $statement->fetchAll(PDO::FETCH_ASSOC);

echo "<ul>";
foreach($friendsArray as $friend) {
    echo "<li>" . $friend['firstname'] . ' ' . $friend['lastname'] . "</li>";
}
echo "</ul>";

// Affichage du formulaire pour ajouter un nouveau friend
echo "
    <form method='POST'>
        <label for='firstname'>Firstname:</label>
        <input type='text' id='firstname' name='firstname' required>
        <br>
        <label for='lastname'>Lastname:</label>
        <input type='text' id='lastname' name='lastname' required>
        <br>
        <button type='submit'>Add Friend</button>
    </form>
";

// Traitement de l'insertion d'un nouveau friend dans la base de données
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];

    // Insertion dans la base de données via une requête préparée
    $query = "INSERT INTO friend (firstname, lastname) VALUES (:firstname, :lastname)";
    $statement = $pdo->prepare($query);
    $statement->bindValue(':firstname', $firstname, PDO::PARAM_STR);
    $statement->bindValue(':lastname', $lastname, PDO::PARAM_STR);
    $statement->execute();

    // Redirection vers la page d'accueil pour actualiser la liste des friends
    header('Location: index.php');
    exit;
}
?>