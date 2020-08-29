<?php 

session_start();
require 'config/config.php';
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
	header('location:login.php');
}

 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Site</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="">
  <!-- Content Wrapper. Contains page content -->
  <div class="">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
           <h1 align="center">Blog Site</h1>
      </div><!-- /.container-fluid -->
    </section>

    <?php 
    $stmt=$pdo->prepare("SELECT * FROM posts ORDER BY id");
    $stmt->execute();
    $result = $stmt->fetchAll();

	  if(!empty($_GET['pageno'])){
	    $pageno = $_GET['pageno'];
	  }else{
	    $pageno =1;
	  }
	    
	  $numofrecs = 5;
	  $offset = ($pageno - 1) * $numofrecs;

	 if(empty($_POST['search'])){
	   $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
	  $stmt->execute();
	  $Rawresult = $stmt->fetchAll();
	  $totalpage = ceil(count($Rawresult)/$numofrecs);

	  $stmt = $pdo->prepare("SELECT * FROM posts LIMIT $offset , $numofrecs");
	  $stmt->execute();
	  $result = $stmt->fetchAll();
	}else{
	  $searchkey = $_POST['search'];
	   $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchkey%' OR content LIKE '%$searchkey%' ORDER BY id DESC");
	  $stmt->execute();

	  $Rawresult = $stmt->fetchAll();
	  $totalpage = ceil(count($Rawresult)/$numofrecs);

	  $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchkey%' OR content LIKE '%$searchkey%' LIMIT $offset , $numofrecs");
	  $stmt->execute();

	  $result = $stmt->fetchAll();
	}


                   ?>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
        <?php 
        if ($result) {
        	foreach ($result as $value) {
        ?>
        	<div class="col-md-4">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="float: none!important;text-align: center">
                  <h4 ><?php echo $value['title']?></h4>
                </div>
                <!-- /.user-block -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <a href="blogdetail.php?id=<?php echo $value['id'] ?>"><img class="img-fluid pad" src="admin/images/<?php echo $value['image'] ?>" style="width: 200px;height: 150px;" alt="Photo"></a>
              </div>
              
            
            </div>
            <!-- /.card -->
          </div>
        <?php

        	}
        }
        ?>
          
          <!-- /.col -->

        </div>
        <!-- /.row -->
        <!-- row 2 -->

        
      </div><!-- /.container-fluid -->
      <div class="row" style="float: right;margin-right: 0">
      	 <nav >
      		<ul class="pagination" style="float: right;">
		        <li class="page-item"><a href="?pageno=1" class="page-link">First</a></li>
		        <li class="page-item <?php if($pageno<= 1){echo 'disabled';} ?>" >
		          <a href="<?php if($pageno <= 1){echo '#';}else {echo '?pageno='.($pageno-1);} ?>" class="page-link">Prev</a></li>
		        <li class="page-item"><a href="#" class="page-link"><?php echo $pageno; ?></a></li>
		        <li class="page-item <?php if($pageno >= $totalpage){echo 'disabled';} ?>" >
		          <a href="<?php if($pageno>=$totalpage){echo '#';}else { echo '?pageno='.($pageno+1);} ?>" class="page-link">Next</a></li>
        		<li class="page-item"><a href="?pageno=<?php echo $totalpage;  ?>" class="page-link">Last</a></li>
      		</ul>
    	</nav>
      </div><br><br>
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->



  <!-- Main Footer -->
  <footer class="main-footer" style="margin-left: 0!important;">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
      <a href="logout.php" type="button" class="btn btn-default">Logout</a> 
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2020 <a href="">A Programmer</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>

