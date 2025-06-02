<?php require_once "includes/header.php"; ?>
<style>
body {
    min-height: 100vh;
    display: flex;
    margin: 0;
    font-family: Arial, sans-serif;
}

.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #000;
    color: white;
    padding: 15px;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    transition: transform 0.3s ease-in-out;
    z-index: 1000;
}

.sidebar img {
    max-width: 100%;
    display: block;
    margin: 0 auto 20px;
}

.sidebar a {
    color: white;
    text-decoration: none;
    padding: 10px;
    display: flex;
    align-items: center;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.sidebar a:hover, .sidebar .active {
    background-color: #0d6efd;
}

.sidebar i {
    margin-right: 10px;
}

.user-section {
    position: absolute;
    bottom: 15px;
    left: 15px;
    display: flex;
    align-items: center;
}

.user-section img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.main-content {
    margin-left: 250px;
    flex-grow: 1;
    padding: 20px;
    transition: margin-left 0.3s ease-in-out;
}

.hamburger {
    display: none;
    width: 40px;
    height: 40px;
    background-color:  rgba(217, 33, 33, 0.74);
    border: none;
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(217, 33, 33, 0.74);
    cursor: pointer;
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 1100;
    transition: background-color 0.3s;
}

.hamburger i {
    font-size: 24px;
    color: #ff4500; /* Grizzly Gym orange accent */
    transition: transform 0.3s;
}

.hamburger:hover {
    background-color:  rgba(217, 33, 33, 0.74);
}

.hamburger.active i.bi-list {
    transform: rotate(90deg); /* Rotate when active */
}

.hamburger.active i.bi-x {
    transform: rotate(0deg);
}

@media (max-width: 1000px) {
    .sidebar {
        transform: translateX(-250px);
    }
    .sidebar.active {
        transform: translateX(0);
    }
    .main-content {
        margin-left: 0;
        width: 100%;
    }
    .hamburger {
        display: flex;
        align-items: center;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .hamburger {
        width: 36px;
        height: 36px;
        top: 10px;
        left: 10px;
    }
    .hamburger i {
        font-size: 20px;
    }
}
</style>
</head>
<body>
<button class="hamburger" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>
<div class="sidebar d-flex flex-column">
    <img src="public/images/logo.jpeg" alt="Grizzly Gym Logo">
    <a href="index.php" class="active"><i class="bi bi-house-door"></i> Home</a>
    <a href="plans.php"><i class="bi bi-speedometer2"></i> Planovi</a>
    <a href="users.php"><i class="bi bi-table"></i> Članovi</a>
    <a href="payments.php"><i class="bi bi-grid"></i> Transakcije</a>
    <a href="checklist.php"><i class="bi bi-people"></i> Check Lista</a>
    <a href="statistics.php"><i class="bi bi-charts"></i> Statistika</a>
    <div class="user-section">
        <img src="https://via.placeholder.com/30" alt="User">
        <span>mdo ▼</span>
    </div>
</div>
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.querySelector('.hamburger');
    const icon = hamburger.querySelector('i');
    sidebar.classList.toggle('active');
    hamburger.classList.toggle('active');
    if (sidebar.classList.contains('active')) {
        icon.classList.remove('bi-list');
        icon.classList.add('bi-x');
    } else {
        icon.classList.remove('bi-x');
        icon.classList.add('bi-list');
    }
}
</script>
</body>
</html>