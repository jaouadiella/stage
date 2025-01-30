<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - SagemCom</title>
    <style>
        /* Styles existants adaptés */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        .hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f9f9f9;
        }

        .hero img {
            width: 150px;
            margin-bottom: 20px;
        }

        .hero-text {
            text-align: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 400px;
        }

        .hero-text h2 {
            font-size: 24px;
            color: #0056b3;
            margin-bottom: 10px;
        }

        .hero-text p {
            margin-bottom: 20px;
            color: #666;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-control:focus {
            border-color: #0056b3;
            outline: none;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            background-color: #0056b3;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #003d80;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <main>
        <section class="hero">
            <img src="sagem.png" alt="Logo">
            <div class="hero-text">
                <h2>Connexion</h2>
                <p>Veuillez vous connecter pour accéder à votre espace.</p>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="id">ID :</label>
                        <input type="text" id="id" name="id" class="form-control" required placeholder="Votre ID">
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="Votre mot de passe">
                    </div>
                    <button type="submit" class="btn">Se connecter</button>
                </form>
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $id = $_POST['id'] ?? '';
                    $password = $_POST['password'] ?? '';

                    $users = [
                        'admin' => ['id' => 'admin', 'password' => 'admin123'],
                        'consultant' => ['id' => 'consultant', 'password' => 'consult123']
                    ];

                    if ($id === $users['admin']['id'] && $password === $users['admin']['password']) {
                        header("Location: admin.php");
                        exit();
                    } elseif ($id === $users['consultant']['id'] && $password === $users['consultant']['password']) {
                        header("Location: page d'acceuil.php");
                        exit();
                    } else {
                        echo '<p class="error">ID ou mot de passe incorrect.</p>';
                    }
                }
                ?>
            </div>
        </section>
    </main>
</body>
</html>
