<?php
include "common.php";
// test 위해서 get 데이터 할당
if($_GET['syear'] != "") $_POST = $_GET;

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
$query ="SELECT 연도, 분기, 권역, 기관, AVG($index) AS $index FROM newTotalUploadNomissing $search_sql GROUP BY 연도,분기";
echo $query."<br>";
$result = $conn->query($query);
unset($chart_list);
while ( $rows = $result->fetch_array())
{
  $data_name = ($hospital != "") ? $rows["기관"] : $rows["권역"];
  $chart_list['column'][$data_name] = $data_name;
  $rows_name = $rows['연도']."_".$rows['분기'];
  $chart_list['rows'][$rows_name][$data_name] = $rows;
}
echo "<pre>";
print_r($chart_list);
echo "</pre>";
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['corechart', 'line']});
    google.charts.setOnLoadCallback(drawCurveTypes);

    function drawCurveTypes() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'X');
      <?php
      if(is_array($chart_list['column'])) {
        foreach($chart_list['column'] as $ii => $column) {
      ?>
      data.addColumn('number', '<?=$column?>');
      <?php
        }
      }
      ?>

      data.addRows([
        <?php
        if(is_array($chart_list['rows'])) {
          foreach($chart_list['rows'] as $ii => $rows) {
            list($y, $q) = explode("_", $ii);
            $data_str = "";
            if(is_array($rows)) {
              foreach($rows as $jj => $row) {
                if($data_str != "") $data_str .= ",";
                $data_str = $row[$index];
              }
            }
        ?>
        ['<?=$y?>년 <?=$q?>분기', <?=$data_str?>],
        <?php
          }
        }
        ?>
      ]);

      var options = {
        hAxis: {
          title: ''
        },
        vAxis: {
          title: ''
        },
        series: {
          1: {curveType: 'function'}
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
</script>

<div id="chart_div"></div> 

<hr>
<h5> <다음은 검색 결과 지표입니다.> </h5>
<table width= "800" border="1" cellspacing="0" cell padding="5">
<tr align="center">
<td bgcolor="#cccccc"></td>
<td bgcolor="#cccccc">강동경희대학교의대병원</td>
<td bgcolor="#cccccc">삼성서울병원</td>
<td bgcolor="#cccccc">성심의료재단강동성심병원</td>
</tr>
<tr align="center">
<td bgcolor="#eeeeee">목표</td>
<td bgcolor="#eeeeee">≥ 95.0</td>
<td bgcolor="#eeeeee">≥ 95.0</td>
<td bgcolor="#eeeeee">≥ 95.0</td>
</tr>

<?php
    echo "<tr align='center'>
    <td> 2022 년 2 분기 </td>
    <td> 100 </td>
    <td> 81.8 </td>
    <td> 71.4 </td>
    </tr>
    ";
    echo "<tr align='center'>
    <td> 2022 년 3 분기 </td>
    <td> 100 </td>
    <td> 100 </td>
    <td> 80 </td>
    </tr>
    ";
    echo "<tr align='center'>
    <td> 2022 년 4 분기 </td>
    <td> 40 </td>
    <td> 75 </td>
    <td> 0 </td>
    </tr>
    ";

/*
while ( $rows = $result->fetch_array())
{
    echo "<tr align='center'>
    <td> $rows[0] 년 $rows[1] 분기 </td>
    <td> $rows[2] </td>
    <td> $rows[3] </td>
    <td> $rows[4] </td>
    </tr>
    ";
}
*/

// $nrows= mysqli_fetch_all($result, MYSQLI_ASSOC);
// echo "<br>";
// echo $query."<br>";
// echo "<pre>";
// print_r($nrows);
// echo "</pre>";

?>
