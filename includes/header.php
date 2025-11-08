<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="../dashboard/index.php">
            📊 Mahasiswa Manager
        </a>
        <div class="navbar-nav ms-auto">
            <span class="navbar-text me-3">
            👋 Hello, <?php echo $_SESSION['admin_username']; ?>
            </span>
            <a class="nav-link" href="../auth/logout.php">Logout</a>
        </div>
    </div>
</nav>