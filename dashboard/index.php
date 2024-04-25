<?php
require_once '../header.php';

if (!($_SESSION['usertype'] === "superadmin" || $_SESSION['usertype'] === "admin")) {
  echo '<script>window.location="../404.php"</script>';
}
?>


<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../dashboard/">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


  <section class="section dashboard">
    <div class="row">

      <div class="col-lg-12">
        <div class="row">

          <div class="col-xxl-4 col-md-6">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title"></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center pointing">
                    <i class="bi bi-building"></i>
                  </div>
                  <div class="ps-3">
                    <h6 id="fact"><span class=" fs-6">Loading...</span>
                      <div class="spinner-border spinner-border-sm text-success" id="l" role="status"></div>
                    </h6>
                    <i>Click icon to display data</i>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>
  </section>

</main>
<script src="dashboard.js"></script>

<?php require_once '../footer.php'; ?>