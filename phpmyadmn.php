<?php
$host = 'localhost';
$db = 'stage';
$user = 'ella';
$pass = '123';

// Connexion à la base de données
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $password = $_POST['password'] ?? '';

    // Requête SQL
    $stmt = $conn->prepare("SELECT role FROM utilisateurs WHERE identifiant = ? AND mot_de_passe = ?");
    $stmt->bind_param("ss", $id, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['role'] === 'admin') {
            header("Location: admin_dashboard.php");
            exit();
        } elseif ($user['role'] === 'consultant') {
            header("Location: consultant_dashboard.php");
            exit();
        }
    } else {
        echo '<p class="error">ID ou mot de passe incorrect.</p>';
    }
    $stmt->close();
}
$conn->close();
?>
