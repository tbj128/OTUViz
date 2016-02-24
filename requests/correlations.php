<?php

  $taxonomyLevel = $_GET["level"];
  $taxonomy = $_GET["taxonomy"];
  $corrVar = $_GET["corrvar"];
  $colorVar = $_GET["colorvar"];
  $sizeVar = $_GET["sizevar"];

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

  // Get all SampleIDs, Reads for a particular taxonomy
  $whereStatement = "";
  $taxonomies = explode(",", $taxonomy);
  $isFirst = true;
  foreach ($taxonomies as $t) {
    if ($isFirst) {
      $isFirst = false;
      $whereStatement = "otus." . $taxonomyLevel . " = '" . $t .  "' ";
    } else {
      $whereStatement = $whereStatement . " OR otus." . $taxonomyLevel . " = '" . $t .  "' ";
    }
  }

  $sampleIDToValue = array();
  $stmt = $conn->prepare("SELECT otu_reads.SampleID, otu_reads.Reads FROM otu_reads INNER JOIN otus ON otus.OTUID=otu_reads.OTUID WHERE $whereStatement");
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $sampleID = $row["SampleID"];
    $reads = $row["Reads"];
    if (array_key_exists($sampleID, $sampleIDToValue)) {
      $sampleIDToValue[$sampleID] = $sampleIDToValue[$sampleID] + $reads;
    } else {
      $sampleIDToValue[$sampleID] = $reads;
    }
  }

  $json_response = array();
  $colsToFetch = "";
  if ($corrVar != "" && $corrVar != "None") {
    $colsToFetch .= "`" . $corrVar . "`,";
  }
  if ($colorVar != "" && $colorVar != "None") {
    $colsToFetch .= "`" . $colorVar . "`,";
  }
  if ($sizeVar != "" && $sizeVar != "None") {
    $colsToFetch .= "`" . $sizeVar . "`,";
  }
  $stmt = $conn->prepare("SELECT $colsToFetch `SampleID` FROM samples");
  //$stmt->bind_param('s', $otu);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $sampleID = $row["SampleID"];

    if (array_key_exists($sampleID, $sampleIDToValue)) {
      $response_row = array();
      if ($corrVar != "" && $corrVar != "None") {
        $corrVarVal = $row[$corrVar];
        $response_row["corrvar"] = $corrVarVal;
      }
      if ($sizeVar != "" && $sizeVar != "None") {
        $sizeVarVal = $row[$sizeVar];
        $response_row["size"] = $sizeVarVal;
      }
      if ($colorVar != "" && $colorVar != "None") {
        $colorVarVal = $row[$colorVar];
        $response_row["color"] = $colorVarVal;
      }
      $response_row["reads"] = $sampleIDToValue[$sampleID];
      array_push($json_response, $response_row);
    }
  }

  mysqli_close($conn);

  echo json_encode($json_response);
?>