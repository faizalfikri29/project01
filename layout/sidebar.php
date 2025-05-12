<?php
include 'config/database.php';
?>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Core</div>
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    
                    <a class="nav-link" href="prodi.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-university"></i></div>
                        Prodi
                    </a>
                    <a class="nav-link" href="dosen.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        Dosen
                    </a>
                    <a class="nav-link" href="kegiatan.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar"></i></div>
                        Kegiatan
                    </a>
                    <a class="nav-link" href="jenis_kegiatan.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        Jenis Kegiatan
                    </a>
                    <a class="nav-link" href="dosen_kegiatan.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-clock"></i></div>
                        Dosen Kegiatan
                    </a>
                    <a class="nav-link" href="penelitian.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                        Penelitian
                    </a>
                    <a class="nav-link" href="tim_penelitian.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                        Tim Penelitian
                    </a>
                    <a class="nav-link" href="bidang_ilmu.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-atom"></i></div>
                        Bidang Ilmu
                    </a>

                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                Authentication
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="login.html">Login</a>
                                    <a class="nav-link" href="register.html">Register</a>
                                    <a class="nav-link" href="password.html">Forgot Password</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                Error
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="401.html">401 Page</a>
                                    <a class="nav-link" href="404.html">404 Page</a>
                                    <a class="nav-link" href="500.html">500 Page</a>
                                </nav>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </nav>
    </div>