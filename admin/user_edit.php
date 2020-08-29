<?php
require '../config/config.php';
session_start();
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
  header('location:login.php');
}

$stmt = $pdo->prepare('SELECT * FROM users WHERE id='.$_GET['id']);
$stmt->execute();
$Rawresult = $stmt->fetchAll();

if($_POST){
		$id = $_GET['id'];
		$name = $_POST['name'];
		$email = $_POST['email'];
    if(empty($_POST['role'])){
      $role = 0	;
    }else{
      $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
    $stmt->execute(array(':email'=>$email,':id'=>$id));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo "<script>alert('Email Duplicated')</script>";
    }else{
      $stmt=$pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
      $result= $stmt->execute();
      if($result){
      echo "<script>alert('Successfully updated');window.location.href='user_list.php'</script>";
    }

    }
}
?>

<?php include('header.php'); ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
            <div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
              	<form action="" enctype="multipart/form-data" method="post">
              		<div class="form-group">
              			<label>Name</label>
              			<input type="text" name="name" value="<?php echo $Rawresult[0]['name'] ?>" class="form-control" required="">
              		</div>
              		<div class="form-group">
              			<label>Email</label>
              			<input class="form-control" value="<?php echo $Rawresult[0]['email'] ?>" name="email" required="">
              		</div>
                  <div class="form-group">
                    <label>Admin</label>
                    <input type="checkbox" name="role" value="1" <?php if($Rawresult[0]['role']==1){echo 'checked';} ?>>
                  </div>
              		<div class="form-group">
              			<input type="submit" value="UPDATE" class="btn btn-success">
              			<a href="user_list.php" type="button" class="btn btn-warning">BACK</a>
              		</div>
              	</form>
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
