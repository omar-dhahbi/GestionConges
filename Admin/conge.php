<?php
include "../security.php";
include "../connexion.php";

function accepterConge($id, $conn)
{
    $stmt = $conn->prepare("SELECT type, NbrJour, user_id FROM congés WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $conge = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($conge['type'] === 'simple') {
        $query = $conn->prepare("UPDATE users SET NbJourConge = NbJourConge - :NbrJour WHERE id = :user_id");
        $query->bindParam(':NbrJour', $conge['NbrJour']);
        $query->bindParam(':user_id', $conge['user_id']);
        $query->execute();
    }
    $update = $conn->prepare("UPDATE congés SET status = 'accepter' WHERE id = :id");
    $update->bindParam(':id', $id);
    $update->execute();
}
function refuserConge($id, $conn)
{
    $stmt = $conn->prepare("UPDATE congés SET status = 'refuser' WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

$attenteConges = getCongesAttente($conn);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($attenteConges as $conge) {
        $id = $conge['id'];
        if (isset($_POST["accept_$id"])) {
            accepterConge($id, $conn);
        } elseif (isset($_POST["refuse_$id"])) {
            refuserConge($id, $conn);
        }
    }
}


function getCongesAccepter($conn)
{
    $stmt = $conn->prepare("SELECT c.*, u.nom AS user_nom, u.prenom AS user_prenom FROM congés c INNER JOIN users u ON c.user_id = u.id WHERE c.status = 'accepter'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCongesRefuser($conn)
{
    $stmt = $conn->prepare("SELECT c.*, u.nom AS user_nom, u.prenom AS user_prenom FROM congés c INNER JOIN users u ON c.user_id = u.id WHERE c.status = 'refuser'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCongesAttente($conn)
{
    $stmt = $conn->prepare("SELECT c.*, u.nom AS user_nom, u.prenom AS user_prenom FROM congés c INNER JOIN users u ON c.user_id = u.id WHERE c.status = 'attente'");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$acceptesConges = getCongesAccepter($conn);
$refusesConges = getCongesRefuser($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Liste des congés</title>
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
        .table {
            border-collapse: collapse;
            width: 90%;
            margin-left: 20px;
            margin-top: 20px !important;
        }

        .divTables {
            display: block;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .nav-tabs {
            margin-left: 200px;
            margin-top: 100px;
        }

        .All {
            font-weight: bold;
            text-align: center;
        }

        .button {
            text-decoration: none;
            border: none;
        }

        .buttonx {
            text-decoration: none;
            border: 0;
            color: #337ab7;
        }

        h3 {
            font-size: 24px;
            color: #333;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-xxl position-relative bg-white d-flex p-0">
        <?php include_once "sidbar.php" ?>
        <div class="content">
            <?php include "navbar.php" ?>
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light text-center rounded p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                    </div>
                    <div class="divTables container">

                        <div class="All" mat-subheader>
                            <h3> LISTE DES CONGÉS </h3>
                        </div>

                        <ul class="nav nav-tabs mb-3" id="ex1" role="tablist" style="margin-right: 300px;">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="ex1-tab-1" data-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="false" onclick="buttonTab('attente')">
                                    <i class="material-icons">refresh</i>CONGÉS EN ATTENTE
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ex1-tab-2" data-toggle="tab" href="#ex1-tabs-2" role="tab" aria-controls="ex1-tabs-2" aria-selected="true" onclick="buttonTab('accepter')">
                                    <i class="material-icons">check</i>CONGÉS ACCEPTÉS
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="ex1-tab-3" data-toggle="tab" href="#ex1-tabs-3" role="tab" aria-controls="ex1-tabs-3" aria-selected="false" onclick="buttonTab('refuser')">
                                    <i class="material-icons">close</i>CONGÉS REFUSÉS
                                </button>
                            </li>
                        </ul>
                        <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Type de congé</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Nombre de jours</th>
                                        <th>Cause de congés</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($attenteConges as $conge) : ?>
                                        <tr>
                                            <td><?php echo $conge['id']; ?></td>
                                            <td><?php echo $conge['user_nom']; ?></td>
                                            <td><?php echo $conge['user_prenom']; ?></td>
                                            <td><?php echo $conge['type']; ?></td>
                                            <td><?php echo $conge['DateDebut']; ?></td>
                                            <td><?php echo $conge['DateFin']; ?></td>
                                            <td><?php echo $conge['NbrJour']; ?></td>
                                            <?php if ($conge['type'] === 'simple') : ?>

                                                <td><?php echo $conge['CauseConge']; ?></td>
                                            <?php elseif ($conge['type'] === 'maladie') : ?>
                                                <td> <a href="../photo/<?php echo $conge['photo'] ?>" name="buttonx">Voir photo</a></td>
                                            <?php endif; ?>
                                            <td>
                                                <form method="post">
                                                    <input type="hidden" name="id" value="<?php echo $conge['id']; ?>">
                                                    <button type="submit" class="button" name="accept_<?php echo $conge['id']; ?>">
                                                        <i class="material-icons text-success">check_circle</i>
                                                    </button>refuse_
                                                    <button type="submit" class="button" name="<?php echo $conge['id']; ?>">
                                                        <i class="material-icons text-danger">cancel</i>
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Type de congé</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Nombre de jours</th>
                                        <th>Cause de congés</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($acceptesConges as $conge) : ?>
                                        <tr>
                                            <td><?php echo $conge['id']; ?></td>
                                            <td><?php echo $conge['user_nom']; ?></td>
                                            <td><?php echo $conge['user_prenom']; ?></td>
                                            <td><?php echo $conge['type']; ?></td>
                                            <td><?php echo $conge['DateDebut']; ?></td>
                                            <td><?php echo $conge['DateFin']; ?></td>
                                            <td><?php echo $conge['NbrJour']; ?></td>
                                            <?php if ($conge['type'] === 'simple') : ?>

                                                <td><?php echo $conge['CauseConge']; ?></td>
                                            <?php elseif ($conge['type'] === 'maladie') : ?>
                                                <td> <a href="../photo/<?php echo $conge['photo'] ?>" name="buttonx">Voir photo</a></td>
                                            <?php endif; ?>
                                            <td>
                                                <i class="fas fa-check text-success" style="margin-left :20px;"></i>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="ex1-tabs-3" role="tabpanel" aria-labelledby="ex1-tab-3">
                            <table class="table text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Type de congé</th>
                                        <th>Date de début</th>
                                        <th>Date de fin</th>
                                        <th>Nombre de jours</th>
                                        <th>Cause de congés</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($refusesConges as $conge) : ?>
                                        <tr>
                                            <td><?php echo $conge['id']; ?></td>
                                            <td><?php echo $conge['user_nom']; ?></td>
                                            <td><?php echo $conge['user_prenom']; ?></td>
                                            <td><?php echo $conge['type']; ?></td>
                                            <td><?php echo $conge['DateDebut']; ?></td>
                                            <td><?php echo $conge['DateFin']; ?></td>
                                            <td><?php echo $conge['NbrJour']; ?></td>
                                            <?php if ($conge['type'] === 'simple') : ?>
                                                <td><?php echo $conge['CauseConge']; ?></td>
                                            <?php elseif ($conge['type'] === 'maladie') : ?>
                                                <td> <a href="../photo/<?php echo $conge['photo'] ?>" name="buttonx">Voir photo</a></td>
                                            <?php endif; ?>
                                            <td>
                                            <td>
                                                <i class="fas fa-times text-danger"style="margin-left :20px;"></i>
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
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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