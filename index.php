<?php
session_start();
include "connexion.php";
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    if (empty($email) || empty($password)) {
        $_SESSION['status'] = "Veuillez remplir tous les champs.";
    } else {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            if ($user['status'] == true) {
                if (md5($password) === $user['password']) {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['photo'] = $user['photo'];
                    $_SESSION['tel'] = $user['tel'];
                    if ($user['role'] == "admin") {
                        header("location: Admin/admin.php");
                    } elseif ($user['role'] == "employee") {
                        header("location: Employee/employee.php");
                    } else {
                        header("location: index.php");
                    }
                } else {
                    $_SESSION['status'] = "Mot de passe incorrect";
                }
            } else {
                $_SESSION['status'] = "Vous n'avez pas accès à la connexion";
            }
        } else {
            $_SESSION['status'] = "Email non existant";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        .form-check-label {
            font-weight: normal;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .forgot-password {
            margin-top: 5px;
            text-align: right;
            color: #007bff;
            font-size: 14px;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
        
        .eye-icons {
            position: relative;
        }
        
        .eye-icons i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            margin-top: 15px;
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
                        Connexion
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['status'])) : ?>
                            <p class="error-message"> <?php echo $_SESSION['status']; ?></p>
                            <?php unset($_SESSION['status']); ?>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Adresse email</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Entrer votre email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                            </div>
                            <div class="form-group eye-icons">
                                <label for="exampleInputPassword1">Mot de passe</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Mot de passe" value="<?php echo isset($_POST['password']) ? $_POST['password'] : ''; ?>">
                                <i class="fas fa-eye-slash" onclick="togglePasswordVisibility(this)"></i>
                            </div>
                            <div class="form-row align-items-center">
                                <div class="col-auto">
                                </div>
                                <div class="col ml-auto">
                                    <small class="form-text text-muted forgot-password"><a href="Password/ForgetPassword.php">Mot de passe oublié?</a></small>
                                </div>
                            </div>
                            <br>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function togglePasswordVisibility(icon) {
            var passwordInput = document.getElementById("exampleInputPassword1");
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
