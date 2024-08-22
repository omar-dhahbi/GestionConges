<?php
include '../security.php';

include "../connexion.php";

$id = $_GET['id'];

$query = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['submit'])) {
  
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $photo = $_FILES['photo']['name'];
    if ($photo != "") {
        move_uploaded_file($_FILES["photo"]["tmp_name"], "../photo/" . $_FILES["photo"]["name"]);
    } else {
        $photo = $user['photo'];
    }
    if (empty($nom) || empty($prenom) || empty($email) || empty($role)) {
        $errorMessage = "Tous les champs  sont obligatoires.";
    }
    $query = "UPDATE users SET nom = :nom, prenom = :prenom, tel = :tel, email = :email, role = :role, photo = :photo WHERE id = :id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':tel', $tel);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':photo', $photo);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: details.php?id=$id");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Mettre à jour le mot de employee</title>
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

        .profile-image {
            max-width: 150px;
            max-height: 200px;
            border: 2px solid black;
            border-radius: 50%;
            margin: auto;
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
                                <?php unset($errorMessage); ?>
                            <?php endif; ?>

                            <h3 class="text">Mettre à jour mot de employee</h3>
                            <br>
                            <div style="display: flex; justify-content: center;">
                                <img src="../photo/<?php echo $user['photo']; ?>" alt="User Photo" class="profile-image">
                            </div>
                            <br><br>
                            <form method="post" action="" enctype="multipart/form-data">
                                
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nom" placeholder="nom" name="nom" value="<?php echo $user['nom']; ?>">
                                    <label for="nom">Nom</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="prenom" placeholder="prenom" name="prenom" value="<?php echo $user['prenom']; ?>">
                                    <label for="prenom">Prenom</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" placeholder="email" name="email" value="<?php echo $user['email']; ?>">
                                    <label for="email">email</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="tel" placeholder="tel" name="tel" value="<?php echo $user['tel']; ?>">
                                    <label for="tel">telephone</label>
                                </div>

                                <div class="mb-3">
                                    <a href="../photo/<?php echo $user['photo'] ?>">Voir photo</a><br>
                                    <input type="file" class="form-control" type="file" id="photo" name="photo">
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="role" placeholder="role" name="role" value="<?php echo $user['role']; ?>">
                                    <label for="role">role</label>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="submit">Mettre à jour</button>
                                    <button class="btn btn-secondary me-md-2" type="button"><a href="admin.php" style="color:white">Annuler</a></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>