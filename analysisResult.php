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

function get_n_name($index) {
  $data['사례조사실시율'] = 'a';
  $data['사례조사기간준수율'] = 'b';
  $data['도말양성신환자치료성공률_전체'] = 'd';
  $data['도말양성신환자치료성공률_65세미만'] = 'd1';
  $data['도말양성신환자치료성공률_65세이상'] = 'd2';
  $data['치료성공률전체'] = 'f';
  $data['치료성공률전체_12개월미만'] = 'f';
  $data['치료중단율전체'] = 'f';
  $data['치료실패율전체'] = 'f';
  $data['치료사망률전체'] = 'f';
  $data['결핵관련사망률전체'] = 'f';
  $data['기타질병사망률전체'] = 'f';
  $data['치료중률전체'] = 'f';
  $data['치료성공률신환자'] = 'f1';
  $data['치료성공률신환자_12개월미만'] = 'f1';
  $data['치료중단율신환자'] = 'f1';
  $data['치료실패율신환자'] = 'f1';
  $data['치료사망률신환자'] = 'f1';
  $data['결핵관련사망률신환자'] = 'f1';
  $data['기타질병사망률신환자'] = 'f1';
  $data['치료중률신환자'] = 'f1';
  $data['치료성공률그외환자'] = 'f2';
  $data['치료성공률그외환자_12개월미만'] = 'f2';
  $data['치료중단율그외환자'] = 'f2';
  $data['치료실패율그외환자'] = 'f2';
  $data['치료사망률그외환자'] = 'f2';
  $data['결핵관련사망률그외환자'] = 'f2';
  $data['기타질병사망률그외환자'] = 'f2';
  $data['치료중률그외환자'] = 'f2';
  $data['치료성공률65세미만'] = 'f1_1';
  $data['치료성공률65세미만_12개월미만'] = 'f1_1';
  $data['치료중단율65세미만'] = 'f1_1';
  $data['치료실패율65세미만'] = 'f1_1';
  $data['치료사망률65세미만'] = 'f1_1';
  $data['결핵관련사망률65세미만'] = 'f1_1';
  $data['기타질병사망률65세미만'] = 'f1_1';
  $data['치료중률65세미만'] = 'f1_1';
  $data['치료성공률65세미만신환자'] = 'f1_1';
  $data['치료성공률65세미만신환자_12개월미만'] = 'f1_1';
  $data['치료중단율65세미만신환자'] = 'f1_1';
  $data['치료실패율65세미만신환자'] = 'f1_1';
  $data['치료사망률65세미만신환자'] = 'f1_1';
  $data['결핵관련사망률65세미만신환자'] = 'f1_1';
  $data['기타질병사망률65세미만신환자'] = 'f1_1';
  $data['치료중률65세미만신환자'] = 'f1_1';
  $data['치료성공률65세미만그외환자'] = 'f2_1';
  $data['치료성공률65세미만그외환자_12개월미만'] = 'f2_1';
  $data['치료중단율65세미만그외환자'] = 'f2_1';
  $data['치료실패율65세미만그외환자'] = 'f2_1';
  $data['치료사망률65세미만그외환자'] = 'f2_1';
  $data['결핵관련사망률65세미만그외환자'] = 'f2_1';
  $data['기타질병사망률65세미만그외환자'] = 'f2_1';
  $data['치료중률65세미만그외환자'] = 'f2_1';
  $data['치료성공률65세이상'] = 'f_2';
  $data['치료성공률65세이상_12개월미만'] = 'f_2';
  $data['치료중단율65세이상'] = 'f_2';
  $data['치료실패율65세이상'] = 'f_2';
  $data['치료사망률65세이상'] = 'f_2';
  $data['결핵관련사망률65세이상'] = 'f_2';
  $data['기타질병사망률65세이상'] = 'f_2';
  $data['치료중률65세이상'] = 'f_2';
  $data['치료성공률65세이상신환자'] = 'f1_2';
  $data['치료성공률65세이상신환자_12개월미만'] = 'f1_2';
  $data['치료중단율65세이상신환자'] = 'f1_2';
  $data['치료실패율65세이상신환자'] = 'f1_2';
  $data['치료사망률65세이상신환자'] = 'f1_2';
  $data['결핵관련사망률65세이상신환자'] = 'f1_2';
  $data['기타질병사망률65세이상신환자'] = 'f1_2';
  $data['치료중률65세이상신환자'] = 'f1_2';
  $data['치료성공률65세이상그외환자'] = 'f2_2';
  $data['치료성공률65세이상그외환자_12개월미만'] = 'f2_2';
  $data['치료중단율65세이상그외환자'] = 'f2_2';
  $data['치료실패율65세이상그외환자'] = 'f2_2';
  $data['치료사망률65세이상그외환자'] = 'f2_2';
  $data['결핵관련사망률65세이상그외환자'] = 'f2_2';
  $data['기타질병사망률65세이상그외환자'] = 'f2_2';
  $data['치료중률65세이상그외환자'] = 'f2_2';
  $data['전출률'] = 'o';
  $data['초치료지침준수율'] = 'q';
  $data['객담도말시행률'] = 's';
  $data['객담도말양성률'] = 's';
  $data['객담배양시행률'] = 's';
  $data['객담배양양성률'] = 's';
  $data['객담핵산증폭검사시행률'] = 's';
  $data['객담핵산증폭검사양성률'] = 's';
  $data['객담xpert시행률'] = 's';
  $data['객담xpert양성률'] = 's';
  $data['전체약제감수성시행률'] = 'bb';
  $data['전통방식약제감수성시행률'] = 'bb';
  $data['신속감수성검사시행률'] = 'bb';
  $data['전체약제감수성시행률신환자'] = 'bb1';
  $data['전통방식약제감수성시행률신환자'] = 'bb1';
  $data['신속감수성검사시행률신환자'] = 'bb1';
  $data['전체약제감수성시행률그외환자'] = 'bb2';
  $data['전통방식약제감수성시행률그외환자'] = 'bb2';
  $data['신속감수성검사시행률그외환자'] = 'bb2';
  $data['전체결핵환자에서다제내성률'] = 'ff';
  $data['전체결핵환자에서RIF단독내성률'] = 'ff';
  $data['전체결핵환자에서INH단독내성률'] = 'ff';
  $data['전체결핵환자에서다제내성률신환자'] = 'ff1';
  $data['전체결핵환자에서RIF단독내성률신환자'] = 'ff1';
  $data['전체결핵환자에서INH단독내성률신환자'] = 'ff1';
  $data['전체결핵환자에서다제내성률그외환자'] = 'ff2';
  $data['전체결핵환자에서RIF단독내성률그외환자'] = 'ff2';
  $data['전체결핵환자에서INH단독내성률그외환자'] = 'ff2';
  $data['전체폐결핵환자에서다제내성률'] = 'jj';
  $data['전체폐결핵환자에서RIF단독내성률'] = 'jj';
  $data['전체폐결핵환자에서INH단독내성률'] = 'jj';
  $data['전체폐결핵환자에서다제내성률신환자'] = 'jj1';
  $data['전체폐결핵환자에서RIF단독내성률신환자'] = 'jj1';
  $data['전체폐결핵환자에서INH단독내성률신환자'] = 'jj1';
  $data['전체폐결핵환자에서다제내성률'] = 'jj2';
  $data['전체폐결핵환자에서RIF단독내성률'] = 'jj2';
  $data['전체폐결핵환자에서INH단독내성률'] = 'jj2';
  $data['접촉자검진율성인19세이상'] = 'E1';
  $data['잠복결핵감염률성인19세이상'] = 'G1';
  $data['잠복결핵감염치료시작률성인19세이상'] = 'O1';
  $data['잠복결핵감염치료완료율성인19세이상심평원자료'] = 'Q1';
  $data['접촉자검진율성인19세이상34세미만'] = 'E2';
  $data['잠복결핵감염률성인19세이상34세미만'] = 'G2';
  $data['잠복결핵감염치료시작률성인19세이상34세미만'] = 'O2';
  $data['잠복결핵감염치료완료율성인19세이상34세미만심평원자료'] = 'Q2';
  $data['접촉자검진율성인35세이상64세미만'] = 'E3';
  $data['잠복결핵감염률성인35세이상64세미만'] = 'G3';
  $data['잠복결핵감염치료시작률성인35세이상64세미만'] = 'O3';
  $data['잠복결핵감염치료완료율성인35세이상64세미만심평원자료'] = 'Q3';
  $data['접촉자검진율성인65세이상'] = 'E4';
  $data['잠복결핵감염률성인65세이상'] = 'G4';
  $data['잠복결핵감염치료시작률성인65세이상'] = 'O4';
  $data['잠복결핵감염치료완료율성인65세이상심평원자료'] = 'Q4';
  $data['접촉자검진율소아'] = 'E5';
  $data['잠복결핵감염률소아'] = 'G5';
  $data['잠복결핵감염치료시작률소아'] = 'O5';
  $data['잠복결핵감염치료완료율소아심평원자료'] = 'Q5';
 
  return $data[$index];
}

$n_name = get_n_name($index);

if($hospital != "") {
  $query ="SELECT 연도, 분기, 권역, $region_text AS 기관, AVG($index) AS $index, $n_name AS n
            FROM newTotalUploadNomissing 
            WHERE 연도 = '$year' AND 분기 = '$quarter' AND 권역 IN ($region_text)
            UNION
            SELECT 연도, 분기, 권역, 기관, AVG($index) AS $index
            FROM newTotalUploadNomissing 
            WHERE 연도 = '$year' AND 분기 = '$quarter' AND 권역 IN ($region_text) AND 기관 IN ($hospital_text)
            GROUP BY 기관";
} else if($region != "") {
  $query ="SELECT 연도, 분기, '전국' AS 권역, '전국' AS 기관, AVG($index) AS $index, $n_name AS n
            FROM newTotalUploadNomissing
            WHERE 연도 = '$year' AND 분기 = '$quarter'
            UNION
            SELECT 연도, 분기, 권역, 기관, AVG($index) AS $index
            FROM newTotalUploadNomissing 
            WHERE 연도 = '$year' AND 분기 = '$quarter' AND 권역 IN ($region_text)
            GROUP BY 권역";
} else {
  $query ="SELECT 연도, 분기, 권역, 기관, $index, $n_name AS n FROM newTotalUploadNomissing $search_sql";
}
echo $query."<br>";
$result = $conn->query($query);
unset($chart_list);
while ( $rows = $result->fetch_array())
{
  $rows[$index] = round($rows[$index], 3);
  $data_name = ($hospital != "") ? $rows["기관"] : $rows["권역"];
  $chart_list[$data_name] = $rows;
}
?>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Element", "Density", { role: "style" } ],
        <?php
        $color = "";
        if(is_array($chart_list)) {
          foreach($chart_list as $name => $row) {
            $color = ($color == "") ? "silver" : "#007bff";
        ?>
        ["<?=$name?>", <?=$row[$index]?>, "<?=$color?>"],
        <?php
          }
        }
        ?>
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
function get_p_value($mu, $x, $n) {
  /*
  $mu=0.954;  // 상위 단위의 전체 평균 (예: 전체 or 권역)
  $x=0.915;   // 하위 단위의 각 평균들 (예: 기관(병원))
  $n=6;       // 하위 단위의 표본(n) 수
  $m_dif = $mu-$x;  // 상위단위 평균 - 하위단위 평균 (분자)
  $pq=$x*(1-$x);    // 하위 단위 비율*(1-비율)
  $denominator=sqrt($pq/$n);   // 분모 sqrt(p*(i-p)/n)
  $z= $m_dif/$denominator;     // z-value = 분자/분포

  echo "평균 차이는 {$m_dif} 입니다<br>\n";
  echo "p와 q의 곱은 {$pq} 입니다<br>\n";
  echo "분모는 {$denominator} 입니다<br>\n";
  echo "<br>";
  echo "z-value는 {$z} 입니다<br>\n";
  
  // p값 구하기
  // stat extension 설치 후 
  
  // PECL stats package를 설치해야 한다고 함
  // https://www.php.net/manual/en/stats.installation.php
  
  return stats_cdf_normal(1, $x, $mu, $denominator);
  */
  
}
$p = "참조범주";
$mu = "";
if(is_array($chart_list)) {
  foreach($chart_list as $name => $row) {
    $n = $row[''];
    if($mu == "") $mu = $row[$index];
    else $p = get_p_value($mu, $row[$index],$n);
    echo "<tr align='center'>
    <td> $name </td>
    <td> $row[$index] </td>
    <td> $p </td>
    </tr>
    ";
  }
}
?>
