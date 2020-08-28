<?php
require '../config/config.php';
session_start();
if(empty($_SESSION['user_id'])&& empty($_SESSION['logged_in'])){
  header('location:login.php');
}


if($_POST){
	$id=$_POST['id'];
	$title = $_POST['title'];
	$content = $_POST['content'];
	if($_FILES['image']['name']!=null){
		$file = 'images/'.($_FILES['image']['name']);
	$imageType = pathinfo($file,PATHINFO_EXTENSION);

	if($imageType!='jpg' && $imageType!='png' && $imageType!='jpeg'){
		echo "<script>alert('Image must be png,jpg or jpeg')</script>";
	}else{

		$image = $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], $file);
		$stmt=$pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id= '$id'");
		$result= $stmt->execute();
			
		if($result){
			echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
		}
	}
	}else{
		$stmt=$pdo->prepare("UPDATE posts SET title='$title',content='$content',image='$image' WHERE id= '$id'");
		$result= $stmt->execute();
			
		if($result){
			echo "<script>alert('Successfully added');window.location.href='index.php';</script>";
		}
	}
}

$stmt = $pdo->prepare('SELECT * FROM posts WHERE id='.$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();
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
              	<form action="" enctype="multipart/form-data" method="post">
              		<input type="hidden" name="id" value="<?php echo $result[0]['id'] ?> ">
              		<div class="form-group">
              			<label>Title</label>
              			<input type="text" name="title" class="form-control" required="" value="<?php echo $result[0]['title'] ?> ">
              		</div>
              		<div class="form-group">
              			<label>Content</label>
              			<textarea class="form-control" name="content" required="" rows="8" cols="90"><?php echo $result[0]['content'] ?></textarea>
              		</div>
              		<div class="form-group">
              			<label>Image</label><br>
              			<img src="images/<?php echo $result[0]['image'] ?> "  width="150" height="150">
              			<input type="file" name="image">
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


