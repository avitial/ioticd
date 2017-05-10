<!--
Data is printed through array values. These arrays were pulled from the db and organizd within index.php.
Google Charts API is used to generate the graph.
    Line Chart used: https://developers.google.com/chart/interactive/docs/gallery/linechart#data-format

-->
<html>
<head>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'Packet Number');
      data.addColumn('number', 'Temp');
      data.addColumn('number', 'Humidity');
      data.addColumn('number', 'Moisture');

      data.addRows([
        <?php 
          for($i=0; $i<sizeof($qid); $i++){
            if($i!=sizeof($qid)) {
              echo "[ ". $qid[$i] .", ". $qT[$i] .", ". $qH[$i] .", ". $qM[$i]."],";
            }//if
            else{
              echo "[ ". $qid[$i] .", ". $qT[$i] .", ". $qH[$i] .", ". $qM[$i]."]";
            }//else
            
          }//for
        
        ?>

      ]);

      var options = {
        chart: {
          title: 'Database: Irrigation',
          subtitle: 'Table: dataset'
          },//chart
        width: 900,
        height: 500,
        axes:{
          x:{
            0:{label: 'Packet Number'}
          },//x
          y:{
            0:{label: 'IDK'}
          }//y
        }//axes

      };//options

      var chart = new google.charts.Line(document.getElementById('line_top_x'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    }
  </script>
</head>
<body>
  <div id="line_top_x"></div>
</body>
</html>










