<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title></title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
  </head>
  <body>

    <form action="upload.php" method="post" enctype="multipart/form-data">
      Image: <input type="file" name="photo" id="photo"  size="40" />
      <input type="submit" />
    </form>

    <pre>
    <?php print_r($_FILES); 
    
    if($f=$_FILES['photo']['tmp_name']) {
        move_uploaded_file($f,$f.'yes');
      }
    ?>
    <pre>

  </body>
</html>