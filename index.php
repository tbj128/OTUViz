
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

    <title>OTUViz</title>

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
    <div id="welcome" class="container">
      <div id="welcome-container" class="row welcome-container">
        <div class="row" style="margin-bottom:60px">
          <div class="col-md-12">
            <h1>Visualize OTU tables in your browser.</h1>
            <h4>To start, you'll need the following information:</h4>
          </div>
        </div>
        <div class="row" style="margin-bottom:30px;">
          <div class="col-md-3"></div>
          <div class="col-md-3">
            <h3 style="margin-top:0px">OTU Table</h3>
            <h5>CSV-formatted file with OTUs across the top header and sample/group IDs as rows</h5>
          </div>
          <div class="col-md-3">
            <span id="otuTableWrapper" class="btn btn-default btn-file">
                Upload <input type="file" name="File Upload" id="otuTable" accept=".csv" />
            </span>
            <span id="otuTableOK" style="display:none;font-size:24px"><i class="glyphicon glyphicon-ok"></i></span>
          </div>
          <div class="col-md-3"></div>
        </div>

        <div class="row" style="margin-bottom:30px;">
          <div class="col-md-3"></div>
          <div class="col-md-3">
            <h3 style="margin-top:0px">OTU Taxonomy Mapping</h3>
            <h5>CSV-formatted file with each row representing an OTU and its corresponding taxonomy information</h5>
          </div>
          <div class="col-md-3">
            <span id="otuTaxonomyMappingWrapper" class="btn btn-default btn-file">
                Upload <input type="file" name="File Upload" id="otuTaxonomyMapping" accept=".csv" />
            </span>
            <span id="otuTaxonomyMappingOK" style="display:none;font-size:24px"><i class="glyphicon glyphicon-ok"></i></span>
          </div>
          <div class="col-md-3"></div>
        </div>

        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-3">
            <h3 style="margin-top:0px">Sample ID Mapping</h3>
            <h5>CSV-formatted file with each row representing an sample/group ID and its corresponding metadata</h5>
          </div>
          <div class="col-md-3">
            <span id="sampleIDMappingWrapper" class="btn btn-default btn-file">
                Upload <input type="file" name="File Upload" id="sampleIDMapping" accept=".csv" />
            </span>
            <span id="sampleIDMappingOK" style="display:none;font-size:24px"><i class="glyphicon glyphicon-ok"></i></span>
          </div>
          <div class="col-md-3"></div>
        </div>
      </div>
    </div><!-- /.container -->


    <div id="editor" class="editor" style="display:none;">
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
        <option value="SAV">SAV</option>
        <option value="VvCD68">CD68</option>
        <option value="VvCD79a">CD79a</option>
        <option value="VvCD4">CD4</option>
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
        <option value="SAV">SAV</option>
        <option value="VvCD68">CD68</option>
        <option value="VvCD79a">CD79a</option>
        <option value="VvCD4">CD4</option>
      </select>
    </div>

    <div id="analysis-container" class="analysis-container" style="display:none;">
    </div><!-- /.container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery.min.js"><\/script>')</script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/d3.js"></script>
    <script src="js/jquery.csv.js"></script>
    <script src="js/plugins/bootstrap-multiselect.js"></script>

    <script type="text/javascript">  
        $(document).ready(function() {
          // Global variables storing the data
          var otuTableData = [];
          var otuTableDataRow = {};
          var otuTableDataCol = {};

          var otuTaxonomyMappingData = [];
          var otuTaxonomyMappingRow = {};
          var otuTaxonomyMappingCol = {};

          var sampleIDMappingData = [];
          var sampleIDMappingRow = {};
          var sampleIDMappingCol = {};

          // The event listener for the file upload
          document.getElementById('otuTable').addEventListener('change', upload, false);
          document.getElementById('otuTaxonomyMapping').addEventListener('change', upload, false);
          document.getElementById('sampleIDMapping').addEventListener('change', upload, false);

          // Method that checks that the browser supports the HTML5 File API
          function browserSupportFileUpload() {
              var isCompatible = false;
              if (window.File && window.FileReader && window.FileList && window.Blob) {
                isCompatible = true;
              }
              return isCompatible;
          }

          function processOTUTable(data) {
            var d = [];
            for (var i = 0; i < data.length; i++) {
              if (i == 0) {
                for (var j = 0; j < data[i].length; j++) {
                  otuTableDataCol[j] = data[i][j];
                }
              } else {
                otuTableDataRow[data[i][1]] = i;
                d.push(data[i]);
              }
            }
            otuTableData = d;
          }

          function processOTUTaxonomyMapping(rawotuTaxonomyMappingData) {
            var tax = ["Kingdom", "Phylum", "Class", "Order", "Family", "Genus", "Species"];
            var d = [];
            for (var i = 0; i < rawotuTaxonomyMappingData.length; i++) {
              if (i == 0) {
                var fullTax = rawotuTaxonomyMappingData[i + 1][2];
                var fullTaxArr = fullTax.split(';');
                for (var j = 0; j < fullTaxArr.length; j++) {
                  otuTaxonomyMappingCol[tax[j]] = j + 2;
                }
              } else {
                fullTax = rawotuTaxonomyMappingData[i][2];
                fullTaxArr = fullTax.split(';');
                newArr = []
                newArr.push(rawotuTaxonomyMappingData[i][0]);
                newArr.push(rawotuTaxonomyMappingData[i][1]);
                otuTaxonomyMappingRow[i] = rawotuTaxonomyMappingData[i][0];
                for (var j = 0; j < fullTaxArr.length; j++) {
                  if (fullTaxArr[j] != "") {
                    newArr.push(fullTaxArr[j]);
                  }
                }
                d.push(newArr);
              }
            }
            otuTaxonomyMappingData = d;
          }

          function processSampleIDMapping(data) {
            var d = [];
            for (var i = 0; i < data.length; i++) {
              if (i == 0) {
                for (var j = 0; j < data[i].length; j++) {
                  sampleIDMappingCol[data[i][j]] = j;
                }
              } else {
                sampleIDMappingRow[data[i][0]] = i - 1;
                d.push(data[i]);
              }
            }
            sampleIDMappingData = d;
          }

          // Method that reads and processes the selected file
          function upload(evt) {
            if (!browserSupportFileUpload()) {
              alert('The File APIs are not fully supported in this browser!');
            } else {
                var data = null;
                var file = evt.target.files[0];
                var reader = new FileReader();
                reader.readAsText(file);
                reader.onload = function(event) {
                    var csvData = event.target.result;
                    data = $.csv.toArrays(csvData);
                    if (data && data.length > 0) {
                      console.log('Imported -' + data.length + '- rows successfully! For ' + evt.target.id);
                      if (evt.target.id == "otuTable") {
                        $('#otuTableWrapper').hide();
                        $('#otuTableOK').show();
                        processOTUTable(data);
                      } else if (evt.target.id == "otuTaxonomyMapping") {
                        $('#otuTaxonomyMappingWrapper').hide();
                        $('#otuTaxonomyMappingOK').show();
                        processOTUTaxonomyMapping(data);
                      } else if (evt.target.id == "sampleIDMapping") {
                        $('#sampleIDMappingWrapper').hide();
                        $('#sampleIDMappingOK').show();
                        processSampleIDMapping(data);
                      }

                      if(otuTableData.length > 0 && sampleIDMappingData.length > 0 && otuTaxonomyMappingData.length > 0) {
                        $('#welcome').hide();
                        $('#editor').show();
                        $('#analysis-container').show();
                        updateTaxonomicLevel(true);
                        createListeners();
                      }
                    } else {
                       alert('No data to import!');
                    }
                };
                reader.onerror = function() {
                    alert('Unable to read ' + file.fileName);
                };
            }
          }





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
          var level = getTaxonomicLevel();
          var taxLevelCol = otuTaxonomyMappingCol[level];
          var taxonomies = {};
          for (var i = 0; i < otuTaxonomyMappingData.length; i++) {
            taxonomies[otuTaxonomyMappingData[i][taxLevelCol]] = 1;
          }

          $("#taxonomy-specific").empty();
          var keys = Object.keys(taxonomies);
          for (var i = 0; i < keys.length; i++) {
            $("#taxonomy-specific").append("<option value=\"" + keys[i] + "\">" + keys[i] + "</option>");
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

        function getTaxonomicLevel() {
          return $("#taxonomy").val();
        }

        function updateAnalysis() {
          var level = getTaxonomicLevel();
          var taxonomy = $("#taxonomy-specific").val();
          var corrvar = $("#corrvar").val();
          var colorvar = $("#colorvar").val();
          var sizevar = $("#sizevar").val();

          if (taxonomy == null || corrvar == null || taxonomy == "" || corrvar == "") {
            return;
          }

          // TODO: Multiple taxonomies (comma separated)
          // Get all OTUs with taxonomy level and taxonomy
          var taxLevelCol = otuTaxonomyMappingCol[level];
          var relevantOTUs = {};
          for (var i = 0; i < otuTaxonomyMappingData.length; i++) {
            if (otuTaxonomyMappingData[i][taxLevelCol] == taxonomy) {
              relevantOTUs[otuTaxonomyMappingData[i][0]] = 1;
            }
          }

          result = [];
          // Sum across relevant OTUs for each sample
          for (var i = 0; i < otuTableData.length; i++) {
            var otuReadCount = 0;
            for (var j = 0; j < otuTableData[i].length; j++) {
              var otu = otuTableDataCol[j];
              if (relevantOTUs.hasOwnProperty(otu)) {
                otuReadCount = otuReadCount + parseFloat(otuTableData[i][j]);
              }
            }

            resultObj = {};
            resultObj["reads"] = otuReadCount;

            var sampleMetadataRow = sampleIDMappingRow[otuTableData[i][1]];
            var corrVarCol = sampleIDMappingCol[corrvar];
            var corrVarVal = sampleIDMappingData[sampleMetadataRow][corrVarCol];
            resultObj["corrvar"] = parseFloat(corrVarVal);

            if (colorvar != "" && colorvar != "None") {
              var colorCol = sampleIDMappingCol[colorvar];
              var colorVal = sampleIDMappingData[sampleMetadataRow][colorCol];
              resultObj["color"] = colorVal;
            }

            if (sizevar != "" && sizevar != "None") {
              var sizeCol = sampleIDMappingCol[sizevar];
              var sizeVal = sampleIDMappingData[sampleMetadataRow][sizeCol];
              resultObj["size"] = parseFloat(sizeVal);
            }

            result.push(resultObj);
          }

          plotCorrelation(result);
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


      });
    </script>

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <!--<script src="js/ie10-viewport-bug-workaround.js"></script>-->

  </body>
</html>
