<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$inicial = 'P';
?>
<style>
  .user-icon {
    background-color:rgb(204, 28, 51);
    color: white;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: bold;
  }
</style>
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <div class="d-flex w-100 align-items-center justify-content-between">
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar">
        <span class="navbar-toggler-icon"></span> Menu
      </button>
      <h5 class="text-light mb-0"> </h5>
      <div class="dropdown">
        <div class="user-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><?= $inicial ?></div>
        <ul class="dropdown-menu dropdown-menu-end">
          <li><a class="dropdown-item" href="https://black.xhn.cl:2096/" target="_blank"><i class="bi bi-envelope-fill"></i> Webmail</a></li>
          <li><a class="dropdown-item" href="php/logout.php"><i class="bi bi-box-arrow-left"></i> Cerrar sesión</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
