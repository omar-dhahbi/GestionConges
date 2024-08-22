<?php
include '../security.php';
include "../connexion.php";
$user_id = $_SESSION['id'];
$query = "SELECT u.nom, u.prenom, c.* FROM congés c JOIN users u ON c.user_id = u.id WHERE c.user_id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Employee Interface</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .buttonx {
            text-decoration: none;
            border: 0;
            color: #337ab7;
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
                    <div class="table-responsive">
                        <table id="tableResultats" class="display" style="width:100%">
                            <thead>
                                <tr style="text-align:center">
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Type</th>
                                    <th>Cause</th>
                                    <th>date debut</th>
                                    <th>date fin</th>
                                    <th>Nombre de Jour</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result as $row) : ?>
                                    <tr style="text-align:center">
                                        <td><?php echo $_SESSION['nom']; ?></td>
                                        <td><?php echo $_SESSION['prenom']; ?></td>
                                        <td><?php echo $row['type']; ?></td>
                                        <?php if ($row['type'] === 'maladie') : ?>
                                            <td> <a href="../photo/<?php echo $row['photo']; ?>" class="buttonx">Voir photo</a>
                                            </td>
                                        <?php else : ?>
                                            <td><?php echo $row['CauseConge']; ?></td>
                                        <?php endif; ?>
                                        <td><?php echo $row['DateDebut']; ?></td>
                                        <td><?php echo $row['DateFin']; ?></td>
                                        <td><?php echo $row['NbrJour']; ?></td>
                                        <td>
                                            <?php if ($row['status'] === 'accepter') : ?>
                                                <i class="fas fa-check text-success"></i>
                                            <?php elseif ($row['status'] === 'refuser') : ?>
                                                <i class="fas fa-times text-danger"></i>
                                            <?php else : ?>
                                                <i class="far fa-clock text-warning"></i>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
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
</body>

</html>