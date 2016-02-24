<?php
  $analyzeActive = "";
  if ($page == "analyze") {
    $analyzeActive = "active";
  }
  $addActive = "";
  if ($page == "add") {
    $addActive = "active";
  }
?>
  <nav class="navbar navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">mian</a>
      </div>
      <div id="navbar" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
          <li class="dropdown <?php echo $analyzeActive; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Analyze <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="analyze_correlations.php">Correlations</a></li>
              <li><a href="analyze_experiments.php">Experiments</a></li>
            </ul>
          </li>
          <li class="dropdown <?php echo $addActive ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Data <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="add_otus.php">OTUs</a></li>
              <li><a href="add_samples.php">Samples</a></li>
              <li role="separator" class="divider"></li>
              <li><a href="add_experiments.php">Experiments</a></li>
            </ul>
          </li>
          <li><a href="#">About</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>
