<?php
include "common.php";

$year = isset($_POST['year']) ? $_POST['year'] : false;
$quarter = isset($_POST['quarter']) ? $_POST['quarter'] : false;
$region = isset($_POST['region']) ? $_POST['region'] : false;
$hospital = isset($_POST['hospital']) ? $_POST['hospital'] : false;
$index = isset($_POST['index']) ? $_POST['index'] : false;

$search_sql = "";
if($year != "") { if($search_sql != "") $search_sql .= " AND "; $search_sql .= " 연도 = '$year' "; }
if($quarter != "") { if($search_sql != "") $search_sql .= " AND ";  $search_sql .= " 분기 = '$quarter' "; }
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

if(is_array($index)) {
  $index = implode(",", $index);
}
if($search_sql != "") $search_sql = " WHERE " . $search_sql;
// $query ="SELECT 연도, 분기, 권역, $index FROM new_total_upload_nomissing $search_sql";

if($hospital != "") {
  $query ="SELECT 연도, 분기, 권역, $region_text AS 기관, AVG($index) AS $index
            FROM newTotalUploadNomissing 
            WHERE 연도 = '$year' AND 분기 = '$quarter' AND 권역 IN ($region_text)
            UNION
            SELECT 연도, 분기, 권역, 기관, AVG($index) AS $index
            FROM newTotalUploadNomissing 
            WHERE 연도 = '$year' AND 분기 = '$quarter' AND 권역 IN ($region_text) AND 기관 IN ($hospital_text)
            GROUP BY 기관";
} else if($region != "") {
  $query ="SELECT 연도, 분기, 권역, '전국' AS 기관, AVG($index) AS $index
            FROM newTotalUploadNomissing
            WHERE 연도 = '$year' AND 분기 = '$quarter'
            UNION
            SELECT 연도, 분기, 권역, 기관, AVG($index) AS $index
            FROM newTotalUploadNomissing 
            WHERE 연도 = '$year' AND 분기 = '$quarter' AND 권역 IN ($region_text)
            GROUP BY 권역";
} else {
  $query ="SELECT 연도, 분기, 권역, 기관, $index FROM newTotalUploadNomissing $search_sql";
}
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

echo "<Pre>";
print_r($chart_list);
echo "</pre>";

?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        ["전국", 98.5, "silver"],
        ["강원권역", 95.6, "#007bff"],
        ["경북권역", 96.8, "#007bff"],
        ["광주권역", 93.4, "#007bff"],
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "",
        width: 600,
        height: 400,
        bar: {groupWidth: "95%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_div"));
      chart.draw(view, options);
  }
  </script>

<div id="chart_div"></div> 

<hr>
<h5> <다음은 검색 결과 지표입니다.> </h5>
<table width= "800" border="1" cellspacing="0" cell padding="5">
<tr align="center">
<td bgcolor="#cccccc"></td>
<td bgcolor="#cccccc"><?=$index?></td>
<td bgcolor="#cccccc">p</td>
</tr>
<tr align="center">
<td bgcolor="#eeeeee">목표</td>
<td bgcolor="#eeeeee">≥ 95.0</td>
<td bgcolor="#eeeeee"></td>
</tr>

<?php
    echo "<tr align='center'>
    <td> 전국 </td>
    <td> 98.5 </td>
    <td> 참조범주 </td>
    </tr>
    ";
    echo "<tr align='center'>
    <td> 강원권역 </td>
    <td> 95.6 </td>
    <td> p=0.041* </td>
    </tr>
    ";
    echo "<tr align='center'>
    <td> 경북권역 </td>
    <td> 96.8 </td>
    <td> P=0.542 </td>
    </tr>
    ";
    echo "<tr align='center'>
    <td> 광주권역 </td>
    <td> 93.4 </td>
    <td> P=0.001** </td>
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
