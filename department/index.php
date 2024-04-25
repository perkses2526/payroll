<?php
require_once '../header.php';

if (!($_SESSION['usertype'] === "superadmin" || $_SESSION['usertype'] === "admin")) {
  echo '<script>window.location="../404.php"</script>';
}
?>


<main id="main" class="main">

  <div class="pagetitle">
    <h1>department</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../department/">Home</a></li>
        <li class="breadcrumb-item active">Department</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


  <section class="section">
    <div class="row">
    </div>
  </section>

</main>
<script src="func.js"></script>

<?php require_once '../footer.php'; ?>