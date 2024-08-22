<?php
include '../security.php';
include "../connexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['currentPassword']) && !empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
        $id = $_SESSION['id'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (md5($currentPassword) !== $user['password']) {
                $errorMessage = "Le mot de passe actuel est incorrect";
            } elseif ($currentPassword === $newPassword) {
                $errorMessage = "Le nouveau mot de passe ne peut pas être le même que le mot de passe actuel";
            } elseif ($newPassword !== $confirmPassword) {
                $errorMessage = "Le nouveau mot de passe et la confirmation du mot de passe ne correspondent pas";
            } else {
                $hashedPassword = md5($newPassword);
                $stmt = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
                $stmt->bindParam(':password', $hashedPassword);
                $stmt->bindParam(':id', $id);
                if ($stmt->execute()) {
                    header("Location: admin.php");
                } else {
                    $errorMessage = "Erreur lors de la mise à jour du mot de passe";
                }
            }
        } else {
            $errorMessage = "Utilisateur non trouvé";
        }
    } else {
        $errorMessage = "Tous les champs sont obligatoires";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Mettre à jour le mot de passe</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    <link href="../img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .eye-icons {
            position: relative;
        }

        .eye-icons input[type="password"] {
            padding-right: 40px;
        }

        .eye-icons i {
            position: absolute;
            top: 50%;
            right: 10px;
            margin-right: 20px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .text {
            font-size: 24px;
            color: #333;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }

        .error-message {
            font-weight: bold;
            color: red;
        }
    </style>
</head>
<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <?php include "sidbar.php" ?>
        <div class="content">
            <?php include "navbar.php" ?>
            <div class="container-fluid pt-4">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4" style="margin-top: 80px;">
                            <?php if (isset($errorMessage)) : ?>
                                <p class="error-message"> <?php echo $errorMessage ?></p>
                            <?php endif; ?>
                            <h3 class="text">Mettre à jour mot de passe</h3>
                            <br>
                            <form method="post" action="">
                                <div class="form-floating mb-3 eye-icons">
                                    <input type="password" class="form-control" id="floatingPassword1" placeholder="Password" name="currentPassword" value="<?php echo isset($_POST['currentPassword']) ?  $_POST['currentPassword'] : ''; ?>">
                                    <label for="floatingPassword1">Mot de passe actuel</label>
                                    <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('floatingPassword1', this)"></i>
                                </div>
                                <div class="form-floating mb-3 eye-icons">
                                    <input type="password" class="form-control" id="floatingPassword2" placeholder="Password" name="new_password" value="<?php echo isset($_POST['new_password']) ?  $_POST['new_password'] : ''; ?>">
                                    <label for="floatingPassword2">Nouveau mot de passe</label>
                                    <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('floatingPassword2', this)"></i>
                                </div>
                                <div class="form-floating mb-3 eye-icons">
                                    <input type="password" class="form-control" id="floatingPassword3" placeholder="Password" name="confirm_password" value="<?php echo isset($_POST['confirm_password']) ?  $_POST['confirm_password'] : ''; ?>">
                                    <label for="floatingPassword3">Confirmer le mot de passe</label>
                                    <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('floatingPassword3', this)"></i>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="submit">Mettre à jour</button>
                                    <a class="btn btn-secondary me-md-2" href="admin.php" style="color:white">Annuler</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/chart/chart.min.js"></script>
    <script src="../lib/easing/easing.min.js"></script>
    <script src="../lib/waypoints/waypoints.min.js"></script>
    <script src="../lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="../lib/tempusdominus/js/moment.min.js"></script>
    <script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.js"></script>

    <script src="../js/main.js"></script>
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