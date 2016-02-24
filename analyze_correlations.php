
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

    <title>Mian</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<link href="css/ie10-viewport-bug-workaround.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="css/mian_custom.css" rel="stylesheet">
    <link href="css/plugins/bootstrap-multiselect.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!--<script src="js/ie-emulation-modes-warning.js"></script>-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <?php
      $page = "analyze";
      include "navbar.php";
    ?>

    <div class="editor">
      <h3>Correlation Analysis</h3>
      <label class="control-label">Taxonomic Level</label>
      <select id="taxonomy" name="taxonomy" class="form-control pad-bottom">
        <option value="Kingdom">Kingdom</option>
        <option value="Phylum">Phylum</option>
        <option value="Class">Class</option>
        <option value="Order">Order</option>
        <option value="Family">Family</option>
        <option value="Genus">Genus</option>
        <option value="Species">Species</option>
        <option value="OTU">OTU</option>
      </select>

      <select style="display:none;" id="taxonomy-specific" name="taxonomy-specific" class="form-control pad-bottom" multiple="multiple">
      </select>

      <hr />

      <label class="control-label">Correlation Variable</label>
      <select id="corrvar" name="corrvar" class="form-control">
        <option value="Density">16S rRNA/ul</option>
        <option value="SAV">SAV</option>
        <option value="CD68">CD68</option>
        <option value="CD79a">CD79a</option>
        <option value="CD4">CD4</option>
      </select>

      <label class="control-label">Color Variable</label>
      <select id="colorvar" name="colorvar" class="form-control">
        <option value="None">None</option>
        <option value="Disease">Disease</option>
        <option value="Location">Location</option>
        <option value="Individual">Individual</option>
        <option value="Core">Core</option>
        <option value="Fibrosis">Fibrosis</option>
        <option value="Batch">Batch</option>
      </select>

      <label class="control-label">Size Variable</label>
      <select id="sizevar" name="sizevar" class="form-control">
        <option value="None">None</option>
        <option value="Density">16S rRNA/ul</option>
        <option value="SAV">SAV</option>
        <option value="CD68">CD68</option>
        <option value="CD79a">CD79a</option>
        <option value="CD4">CD4</option>
      </select>
    </div>

    <div id="analysis-container" class="analysis-container">
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/jquery.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/d3.js"></script>
    <script src="js/plugins/bootstrap-multiselect.js"></script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<script src="js/ie10-viewport-bug-workaround.js"></script>-->


    <script>
      $( document ).ready(function() {
        updateTaxonomicLevel(true);
        createListeners();
      });

      function createListeners() {
        $("#taxonomy").change(function () {
          updateTaxonomicLevel(false);
        });

        $("#taxonomy-specific").change(function () {
          updateAnalysis();
        });

        $("#corrvar").change(function () {
          updateAnalysis();
        });

        $("#colorvar").change(function () {
          updateAnalysis();
        });

        $("#sizevar").change(function () {
          updateAnalysis();
        });
      }

      function updateTaxonomicLevel(firstLoad) {
        $.ajax({
          url: "http://localhost/mian/requests/taxonomies.php?level=" + getTaxonomicLevel(), 
          success: function(result) {
            var json = JSON.parse(result);
            $("#taxonomy-specific").empty();
            for (var i = 0; i < json.length; i++) {
              $("#taxonomy-specific").append("<option value=\"" + json[i] + "\">" + json[i] + "</option>");
            }
            if (firstLoad) {
              $('#taxonomy-specific').multiselect({
                buttonWidth: '320px',
                enableFiltering: true,
                //filterBehavior: 'value',
                maxHeight: 400
              });
            } else {
              $('#taxonomy-specific').multiselect('rebuild');
            }

            updateAnalysis();
          }
        });
      }

      function getTaxonomicLevel() {
        return $("#taxonomy").val();
      }

      function updateAnalysis() {
        var level = getTaxonomicLevel();
        var taxonomy = $("#taxonomy-specific").val();
        var corrvar = $("#corrvar").val();
        var colorvar = $("#colorvar").val();
        var sizevar = $("#sizevar").val();

        $.ajax({
          url: "requests/correlations.php?level=" + level + "&taxonomy=" + taxonomy + "&corrvar=" + corrvar + "&sizevar=" + sizevar + "&colorvar=" + colorvar, 
          success: function(result) {
            plotCorrelation(JSON.parse(result));
          }
        });
      }

      function plotCorrelation(data) {
        $("#analysis-container").empty();

        var margin = {top: 20, right: 20, bottom: 30, left: 40},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        // setup x 
        var xValue = function(d) { return d.corrvar;}, // data -> value
            xScale = d3.scale.linear().range([0, width]), // value -> display
            xMap = function(d) { return xScale(xValue(d));}, // data -> display
            xAxis = d3.svg.axis().scale(xScale).orient("bottom");

        // setup y
        var yValue = function(d) { return d.reads;}, // data -> value
            yScale = d3.scale.linear().range([height, 0]), // value -> display
            yMap = function(d) { return yScale(yValue(d));}, // data -> display
            yAxis = d3.svg.axis().scale(yScale).orient("left");

        // setup fill color
        var cValue = function(d) { return d.color;},
            color = d3.scale.category10();

        // setup circle size
        var sValue = function(d) { return d.size; };
        var minSValue = d3.min(data, sValue);
        var maxSValue = d3.max(data, sValue);
        var sScale = d3.scale.linear().domain([minSValue, maxSValue]).range([2, 8]);

        // add the graph canvas to the body of the webpage
        var svg = d3.select("#analysis-container").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        // don't want dots overlapping axis, so add in buffer to data domain
        xScale.domain([d3.min(data, xValue)-d3.max(data, xValue)*0.01, d3.max(data, xValue)+d3.max(data, xValue)*0.01]);
        yScale.domain([d3.min(data, yValue)-d3.max(data, yValue)*0.01, d3.max(data, yValue)+d3.max(data, yValue)*0.01]);

        // x-axis
        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis)
          .append("text")
            .attr("class", "label")
            .attr("x", width)
            .attr("y", -6)
            .style("text-anchor", "end")
            .text($("#corrval").val());

        // y-axis
        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
          .append("text")
            .attr("class", "label")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", ".71em")
            .style("text-anchor", "end")
            .text("Reads");

        // draw dots
        svg.selectAll(".dot")
            .data(data)
          .enter().append("circle")
            .attr("class", "dot")
            .attr("r", function(d) { 
                if ($("#sizevar").val() != "" && $("#sizevar").val() != "None") {
                  return sScale(sValue(d)); 
                } else {
                  return 3;
                }
              })
            .attr("cx", xMap)
            .attr("cy", yMap)
            .style("fill", function(d) { return color(cValue(d));}) ;

        if ($("#colorvar").val() != "" && $("#colorvar").val() != "None") {
          // draw legend
          var legend = svg.selectAll(".legend")
              .data(color.domain())
            .enter().append("g")
              .attr("class", "legend")
              .attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });

          // draw legend colored rectangles
          legend.append("rect")
              .attr("x", width - 18)
              .attr("width", 18)
              .attr("height", 18)
              .style("fill", color);

          // draw legend text
          legend.append("text")
              .attr("x", width - 24)
              .attr("y", 9)
              .attr("dy", ".35em")
              .style("text-anchor", "end")
              .text(function(d) { return d;})
        }
      }
    </script>

  </body>
</html>
