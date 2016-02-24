<?php
  
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "mian";

  // Create connection
  $conn = mysqli_connect($servername, $username, $password, $dbname);
  // Check connection
  if (!$conn) {
      die("Connection failed: " . mysqli_connect_error());
  }

  if ($_FILES['add-bulk-upload']) {
    // TODO: Need file validation and cleaning!

    foreach ($_FILES['add-bulk-upload']['tmp_name'] as $file) {

      $csv = array_map('str_getcsv', file($file));

      $colToType = array();
      $colToMetadata = array();
      $colToOTU = array();
      $rowNum = 0;
      $experimentName = "";
      $experimentID = 0;
      $colNumToHeader = array();
      foreach ($csv as $value) {
        if ($rowNum == 0) {
          // Experiment Name
          $experimentName = $value[0];
        } else if ($rowNum == 1) {
          // Experiment Description
          $experimentDescription = $value[0];

          // Create Experiment
          $sql = "INSERT INTO `experiments` (`Experiment`, `Description`) VALUES ('$experimentName', '$experimentDescription');";
          if (mysqli_query($conn, $sql)) {
            // TODO: Success
          } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
          }
          $experimentID = mysqli_insert_id($conn);
        } else if ($rowNum == 2) {
          // Metric Headers for each OTU
          $colNum = 0;
          foreach ($value as $col) {
            $colNumToHeader[$colNum] = $col;
            $colNum++;
          }
        } else {
          // Metric Values for each OTU
          $taxonomicLevel = $colNumToHeader[0];
          $taxonomy = $value[0];
          $colNum = 0;
          foreach ($value as $col) {
            if ($colNum > 0) {
              $metricType = $colNumToHeader[$colNum];
              $sql = "INSERT INTO `results` (`TaxonomicLevel`, `Taxonomy`, `ExpID`, `MetricType`, `Metric`) VALUES ('$taxonomicLevel', '$taxonomy' ,'$experimentID', '$metricType', $col);";
              if (mysqli_query($conn, $sql)) {
                // TODO: Success
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
            }
            $colNum++;
          }
        }
        $rowNum++;
      }

    }
  }

  mysqli_close($conn);
?>