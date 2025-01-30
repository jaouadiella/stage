<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=stage;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$data = [];
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serial_number = $_POST['num_serie'] ?? '';

    if (!empty($serial_number)) {
        $stmt = $bdd->prepare("SELECT * FROM num_serie WHERE num_serie = :num_serie");
        $stmt->execute([':num_serie' => $serial_number]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$data) {
            $message = "Aucun historique trouvé pour ce numéro de série.";
        }
    } else {
        $message = "Veuillez entrer un numéro de série.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Produits</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .nav-bar {
            background-color: #0056b3;
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            color: #0056b3;
            margin: 0;
        }
        .form-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }
        .input-zone {
            width: 100%;
        }
        .input-zone label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: bold;
        }
        .input-zone input {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }
        .input-zone input:focus {
            border-color: #0056b3;
            outline: none;
        }
        .buttons {
            text-align: center;
            margin-top: 10px;
        }
        .buttons .btn {
            padding: 12px 20px;
            margin: 0 10px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .buttons .valid {
            background-color: #28a745;
        }
        .buttons .valid:hover {
            background-color: #218838;
        }
        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .table th {
            background-color: #0056b3;
            color: white;
            padding: 10px;
            text-align: left;
            border: none;
        }
        .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .info-message {
            text-align: center;
            background-color: #17a2b8;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="nav-bar">Historique des Produits</div>
    <div class="container">
        <div class="header">
            <h1>Rechercher un produit</h1>
        </div>
        <form method="POST" action="">
            <div class="form-container">
                <div class="input-zone">
                    <label for="serial_number">Numéro de Série</label>
                    <input type="text" id="serial_number" name="serial_number" placeholder="Entrez le numéro de série" required>
                </div>
            </div>
            <div class="buttons">
                <button type="submit" class="btn valid">Rechercher</button>
                
            </div>
        </form>
        <?php if (!empty($message)): ?>
            <p class="info-message"><?= $message ?></p>
        <?php endif; ?>
        <?php if (!empty($data)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Numéro de Série</th>
                        <th>Code Produit</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <td><?= $row['num_serie'] ?></td>
                            <td><?= $row['product_code'] ?></td>
                            <td><?= $row['description'] ?></td>
                            <td><?= $row['date'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
