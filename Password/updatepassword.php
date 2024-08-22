<?php
include "../connexion.php";
if (isset($_POST['Continuer'])) {
    if (!empty($_POST['code']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $code = $_POST['code'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE code = :code");
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($user) {
            if ($newPassword != $confirmPassword) {
                $errorMessage = "Le nouveau mot de passe et la confirmation du mot de passe ne correspondent pas";
            } else {
                $hashedPassword = md5($newPassword);
                $stmt = $conn->prepare("UPDATE users SET password = :password, code = NULL WHERE code = :code");
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':code', $code);
                if ($stmt->execute()) {
                    header("location:../index.php");
                } else {
                    $errorMessage = "Erreur lors de la mise à jour du mot de passe";
                }
            }
        } else {
            $errorMessage = "Code incorrect";
        }
    } else {
        $errorMessage = "Tous les champs sont obligatoires";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 80px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 20px;
            font-size: 24px;
        }

        .card-body {
            padding: 30px;
        }

        .form-group label {
            font-weight: 600;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 18px;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }

        .eye-icons {
            position: relative;
        }

        .eye-icons input[type="password"] {
            padding-right: 40px;
        }

        .eye-icons i {
            position: absolute;
            top: 50%;
            margin-top: 15px;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Entrez le code de sécurité
                    </div>
                    <div class="card-body">
                        <?php if (isset($errorMessage)) : ?>
                            <p class="error-message"><?php echo $errorMessage; ?></p>
                            <?php unset($errorMessage); ?>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="code">Code</label>
                                <input type="text" class="form-control" id="code" name="code" placeholder="Code" value="<?php echo isset($_POST['code']) ? $_POST['code'] : ''; ?>" required>
                            </div>
                            <div class="form-group eye-icons">
                                <label for="new_password">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Nouveau mot de passe" value="<?php echo isset($_POST['new_password']) ? $_POST['new_password'] : ''; ?>" required>
                                <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('new_password', this)"></i>
                            </div>
                            <div class="form-group eye-icons">
                                <label for="confirm_password">Confirmer le nouveau mot de passe</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirmer le nouveau mot de passe" value="<?php echo isset($_POST['confirm_password']) ? $_POST['confirm_password'] : ''; ?>" required>
                                <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('confirm_password', this)"></i>
                            </div>

                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="Continuer">Continuer</button>
                                <button type="button" class="btn btn-secondary ml-2" onclick="window.location.href='index.php'">Annuler</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function togglePasswordVisibility(inputId, icon) {
            var passwordInput = document.getElementById(inputId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }
    </script>

</body>

</html>