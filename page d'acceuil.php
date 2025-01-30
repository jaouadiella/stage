<?php
try {
    $bdd = new PDO('mysql:host=localhost;dbname=stage;charset=utf8', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

$message = '';
$serialData = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serialNumber = trim($_POST['serial_number'] ?? '');
    $validationType = $_POST['validation_type'] ?? '';
    $commentaire = trim($_POST['commentaire'] ?? '');
    $choixReprise = $_POST['choix_reprise'] ?? null;

    if (empty($serialNumber)) {
        $message = "Veuillez remplir la zone de saisie.";
    } else {
        try {
            $query = $bdd->prepare("SELECT * FROM num_series WHERE num_serie = :num_serie");
            $query->execute(['num_serie' => $serialNumber]);
            $serialData = $query->fetch(PDO::FETCH_ASSOC);

            $panneQuery = $bdd->prepare("SELECT COUNT(*) FROM num_series WHERE num_serie = :num_serie AND id_status_numserie = 32");
            $panneQuery->execute(['num_serie' => $serialNumber]);
            $hasPanne = $panneQuery->fetchColumn() > 0;

            if ($hasPanne) {
                $message = "Erreur : Le numéro de série $serialNumber est associé à un/des produit en panne.";
            } else {
                if ($serialData) {
                    $message = "Numéro de série trouvé : " . htmlspecialchars($serialNumber);
                } else {
                    $idProduit = 1;
                    $insertQuery = $bdd->prepare("INSERT INTO num_series (num_serie, id_produit, id_status_numserie) VALUES (:num_serie, :id_produit, 0)");
                    $insertQuery->execute([
                        'num_serie' => $serialNumber,
                        'id_produit' => $idProduit
                    ]);
                    $message = "Numéro de série ajouté avec succès : " . htmlspecialchars($serialNumber);
                }

                if ($validationType === 'non-confirmite' && !empty($commentaire)) {
                    $message .= " | Commentaire enregistré : " . htmlspecialchars($commentaire);
                } elseif ($validationType === 'reprise' && $choixReprise) {
                    $message .= " | Raison de reprise : " . htmlspecialchars($choixReprise);
                }
            }
        } catch (PDOException $e) {
            $message = "Erreur : " . $e->getMessage();
        }
    }
}

$queryFlashed = "SELECT id_num_serie_enfant FROM lien_num_serie";
$produitsFlashed = $bdd->query($queryFlashed)->fetchAll(PDO::FETCH_ASSOC);

$queryPannes = "SELECT id_num_serie FROM num_series WHERE id_status_numserie = 32";
$produitsPannes = $bdd->query($queryPannes)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sagemcom - Validation</title>
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
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        input[type="text"] {
            width: 60%;
            padding: 12px;
            font-size: 16px;
            margin: 10px 0 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .buttons .btn {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn.valide { background-color: #28a745; color: white; }
        .btn.non-confirmite { background-color: #dc3545; color: white; }
        .btn.reprise { background-color: #ffa500; color: white; }
        .info-message {
            text-align: center;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }
        .info-message.success { background-color: #28a745; color: white; }
        .info-message.error { background-color: #dc3545; color: white; }
        .tables-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-top: 30px;
        }
        .small-table {
            width: 48%;
            border-collapse: collapse;
        }
        .small-table th, .small-table td {
            padding: 6px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .small-table th { background-color: #0056b3; color: white; }
    </style>
</head>
<body>
    <div class="nav-bar">SagemCom Mode: AQP Packout: 4.8.5</div>
    <div class="container">
        <header><h1>Sagemcom - Validation</h1></header>
        <form method="POST">
            <label for="serial_number">Zone de saisie</label>
            <input type="text" name="serial_number" id="serial_number" placeholder="Entrez le numéro de série" required>
            <div class="buttons">
                <button type="submit" name="validation_type" value="valide" class="btn valide">Valide</button>
                <button type="submit" name="validation_type" value="non-confirmite" class="btn non-confirmite" id="btn-non-confirmite">Non-Confirmité</button>
                <button type="submit" name="validation_type" value="reprise" class="btn reprise" id="btn-reprise">Reprise</button>
            </div>
            <div id="commentaire-container" style="display: none; margin-top: 10px;">
                <label for="commentaire">Précisez la non-conformité :</label>
                <textarea name="commentaire" id="commentaire" rows="4" style="width: 60%;"></textarea>
            </div>
            <div id="reprise-container" style="display: none; margin-top: 10px;">
                <label for="choix_reprise">Raison de reprise :</label>
                <select name="choix_reprise" id="choix_reprise" style="width: 60%;">
                    <option value="raison1">Cosmetique emballage palletisation</option>
                    <option value="raison2">Cosmetique palletisation</option>
                    <option value="raison3">Cosmetique emballage mis-en carton palletisation</option>
                </select>
            </div>
        </form>

        <?php if (!empty($message)): ?>
            <div class="info-message <?= strpos($message, 'Erreur') === 0 ? 'error' : 'success' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($produitsFlashed) || !empty($produitsPannes)): ?>
        <div class="tables-container">
            <table class="small-table">
                <thead>
                    <tr><th>Produits flashés</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($produitsFlashed as $produit): ?>
                        <tr><td><?= htmlspecialchars($produit['id_num_serie_enfant']) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <table class="small-table">
                <thead>
                    <tr><th>Produits en panne</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($produitsPannes as $produit): ?>
                        <tr><td><?= htmlspecialchars($produit['id_num_serie']) ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>

    <script>
        document.getElementById('btn-non-confirmite').addEventListener('click', function() {
            document.getElementById('commentaire-container').style.display = 'block';
            document.getElementById('reprise-container').style.display = 'none';
        });

        document.getElementById('btn-reprise').addEventListener('click', function() {
            document.getElementById('reprise-container').style.display = 'block';
            document.getElementById('commentaire-container').style.display = 'none';
        });
    </script>
</body>
</html>
