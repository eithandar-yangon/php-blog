<?php
require '../config/config.php';
session_start();
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
  header('location:login.php');
}

if($_POST){
	$file = 'images/'.($_FILES['image']['name']);
	$imageType = pathinfo($file,PATHINFO_EXTENSION);

	if($imageType!='jpg' && $imageType!='png' && $imageType!='jpeg'){
		echo "<script>alert('Image must be png,jpg or jpeg')</script>";
	}else{

		$title = $_POST['title'];
		$content = $_POST['content'];
		$image = $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], $file);
		$stmt=$pdo->prepare('INSERT INTO posts(title,content,image,author_id) VALUES (:title,:content,:image,:author_id)');
		$result= $stmt->execute(
			array(':title'=>$title,':content'=>$content,':image'=>$image,':author_id'=>$_SESSION['user_id'])
		);

		if($result){
			echo "<script>alert('Successfully added');window.location.href='index.php'</script>";
		}
	}
}
?>

<?php include('header.html'); ?>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
            <div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
              	<form action="add.php" enctype="multipart/form-data" method="post">
              		<div class="form-group">
              			<label>Title</label>
              			<input type="text" name="title" class="form-control" required="">
              		</div>
              		<div class="form-group">
              			<label>Content</label>
              			<textarea class="form-control" name="content" required="" rows="8" cols="90"></textarea>
              		</div>
              		<div class="form-group">
              			<label>Image</label><br>
              			<input type="file" name="image" required="">
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

