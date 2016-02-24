<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "mian";

  $rec_limit = 30;
  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  /* Get total number of records */
  $sql = "SELECT count(OTU) FROM otus";
  $retval = mysqli_query($conn, $sql);

  $row = mysqli_fetch_array($retval, MYSQL_NUM );
  $rec_count = $row[0];

  if(isset($_GET{'page'}))
  {
    $page = $_GET{'page'} + 1;
    $offset = $rec_limit * $page ;
  }
  else
  {
    $page = 0;
    $offset = 0;
  }
  $left_rec = $rec_count - ($page * $rec_limit);
    
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Add OTU - Mian</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="css/mian_custom.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--<script src="js/ie-emulation-modes-warning.js"></script>-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->



    <link rel="stylesheet" href="css/jquery.fileupload-ui.css">
  </head>

  <body>

    <?php
      $page = "add";
      include "navbar.php";
    ?>

    <!-- Content -->
    <div class="container">

      <div class="page-header">
        <h1 id="navbar"><i class="fa fa-database"></i> Add OTU Data</h1>
        <h3>Upload a dataset or add a single OTU below.</h3>
      </div>

      <div class="row">
        <div class="col-md-2">

          <span id="add-bulk" class="btn btn-primary" style="width:100%;margin-bottom:4px;"><i class="fa fa-list"></i>&nbsp;&nbsp;Bulk Add</span>
          <span id="single-bulk" class="btn btn-primary" style="width:100%;"><i class="fa fa-plus"></i>&nbsp;&nbsp;Single Add</span>
          
        </div>
        <div class="col-md-10">
          <table class="table"> 
            <thead> 
              <tr> 
                <th></th> 
                <th>OTU</th> 
                <th>Size</th> 
                <th>Kingdom</th> 
                <th>Phylum</th> 
                <th>Class</th> 
                <th>Order</th> 
                <th>Family</th> 
                <th>Genus</th> 
                <th>Species</th> 
              </tr> 
            </thead> 
            <tbody> 
              <?php
                $sql = "SELECT * FROM otus LIMIT $offset, $rec_limit";
                $retval = mysqli_query($conn, $sql);
                while($row = mysqli_fetch_array($retval, MYSQL_ASSOC))
                {
                  echo "<tr>";
                  echo "<th scope=\"row\"><a href=\"\"><i class=\"fa fa-trash\"></i></a></th>";
                  echo "<td>".$row["OTU"]."</td> ";
                  echo "<td>".$row["Size"]."</td> ";
                  echo "<td>".$row["Kingdom"]."</td> ";
                  echo "<td>".$row["Phylum"]."</td> ";
                  echo "<td>".$row["Class"]."</td> ";
                  echo "<td>".$row["Order"]."</td> ";
                  echo "<td>".$row["Family"]."</td> ";
                  echo "<td>".$row["Genus"]."</td> ";
                  echo "<td>".$row["Species"]."</td>";
                  echo "</tr>";
                }

                if( $page > 0 )
                {
                  $last = $page - 2;
                  echo "<a href=\"?page=$last\">Last 10 Records</a> |";
                  echo "<a href=\"?page=$page\">Next 10 Records</a>";
                }

                else if( $page == 0 )
                {
                  echo "<a href=\"?page=$page\">Next 10 Records</a>";
                }

                else if( $left_rec < $rec_limit )
                {
                  $last = $page - 2;
                  echo "<a href=\"?page=$last\">Last 10 Records</a>";
                }
              ?>

              
            </tbody> 
          </table>
        </div>
      </div>

      <form style="display:none;" enctype="multipart/form-data" action="upload/upload_otus.php" method="POST">
        <input id="add-bulk-upload" name="add-bulk-upload" type="file" onchange="javascript:this.form.submit();" />
      </form>

    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<script src="js/ie10-viewport-bug-workaround.js"></script>-->
    <script src="js/SimpleAjaxUploader.js"></script>

    <script>
      $( document ).ready(function() {
        $("#add-bulk").click(function(){
          $("#add-bulk-upload").click();
        });
      });
    </script>
  </body>
</html>

<?php
  mysqli_close($conn);
?>