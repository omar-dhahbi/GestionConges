<?php
include '../security.php';

if ($_SESSION['role'] !== 'admin') {
  header("location: ../index.php");
  exit();
}
include "../connexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["activer"])) {
    $userId = $_POST["id"];
    activeCompte($userId);
  } elseif (isset($_POST["desactiver"])) {
    $userId = $_POST["id"];
    desactiverCompte($userId);
  }
}

$query = "SELECT * FROM users WHERE role = 'employee'";
$stmt = $conn->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

function activeCompte($userId)
{
  global $conn;
  $query = "UPDATE users SET status = 1 WHERE id = :id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $userId);
  $stmt->execute();
}

function desactiverCompte($userId)
{
  global $conn;
  $query = "UPDATE users SET status = 0 WHERE id = :id";
  $stmt = $conn->prepare($query);
  $stmt->bindParam(':id', $userId);
  $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Admin -- interface</title>
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
    .circular-image {
      max-width: 50px;
      max-height: 100px;
      border-radius: 50%;
    }
  </style>

</head>

<body>
  <div class="container-xxl position-relative bg-white d-flex p-0">
    <?php include "sidbar.php" ?>
    <div class="content">
      <?php include "navbar.php" ?>
      <div class="container-fluid pt-4 px-4">
        <div class="bg-light text-center rounded p-4">

          <div class="table-responsive">
            <table id="tableResultats" class="display" style="width:100%;">
              <thead>
                <tr style="text-align: center;">
                  <td>Nom</td>
                  <td>Prenom</td>
                  <td>telephone</td>
                  <td>photo</td>
                  <td>role</td>
                  <td>status</td>
                  <td>action</td>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user) : ?>

                  <tr style="color:black;text-align:center">
                    <td><?php echo $user['nom']; ?></td>
                    <td><?php echo $user['prenom']; ?></td>
                    <td><?php echo $user['tel']; ?></td>
                    <td>
                      <img src="../photo/<?php echo $user['photo']; ?>" alt="User Photo" class="circular-image">
                    </td>
                    <td><?php echo $user['role']; ?></td>
                    <td>
                      <form method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <?php if ($user['status']) : ?>
                          <button type="submit" name="desactiver" class="btn btn-danger">Désactiver</button>
                        <?php else : ?>
                          <button type="submit" name="activer" class="btn btn-success">Activer</button>
                        <?php endif; ?>
                      </form>
                    </td>

                    <td>
                      <a href="details.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary me-1">
                        <i class="fas fa-info-circle"></i>
                      </a>
                    </td>

                  </tr>
                <?php endforeach; ?>
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