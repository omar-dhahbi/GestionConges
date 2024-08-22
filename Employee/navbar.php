<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>

    <div class="navbar-nav align-items-center ms-auto">


        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="../photo/<?php echo $_SESSION['photo']; ?>" alt="" style="width: 40px; height: 40px;">
                <span class="d-none d-lg-inline-flex"><?php echo $_SESSION['nom'] . ' ' . $_SESSION['prenom']; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="profil.php?id=<?php echo $_SESSION['id'] ?>" class="dropdown-item"><i class="fa fa-user"></i> My Profile</a>
                <a href="updatePassword.php?id=<?php echo $_SESSION['id'] ?>" class="dropdown-item"><i class="fa fa-lock"></i> Password</a>
                <a href="../logout.php" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Sign Out</a>

            </div>
        </div>
    </div>
</nav>