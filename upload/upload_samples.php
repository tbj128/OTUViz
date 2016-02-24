<?php

  function startsWith($haystack, $needle) {
      // search backwards starting from haystack length characters from the end
      return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
  }
  function endsWith($haystack, $needle) {
      // search forward starting from end minus needle length characters
      return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
  }
  
  function get_otu_id($conn, $otu) {
    $stmt = $conn->prepare('SELECT `OTUID` FROM otus WHERE OTU = ? LIMIT 1');
    $stmt->bind_param('s', $otu);
    $stmt->execute();
    $result = $stmt->get_result();
    $otuID = -1;
    while ($row = $result->fetch_assoc()) {
      $otuID = $row["OTUID"];
      break;
    }
    return $otuID;
  }

  // TODO: Need file validation and cleaning!
  $csv = array_map('str_getcsv', file($_FILES['add-bulk-upload']['tmp_name']));

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

  $colToType = array();
  $colToMetadata = array();
  $colToOTU = array();
  $rowNum = 0;
  foreach ($csv as $value) {
    if ($rowNum == 0) {
      // Create the Header to Column mapping
      $colNum = 0;
      foreach ($value as $col) {
        if (startsWith($col, "Otu")) {
          // OTU Column
          $colToType[$colNum] = 'Reads';
          $colToOTU[$colNum] = $col;
        } else {
          $colToType[$colNum] = 'Metadata';
          $colToMetadata[$colNum] = $col;
        }
        $colNum++;
      }
    } else {
      $colNum = 0;
      $metadata_keys = "";
      $metadata_vals = "";
      foreach ($value as $col) {
        if ($colToType[$colNum] == 'Metadata') {
          $colname = $colToMetadata[$colNum];
          $colval = $conn->real_escape_string($col);
          $metadata_keys = $metadata_keys . "`" . $colname . "`,";
          $metadata_vals = $metadata_vals . "'" . $colval . "',";
        }
        $colNum++;
      }

      $metadata_keys = rtrim(trim($metadata_keys, " "), ",");
      $metadata_vals = rtrim(trim($metadata_vals, " "), ",");
      $sql = "INSERT INTO `samples` ($metadata_keys) VALUES ($metadata_vals);";
      if (mysqli_query($conn, $sql)) {
        // TODO: Success
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $sampleID = mysqli_insert_id($conn);

      $colNum = 0;
      foreach ($value as $col) {
        if ($colToType[$colNum] == 'Reads') {
          $colname = $colToOTU[$colNum];
          $stmt = $conn->prepare('SELECT `OTUID` FROM otus WHERE OTU = ? LIMIT 1');
          $stmt->bind_param('s', $colname);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
            $otuID = $row["OTUID"];
            $sql = "INSERT INTO `otu_reads` (`OTUID`, `OTU`, `SampleID`, `Reads`) VALUES ('$otuID', '$colname', '$sampleID', '$col');";
            if (mysqli_query($conn, $sql)) {
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
            break;
          }
        }
        $colNum++;
      }
    }
    $rowNum++;
  }

  mysqli_close($conn);
?>