<?php
require '../config/config.php';
session_start();
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
  header('location:login.php');
}

if($_POST){
	if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password'])< 6){
    if(empty($_POST['name'])){
      $nameError = "Name can not be blank";
    }
    if(empty($_POST['email'])){
      $emailError = "Email can not be blank";
    }
    
    if(strlen($_POST['password'])<6) {
      $passwordError = "Password must be 6 characters";
    }
    if(empty($_POST['password'])){
      $passwordError = "Password can not be blank";
    }
  }else{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    if(empty($_POST['role'])){
      $role = 0;
    }else{
      $role = 1;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindValue(':email',$email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user){
      echo "<script>alert('Email Duplicated')</script>";
    }else{
      $stmt=$pdo->prepare("INSERT INTO users(name,email,password,role) VALUES (:name,:email,:password,:role)");
      $result= $stmt->execute(
      array(':name'=>$name,':email'=>$email,':password'=>$password,':role'=>$role)
    );
      if($result){
      echo "<script>alert('Successfully added');window.location.href='user_list.php'</script>";
    }

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
              	<form action="user_add.php" enctype="multipart/form-data" method="post">
              		<div class="form-group">
              			<label>Name</label>
              			<input type="text" name="name" class="form-control" placeholder="Name">
                    <p style="color: red"><?php echo empty($nameError) ? '' : '*'.$nameError; ?></p>

              		</div>
              		<div class="form-group">
              			<label>Email</label>
              			<input class="form-control" name="email" rows="8" cols="90"  placeholder="Email">
                    <p style="color: red"><?php echo empty($emailError) ? '' : '*'.$emailError; ?></p>

              		</div>
                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password">
                    <p style="color: red"><?php echo empty($passwordError) ? '' : '*'.$passwordError; ?></p>
                  </div>
                  <div class="form-group">
                    <label>Admin</label>
                    <input type="checkbox" name="role" value="1">
                  </div>
              		<div class="form-group">
              			<input type="submit" value="SUBMIT" class="btn btn-success">
              			<a href="index.php" type="button" class="btn btn-warning">BACK</a>
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

