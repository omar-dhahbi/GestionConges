<?php
include '../security.php';
include "../connexion.php";
$message = "";
if (isset($_POST['submit'])) {
    $type = $_POST['type'];
    $DateDebut = isset($_POST['datedebut']) ? $_POST['datedebut'] : null;
    $DateFin = isset($_POST['datefin']) ? $_POST['datefin'] : null;
    $CauseConge = isset($_POST['cause']) ? $_POST['cause'] : null;
    $photo =  isset($_FILES['photo']['name']) ? $_FILES['photo']['name'] : null;
    move_uploaded_file($_FILES["photo"]["tmp_name"], "../photo/" . $_FILES["photo"]["name"]);
    $NbrJour = isset($_POST['nombrejour']) ? $_POST['nombrejour'] : 0;


    if (empty($DateDebut) || empty($DateFin)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif ($type == 'choix') {
        $message = "Veuillez remplir tous les champs.";
    } elseif (($type === 'maladie' && empty($photo)) || ($type === 'simple' && empty($CauseConge))) {
        $message = "Veuillez remplir tous les champs.";
    } else {
        $user_id = $_SESSION["id"];
        $query = "SELECT * FROM congés WHERE user_id = :user_id AND created_at >= NOW() - INTERVAL 3 DAY";
        $stmt = $conn->prepare($query);
        $stmt->execute(['user_id' => $user_id]);
        $lastRequest = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($lastRequest)) {
            $message = "Vous avez déjà soumis une demande de congé au cours des dernières 72 heures.";
        } else {
            $insertQuery = "INSERT INTO congés (user_id, type, DateDebut, DateFin, NbrJour, CauseConge, photo) 
            VALUES (:user_id, :type, :DateDebut, :DateFin, :NbrJour, :CauseConge, :photo)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->execute([
                'user_id' => $user_id,
                'type' => $type,
                'DateDebut' => $DateDebut,
                'DateFin' => $DateFin,
                'NbrJour' => $NbrJour,
                'CauseConge' => $CauseConge,
                'photo' => $photo
            ]);
            if ($type === 'simple') {
                $nombreJour = "SELECT NbJourConge FROM users WHERE id = :user_id";
                $stmt = $conn->prepare($nombreJour);
                $stmt->execute(['user_id' => $user_id]);
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($userData) {
                    if ($NbrJour > $userData['NbJourConge']) {
                        $message = "Demande non effectuée, nombre de jours demandés > 45";
                    }
                }
            }
            header("Location: employee.php");
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Demande congés</title>
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
                            <?php if (!empty($message)) : ?>
                                <p class="error-message"> <?php echo $message ?></p>
                            <?php endif; ?>
                            <h3 class="text">Demander congés</h3>
                            <br>

                            <form method="post" action="" enctype="multipart/form-data">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $_SESSION['nom'] ?>" disabled>

                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $_SESSION['prenom'] ?>" disabled>

                                </div>

                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="datedebut" name="datedebut" min="2023-01-01" onchange="updateMinDateFin()" value="<?php echo isset($_POST['datedebut']) ? $_POST['datedebut'] : ''; ?>">
                                    <label for="datedebut">Date de début</label>

                                </div>
                                <div class="form-floating mb-3">
                                    <input type="date" class="form-control" id="datefin" name="datefin" onchange="calculateNbrJour()" value="<?php echo isset($_POST['datefin']) ? $_POST['datefin'] : ''; ?>">
                                    <label for="datefin">Date de fin</label>

                                </div>
                                <div class="form-floating mb-3">
                                    <select class="form-control" id="type" name="type" onchange="toggleField()">
                                        <option value="choix">Sélectionnez le type de congé</option>
                                        <option value="simple" <?php if (isset($_POST['type']) && $_POST['type'] == 'simple') echo 'selected'; ?>>Simple</option>
                                        <option value="maladie" <?php if (isset($_POST['type']) && $_POST['type'] == 'maladie') echo 'selected'; ?>>Maladie</option>
                                    </select>
                                    <label for="type">Type de congé</label>

                                </div>
                                <div class="form-floating mb-3" id="causefield" style="display:none;">
                                    <textarea class="form-control" id="cause" name="cause" placeholder="Cause du congé"><?php echo isset($_POST['cause']) ? $_POST['cause'] : ''; ?></textarea>
                                    <label for="cause">Cause du congé</label>

                                </div>
                                <div class="mb-3" id="photoField" style="display:none;">
                                    <label for="photo" class="form-label">Photo</label>
                                    <input class="form-control" type="file" id="photo" multiple name="photo">
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="number" class="form-control" id="nombrejour" name="nombrejour" placeholder="Nombre de jours" readonly>
                                    <label for="nombrejour">Nombre de jours</label>
                                </div>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit" name="submit">Demander</button>
                                    <button class="btn btn-secondary me-md-2" type="button"><a href="employee.php" style="color:white">Annuler</a></button>
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
        function updateMinDateFin() {
            var startDate = document.getElementById('datedebut').value;
            document.getElementById('datefin').min = startDate;
        }

        function calculateNbrJour() {
            var startDate = new Date(document.getElementById('datedebut').value);
            var endDate = new Date(document.getElementById('datefin').value);
            var difference = endDate - startDate;
            var daysDifference = Math.ceil(difference / (1000 * 60 * 60 * 24));
            document.getElementById('nombrejour').value = daysDifference;
        }


        function toggleField() {
            var type = document.getElementById('type').value;
            var photoField = document.getElementById('photoField');
            var causeField = document.getElementById('causefield');
            if (type == 'choix') {
                photoField.style.display = 'none';
                causeField.style.display = 'none';
            } else if (type === 'maladie') {
                photoField.style.display = 'block';
                causeField.style.display = 'none';


            } else if (type === 'simple') {
                causeField.style.display = 'block';
                photoField.style.display = 'none';

            }
        }
    </script>

</body>

</html>