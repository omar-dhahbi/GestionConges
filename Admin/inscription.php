<?php
include '../security.php';
include '../connexion.php';

if (isset($_POST['inscription'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['passwordconfirmation'];
    $photo = $_FILES['photo']['name'];

    move_uploaded_file($_FILES["photo"]["tmp_name"], "../photo/" . $_FILES["photo"]["name"]);
    $query_check_email = "SELECT email FROM users WHERE email = :email";
    $check_email = $conn->prepare($query_check_email);
    $check_email->execute([':email' => $email]);
    $count = $check_email->rowCount();
    if ($count > 0) {
        $_SESSION['status'] = "<span class='error-message'>L'email existe déjà</span>";
        header("location:inscription.php");
        exit;
    }
    if (empty($nom) || empty($prenom) || empty($email) || empty($password) || empty($cpassword)) {
        $_SESSION['status'] =  "<span class='error-message'>Veuillez remplir tous les champs.</span>";
        header("location:inscription.php");
        exit;
    }
    if ($password !== $cpassword) {
        $_SESSION['status'] =  "<span class='error-message'>Les mots de passe ne correspondent pas</span>";
        header("location:inscription.php");
        exit;
    }

    $passwordHash = md5($password);

    $query = "INSERT INTO users(nom, prenom, tel, email, password, photo) VALUES(:nom, :prenom, :tel, :email, :passwordHash, :photo)";
    $stmt = $conn->prepare($query);
    $result = $stmt->execute([
        ':nom' => $nom,
        ':prenom' => $prenom,
        ':tel' => $tel,
        ':email' => $email,
        ':passwordHash' => $passwordHash,
        ':photo' => $photo
    ]);

    if ($result) {
        header("location:admin.php");
        exit;
    } else {
        $_SESSION['status'] = "<span class='error-message'>Erreur lors de l'inscription</span>";
        header("location:inscription.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ajouter un employé</title>
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
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <?php include "sidbar.php"; ?>
        <div class="content">
            <?php include "navbar.php" ?>
            <div class="container-fluid pt-4">
                <div class="row justify-content-center">
                    <div class="col-sm-12 col-xl-6">
                        <div class="bg-light rounded h-100 p-4">

                            <h3 class="text">Ajout employee</h3>
                            <br><br>
                            <form method="post" action="" enctype="multipart/form-data">
                                <?php
                                if (isset($_SESSION['status'])) {
                                    echo $_SESSION['status'];
                                    unset($_SESSION['status']);
                                }
                                ?>
                                <br>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nom" placeholder="nom" name="nom" value="<?php echo isset($_POST['nom']) ? $_POST['nom'] : ''; ?>">
                                    <label for="nom">Nom</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="prenom" placeholder="prenom" name="prenom" value="<?php echo isset($_POST['prenom']) ? $_POST['prenom'] : ''; ?>">
                                    <label for="prenom">Prenom</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="tel" placeholder="tel" name="tel" value="<?php echo isset($_POST['tel']) ? $_POST['tel'] : ''; ?>">
                                    <label for="tel">telephone</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" placeholder="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                                    <label for="email">email</label>
                                </div>
                                <div class="form-floating mb-3 eye-icons">
                                    <input type="password" class="form-control" id="password" placeholder="Password" name="password" value="<?php echo isset($_POST['password']) ?  $_POST['password'] : ''; ?>">
                                    <label for="password">Password</label>
                                    <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('password', this)"></i>
                                </div>
                                <div class="form-floating mb-3 eye-icons">
                                    <input type="password" class="form-control" id="passwordconfirmation" placeholder="Password" name="passwordconfirmation" value="<?php echo isset($_POST['passwordconfirmation']) ? $_POST['passwordconfirmation'] : ''; ?>">
                                    <label for="passwordconfirmation">Confirmer le mot de passe</label>
                                    <i class="fas fa-eye-slash" onclick="togglePasswordVisibility('passwordconfirmation', this)"></i>
                                </div>



                                <div class="mb-3">
                                    <input type="file" class="form-control" id="photo" name="photo">
                                </div>

                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="inscription">Ajouter</button>
                                    <button class="btn btn-secondary me-md-2" type="button"><a href="admin.php" style="color:white">Annuler</a></button>
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