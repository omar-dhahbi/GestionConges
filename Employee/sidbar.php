<?php
$current_page = basename($_SERVER['PHP_SELF']);

?>

<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-light navbar-light">

        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="../photo/<?php echo $_SESSION['photo']; ?>" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>

            <div class="ms-3">
                <h6 class="mb-0"><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></h6>
                <span><?php echo $_SESSION['role'] ?></span>

            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="employee.php" class="nav-item nav-link <?php echo ($current_page == 'employee.php') ? 'active' : ''; ?>"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>

            <a href="demandeCongé.php" class="nav-item nav-link <?php echo ($current_page == 'demandeCongé.php') ? 'active' : ''; ?>"><i class="fa fa-calendar-alt me-2"></i>traitement congés</a>
        </div>
</div>
</nav>
</div>