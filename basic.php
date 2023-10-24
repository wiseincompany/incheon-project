<?php
include_once("db.php");
$Tquery = "SELECT round(avg(객담도말시행률), 1), round(avg(객담배양시행률), 1), round(avg(객담핵산증폭검사시행률), 1), round(avg(초치료지침준수율), 1), round(avg(전통방식약제감수성시행률), 1), round(avg(신속감수성검사시행률), 1), round(avg(치료중단율전체), 1), round(avg(치료중률전체), 1), round(avg(전출률), 1), round(avg(사례조사실시율), 1), round(avg(결핵의심및환자비율), 1), round(avg(객담도말시행률), 1), round(avg(객담배양시행률), 1), round(avg(접촉자검진율성인19세이상), 1), round(avg(잠복결핵감염률성인19세이상), 1), round(avg(잠복결핵감염치료시작률성인19세이상), 1), round(avg(접촉자검진율소아), 1), round(avg(잠복결핵감염률소아), 1), round(avg(잠복결핵감염치료시작률소아), 1) FROM newTotalUploadNomissing WHERE 연도=2023 AND 분기=2";
$Tresult = $conn->query($Tquery);
$query ="SELECT 권역, round(avg(객담도말시행률), 1), round(avg(객담배양시행률), 1), round(avg(객담핵산증폭검사시행률), 1), round(avg(초치료지침준수율), 1), round(avg(전통방식약제감수성시행률), 1), round(avg(신속감수성검사시행률), 1), round(avg(치료중단율전체), 1), round(avg(치료중률전체), 1), round(avg(전출률), 1), round(avg(사례조사실시율), 1), round(avg(결핵의심및환자비율), 1), round(avg(객담도말시행률), 1), round(avg(객담배양시행률), 1), round(avg(접촉자검진율성인19세이상), 1), round(avg(잠복결핵감염률성인19세이상), 1), round(avg(잠복결핵감염치료시작률성인19세이상), 1), round(avg(접촉자검진율소아), 1), round(avg(잠복결핵감염률소아), 1), round(avg(잠복결핵감염치료시작률소아), 1) FROM newTotalUploadNomissing WHERE 연도=2023 AND 분기=2 GROUP BY 권역";
$result = $conn->query($query);
?>

<h5> <다음은 전국 및 21개 권역의 2023년 2분기 코호트 활동성 결핵 환자 관리지표 현황입니다.> </h5> 
<table width= "1000" border="1" cellspacing="0" cell padding="2">
<tr align="center">
<td bgcolor="#cccccc">핵심 평가지표</td>
<td bgcolor="#cccccc">객담도말 시행률</td>
<td bgcolor="#cccccc">객담배양 시행률</td>
<td bgcolor="#cccccc">객담핵산증폭검사 시행률</td>
<td bgcolor="#cccccc">초치료지침 준수율</td>
<td bgcolor="#cccccc">전통방식약제 감수성 시행률</td>
<td bgcolor="#cccccc">신속감수성검사 시행률</td>
<td bgcolor="#cccccc">치료 중단율</td>
<td bgcolor="#cccccc">치료중 비율</td>
<td bgcolor="#cccccc">전출률</td>
<td bgcolor="#cccccc">개별역학조사 실시율</td>
<td bgcolor="#cccccc">결핵의심 및 환자비율</td>
<td bgcolor="#cccccc">객담도말검사 시행률</td>
<td bgcolor="#cccccc">객담배양검사 시행률</td>
<td bgcolor="#cccccc">접촉자 검진율(성인)</td>
<td bgcolor="#cccccc">잠복결핵 감염률(성인)</td>
<td bgcolor="#cccccc">잠복결핵 감염치료 시작률(성인)</td>
<td bgcolor="#cccccc">접촉자 검진율(소아)</td>
<td bgcolor="#cccccc">잠복결핵 감염률(소아)</td>
<td bgcolor="#cccccc">잠복결핵 감염치료 시작률(소아)</td>

<?php
  while ( $Trow = $Tresult->fetch_array())
  {
  echo "<tr align='center'>
  <td> 전국 평균 </td>
  <td> $Trow[0] </td>
  <td> $Trow[1] </td>
  <td> $Trow[2] </td>
  <td> $Trow[3] </td>
  <td> $Trow[4] </td>
  <td> $Trow[5] </td>
  <td> $Trow[6] </td>
  <td> $Trow[7] </td>
  <td> $Trow[8] </td>
  <td> $Trow[9] </td>
  <td> $Trow[10] </td>
  <td> $Trow[11] </td>
  <td> $Trow[12] </td>
  <td> $Trow[13] </td>
  <td> $Trow[14] </td>
  <td> $Trow[15] </td>
  <td> $Trow[16] </td>
  <td> $Trow[17] </td>
  <td> $Trow[18] </td>
  </tr>
  ";
  }

   while ( $row = $result->fetch_array())
   {
    echo "<tr align='center'>
    <td> $row[0] </td>
    <td> $row[1] </td>
    <td> $row[2] </td>
    <td> $row[3] </td>
    <td> $row[4] </td>
    <td> $row[5] </td>
    <td> $row[6] </td>
    <td> $row[7] </td>
    <td> $row[8] </td>
    <td> $row[9] </td>
    <td> $row[10] </td>
    <td> $row[11] </td>
    <td> $row[12] </td>
    <td> $row[13] </td>
    <td> $row[14] </td>
    <td> $row[15] </td>
    <td> $row[16] </td>
    <td> $row[17] </td>
    <td> $row[18] </td>
    <td> $row[19] </td>
    </tr>
    ";
  }
  ?>
