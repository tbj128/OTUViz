<?php

  function startsWith($haystack, $needle) {
      // search backwards starting from haystack length characters from the end
      return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
  }
  function endsWith($haystack, $needle) {
      // search forward starting from end minus needle length characters
      return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
  }

  function dequote($s) {
    $retStr = $s;
    if ((substr($s,0,1) == substr($s,-1)) && (startsWith($s, "'") || startsWith($s, '"'))) {
      $retStr = substr($s,1,-1);
    }

    if ((substr($retStr,0,1) == substr($retStr,-1)) && (startsWith($retStr, "'") || startsWith($retStr, '"'))) {
      $retStr = substr($retStr,1,-1);
    }
    return $retStr;
  }

  function debracket($s) {
    $sSplit = explode('(', $s);
    return $sSplit[0];
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



  $rowNum = 0;
  foreach ($csv as $value) {
    if ($rowNum > 0) {
      // Ignore the first row and add the rest of the rows to the database
      $otu = $value[0];
      $size = $value[1];

      $taxonomyArr = explode(';',$value[2]);
      $kingdom = "";
      $phylum = "";
      $class = "";
      $order = "";
      $family = "";
      $genus = "";
      $species = "";

      $taxID = 0;
      foreach ($taxonomyArr as $rawTaxonomy) {
        $taxonomy = dequote(debracket($rawTaxonomy));
        switch ($taxID) {
            case 0:
                $kingdom = $taxonomy;
                break;
            case 1:
                $phylum = $taxonomy;
                break;
            case 2:
                $class = $taxonomy;
                break;
            case 3:
                $order = $taxonomy;
                break;
            case 4:
                $family = $taxonomy;
                break;
            case 5:
                $genus = $taxonomy;
                break;
            case 6:
                $species = $taxonomy;
                break;
        }
        $taxID++;
      }

      $otu = $conn->real_escape_string($otu);
      $size = $conn->real_escape_string($size);
      $kingdom = $conn->real_escape_string($kingdom);
      $phylum = $conn->real_escape_string($phylum);
      $class = $conn->real_escape_string($class);
      $order = $conn->real_escape_string($order);
      $family = $conn->real_escape_string($family);
      $genus = $conn->real_escape_string($genus);
      $species = $conn->real_escape_string($species);

      $sql = "INSERT INTO `otus`(`OTU`, `Size`, `Kingdom`, `Phylum`, `Class`, `Order`, `Family`, `Genus`, `Species`) VALUES ('$otu', $size, '$kingdom', '$phylum', '$class', '$order', '$family', '$genus', '$species')";

      if (mysqli_query($conn, $sql)) {
        // TODO: Success
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
    }
    $rowNum++;
  }


  mysqli_close($conn);
?>