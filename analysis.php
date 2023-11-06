<?php
  error_reporting( E_ALL );
  ini_set( "display_errors", 1 );
?>

<?php 
include_once 'db.php';
    $year = isset($_POST['year']) ? $_POST['year'] : false;
    $quarter = isset($_POST['quarter']) ? $_POST['quarter'] : false;
    $region = isset($_POST['region']) ? $_POST['region'] : false;
    $hospital = isset($_POST['hospital']) ? $_POST['hospital'] : false;
    $index = isset($_POST['index']) ? $_POST['index'] : false;

    echo "연도: {$year} <br>"; 
    echo "분기: {$quarter} <br>"; 
    echo "권역: {$region} <br>"; 
    //echo "기관: {$hospital} <br>"; 
    echo "지표: {$index}" ; 

$search_sql = "";
if($year != "") $search_sql .= ($search_sql != "") ? " AND 연도 = '$year' " : " 연도 = '$year' ";   
if($quarter != "") $search_sql .= ($search_sql != "") ? " AND 분기 = '$quarter' " : " 분기 = '$quarter' ";    
if($region != "") $search_sql .= ($search_sql != "") ? " AND 권역 = '$region' " : " 권역 = '$region' ";    
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
$query ="SELECT 연도, 분기, 권역, 기관, $index FROM newTotalUploadNomissing $search_sql";
$chart_query = "SELECT round(avg($index), 1) FROM newTotalUploadNomissing $search_sql";

$result = $conn->query($query);
$chart_result = $conn->query($chart_query);
$chart_row = $chart_result->fetch_array();

$data=array('구리', 8.94);
$options = array(
	'title' => "지표입니다",
	'width' => 400, 'height' => 500
);
echo "<!--";
echo "<br>";
echo $query."<br>";
echo "<br>";
echo $chart_query."<br>";
echo $chart_row[0]."<br>";
echo "-->";
?>

<hr>
<?php/*
<script src="//www.google.com/jsapi"></script>

<script>
var data = <?= json_encode($data) ?>;
var options = <?= json_encode($options) ?>;
google.load('visualization', '1.0', {'packages':['corechart']});
google.setOnLoadCallback(function() {
  var chart = new google.visualization.ColumnChart(document.querySelector('#chart_div'));
  chart.draw(google.visualization.arrayToDataTable(data), options);
});
</script> 
*/?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Year', 'Sales', 'Expenses', 'Profit'],
        ['2014', 1000, 400, 200],
        ['2015', 1170, 460, 250],
        ['2016', 660, 1120, 300],
        ['2017', 1030, 540, 350]
    ]);

    var options = {
        chart: {
        title: 'Company Performance',
        subtitle: 'Sales, Expenses, and Profit: 2014-2017',
        }
    };

    var chart = new google.charts.Bar(document.getElementById('chart_div'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>

<div id="chart_div"></div> 

<hr>
<h5> <다음은 검색 결과 지표입니다.> </h5>
<table width= "800" border="1" cellspacing="0" cell padding="5">
<tr align="center">
<td bgcolor="#cccccc">연도</td>
<td bgcolor="#cccccc">분기</td>
<td bgcolor="#cccccc">권역</td>
<td bgcolor="#cccccc">기관(병원)</td>
<td bgcolor="#cccccc">지표</td>

<?php
while ( $rows = $result->fetch_array())
{
    echo "<tr align='center'>
    <td> $rows[0] </td>
    <td> $rows[1] </td>
    <td> $rows[2] </td>
    <td> $rows[3] </td>
    <td> $rows[4] </td>
    </tr>
    ";
}

// $nrows= mysqli_fetch_all($result, MYSQLI_ASSOC);
// echo "<br>";
// echo $query."<br>";
// echo "<pre>";
// print_r($nrows);
// echo "</pre>";

?>
