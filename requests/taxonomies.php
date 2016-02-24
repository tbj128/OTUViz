<?php

  if (!ISSET($_GET["level"])) {
    echo json_encode(array());
    return;
  }

  $taxonomyLevel = $_GET["level"];

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

  $json_response = array();
  $col = $conn->real_escape_string($taxonomyLevel);

  if ($col == "OTU") {
    // Deal with the huge amount of data in OTU
    $stmt = $conn->prepare("SELECT DISTINCT `$col` FROM otu_reads");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $taxonomySpecifics = $row[$taxonomyLevel];
      array_push($json_response, $taxonomySpecifics);
    }
  } else {
    $stmt = $conn->prepare("SELECT DISTINCT `$col` FROM otus");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $taxonomySpecifics = $row[$taxonomyLevel];
      array_push($json_response, $taxonomySpecifics);
    }
  }

  mysqli_close($conn);

  echo json_encode($json_response);
?>