<!-- page/components/navbar.php -->
<!-- @Copyright by 23552011408_Arika Azhar_TIF 23 CNS B_WEB1 -->

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
    <div class="container-fluid">
        <a class="navbar-brand text-success fw-bold" href="dashboard.php">
            <i class="fas fa-recycle"></i> CleanTrans
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Notifikasi (opsional) -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header">Notifikasi</h6></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-user-plus text-primary"></i> Nasabah baru terdaftar
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-exchange-alt text-success"></i> Transaksi baru
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="#">Lihat Semua</a></li>
                    </ul>
                </li>
                
                <!-- User Profile -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="d-none d-md-inline">
                            <?php echo isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User'; ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item disabled" href="#">
                                <small class="text-muted">
                                    Logged in as: <strong><?php echo isset($_SESSION['level']) ? ucfirst($_SESSION['level']) : 'User'; ?></strong>
                                </small>
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="profil.php">
                                <i class="fas fa-user-cog"></i> Profil Saya
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="logout.php" onclick="return confirm('Yakin ingin logout?')">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>