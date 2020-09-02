<?php
session_start();
require '../config/config.php';
require '../config/common.php';

if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
  header('location:login.php');
}
?>

<?php include('header.php'); ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blog Listing</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              	<div><a href="add.php" type="button" class="btn btn-success">Create New Blog</a></div><br>
                <table class="table table-bordered">
                  <thead>                  
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <?php 
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

                  if(!$result){
                    echo "<script>alert('No Result');window.location.href='index.php'</script>";
                  }
                }


                   ?>
                  <tbody>
                    <?php 
                    if($result){
                      $id =1;
                      foreach ($result as $value) { 
                      
                    
                    ?>
                      <tr>
                      <td><?php echo $id ?></td>
                      <td><?php echo escape($value['title']); ?></td>
                      <td>
                        <?php echo escape(substr($value['content'],0,400));?> 
                      </td>
                      <td class="btn-group">
                        <div class="container"><a href="edit.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-warning">Edit</a></div>
                        <div class="container"><a href="delete.php?id=<?php echo $value['id'] ?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure to delete')">Delete</a></div>
                      </td>
                    </tr>

                    <?php
                    $id++;
                      }
                    }
                     ?>
                    
                  </tbody>
                </table><br>
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
              </div>
              <!-- /.card-body -->
              
            </div>
            <!-- /.card -->

            
            <!-- /.card -->
          </div>  
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php include('footer.html') ?>

