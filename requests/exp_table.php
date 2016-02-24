<?php

  $taxonomyLevel = $_GET["level"];
  $taxonomy = $_GET["taxonomy"];
  $exp = "";
  if (ISSET($_GET["exp"])) {
    $exp = $_GET["exp"];
  }

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


  $whereStatement = "results.TaxonomicLevel = '" . $taxonomyLevel . "' ";
  $exp = $conn->real_escape_string($exp);
  if ($exp != "") {
    $whereStatement .= " AND experiments.Experiment = '" . $exp . "' "; 
  }

  if ($taxonomy != "") {
    $whereStatement .= " AND (";
    $taxonomies = explode(",", $taxonomy);
    $isFirst = true;
    foreach ($taxonomies as $t) {
      if ($isFirst) {
        $isFirst = false;
        $whereStatement .= "results.Taxonomy = '" . $t .  "' ";
      } else {
        $whereStatement .= " OR results.Taxonomy = '" . $t .  "' ";
      }
    }
    $whereStatement .= ")";
  }

  $uniqueMetrics = array();
  $metricsResponse = array();
  $taxonomyExpToMetrics = array();
  $stmt = $conn->prepare("SELECT results.Taxonomy, results.MetricType, results.Metric, experiments.Experiment FROM results LEFT JOIN experiments ON results.ExpID=experiments.ExpID WHERE $whereStatement");
  $stmt->execute();

  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $uniqueMetrics[$row["MetricType"]] = 1;
    if (array_key_exists($row["Taxonomy"], $taxonomyExpToMetrics)) {
      if (array_key_exists($row["Experiment"], $taxonomyExpToMetrics[$row["Taxonomy"]])) {
        $taxonomyExpToMetrics[$row["Taxonomy"]][$row["Experiment"]][$row["MetricType"]] = $row["Metric"];
      } else {
        $exps = $taxonomyExpToMetrics[$row["Taxonomy"]];
        $exp = array();
        $exp[$row["MetricType"]] = $row["Metric"];
        $exps[$row["Experiment"]] = $exp;
        $taxonomyExpToMetrics[$row["Taxonomy"]] = $exps;
      }
    } else {
      $exps = array();
      $exp = array();
      $exp[$row["MetricType"]] = $row["Metric"];
      $exps[$row["Experiment"]] = $exp;
      $taxonomyExpToMetrics[$row["Taxonomy"]] = $exps;
    }
  }

  $metricsResponse["unique_keys"] = array_keys($uniqueMetrics); 
  $metricsList = array();
  foreach ($taxonomyExpToMetrics as $t => $exps) {
    foreach ($exps as $expname => $metrics) {
      $metricResponse = array();
      $metricResponse["Taxonomy"] = $t;
      $metricResponse["Expr"] = $expname;
      foreach ($uniqueMetrics as $key => $value) {
        if (array_key_exists($key, $metrics)) {
          $metricResponse[$key] = $metrics[$key];
        } else {
          $metricResponse[$key] = "-";
        }
      }
      array_push($metricsList, $metricResponse);
    }
  }
  $metricsResponse["metrics"] = $metricsList; 

  mysqli_close($conn);

  echo json_encode($metricsResponse);
?>