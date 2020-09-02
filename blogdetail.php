<?php 

session_start();
require 'config/config.php';
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
	header('location:login.php');
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

$blogId= $_GET['id'];
$stmtcmt = $pdo->prepare("SELECT * FROM comments WHERE post_id = $blogId");
$stmtcmt->execute();
$cmresult = $stmtcmt->fetchAll();

$auresult=[];

if($cmresult){
  foreach ($cmresult as $key => $value) {
    $authorId= $cmresult[$key]['author_id'];
  $stmtau = $pdo->prepare("SELECT * FROM users WHERE id = $authorId");
  $stmtau->execute();
  $auresult[] = $stmtau->fetchAll();
  }
}



if($_POST){
  if(empty($_POST['comment'])){
    if(empty($_POST['comment'])){
      $cmtError = "Comment can not be blank";
    }
  }else{
    $comment = $_POST['comment'];
  $blogId= $_GET['id'];
  $author_id = $_SESSION['user_id'];

    $stmt=$pdo->prepare("INSERT INTO comments(content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
    $result= $stmt->execute(
      array(':content'=>$comment,':author_id'=>$author_id,':post_id'=>$blogId)
    );

    if($result){
      header('location:blogdetail.php?id='.$blogId);
    }
  }	
}


 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blog Detail</title>
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
    

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <!-- Box Comment -->
            <div class="card card-widget">
              <div class="card-header">
                <div class="card-title" style="float: none!important;text-align: center">
                  <h4 ><?php echo escape($result[0]['title']); ?></h4>
                </div>
                <!-- /.user-block -->
                
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <img class="img-fluid pad" src="admin/images/<?php echo $result[0]['image'] ?>" alt="Photo" style="width: 100%">
                <br><br>
                <p><?php echo escape($result[0]['content']); ?></p>
                <a href="index.php" type="button" class="btn btn-primary float-right">Go Back</a>
              </div>
              <!-- /.card-body -->
              <div class="card-footer card-comments">
              	<h3>Comments</h3>
              	<hr>
                <div class="card-comment">
                  <!-- User image -->
                  <?php 
                  if($cmresult){?>
                    <div class="comment-text" style="margin-left: 0">
                      <?php foreach ($cmresult as $key => $value) { ?>
                    <span class="username">
                      <?php echo escape($auresult[$key][0]['name']);?>

                      <span class="text-muted float-right"><?php echo escape($value['created_at']); ?></span>
                    </span><!-- /.username -->
                    <?php echo escape($value['content']); ?>
                    </div>
                  <?php } ?>
                  <?php
                   
                  }?>

                  <div class="comment-text">
                    
                  </div>
                  <!-- /.comment-text -->
                </div>
                <!-- /.card-comment -->
              </div>
              <!-- /.card-footer -->
              <div class="card-footer">
                <form action="" method="post">
                  <img class="img-fluid img-circle img-sm" src="dist/img/user4-128x128.jpg" alt="Alt Text">
                  <!-- .img-push is used to add margin to elements next to floating images -->
                  <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="img-push">
                    <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                    <p style="color: red"><?php echo empty($cmtError) ? '' : '*'.$cmtError; ?></p>

                  </div>
                </form>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
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

