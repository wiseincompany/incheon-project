<?php
include "common.php";

$syear = isset($_POST['syear']) ? $_POST['syear'] : false;
$squarter = isset($_POST['squarter']) ? $_POST['squarter'] : false;
$eyear = isset($_POST['eyear']) ? $_POST['eyear'] : false;
$equarter = isset($_POST['equarter']) ? $_POST['equarter'] : false;
$region = isset($_POST['region']) ? $_POST['region'] : false;
$hospital = isset($_POST['hospital']) ? $_POST['hospital'] : false;
$index = isset($_POST['index']) ? $_POST['index'] : false;

$search_sql = "";
if($syear != "") $search_sql .= ($search_sql != "") ? " AND 연도 >= '$syear' " : " 연도 >= '$syear' ";   
if($squarter != "") $search_sql .= ($search_sql != "") ? " AND 분기 >= '$squarter' " : " 분기 >= '$squarter' ";    
if($eyear != "") $search_sql .= ($search_sql != "") ? " AND 연도 <= '$eyear' " : " 연도 <= '$eyear' ";   
if($equarter != "") $search_sql .= ($search_sql != "") ? " AND 분기 <= '$equarter' " : " 분기 <= '$equarter' ";    
if($region != "") {
    if(is_array($region)) {
        $region_text = "";
        foreach($region as $ii => $value) {
            if($region_text != "") $region_text .= ",";
            $region_text .= "'".$value."'";
        }
    }
    $search_sql .= ($search_sql != "") ? " AND 권역 IN ($region_text) " : " 권역 IN ($region_text) ";    
} 
if($hospital != "") {
    if(is_array($hospital)) {
        $hospital_text = "";
        foreach($hospital as $ii => $value) {
            if($hospital_text != "") $hospital_text .= ",";
            $hospital_text .= "'".$value."'";
        }
    }
    $search_sql .= ($search_sql != "") ? " AND 기관 IN ($hospital_text) " : " 기관 IN ($hospital_text) ";    
} 
if($search_sql != "") $search_sql = " WHERE " . $search_sql;
// $query ="SELECT 연도, 분기, 권역, $index FROM new_total_upload_nomissing $search_sql";
if(is_array($index)) $index = implode(",", $index);
$query ="SELECT 연도, 분기, 권역, 기관, $index FROM newTotalUploadNomissing $search_sql";
//echo $query."<br>";
$result = $conn->query($query);
?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', '강원권역', '강릉아산병원', '연세대학교 원주세브란스기독병원'],
          ['사례조사실시율', 98.5, 95.6, 96.8],
          ['사례조사기간준수율', 98.5, 95.6, 96.8],
          ['결핵의심자객도말검사양성률', 98.5, 95.6, 96.8]
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: ''            
          },
          legend: { position: 'bottom' }
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>

<div id="chart_div"></div> 

