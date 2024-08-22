<?php

include "../security.php";
include "../connexion.php";



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Details Employee</title>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-image {
            max-width: 150px;
            max-height: 200px;
            border: 2px solid black;
            border-radius: 50%;
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <?php include "sidbar.php"; ?>
        <div class="content">
            <?php include "navbar.php"; ?>
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                    </div>
                    <div class="table-responsive">
                        <h3 style="text-align:center;"><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h3>
                        <img src="../photo/<?php echo $_SESSION['photo']; ?>" alt="User Photo" class="profile-image">
                        <br><br><br>
                        <div class="row">
                            <div class="col-md-10 offset-md-1">
                                <table class="table text-center">
                                    <tr>
                                        <td>Nom</td>
                                        <td><?php echo $_SESSION['nom']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Prenom</td>
                                        <td><?php echo $_SESSION['prenom']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Numero de telephone</td>
                                        <td><?php echo $_SESSION['tel']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td><?php echo $_SESSION['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Role</td>
                                        <td><?php echo $_SESSION['role']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <a href="updateProfil.php?id=<?php echo $_SESSION['id']; ?>" class="btn btn-primary">Mettre à jour</a>
                </div>
            </div>
        </div>
    </div>
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
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
    <!-- Initialize Calendar -->
    <!-- Template Javascript -->
    <script src="../js/main.js"></script>
</body>

</html>
