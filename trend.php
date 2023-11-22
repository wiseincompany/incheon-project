<?php
include "common.php";

$year_query ="SELECT DISTINCT 연도 FROM newTotalUploadNomissing";
$year_result = $conn->query($year_query);
if($year_result->num_rows> 0){
    $year_options= mysqli_fetch_all($year_result, MYSQLI_ASSOC);}

$quarter_query ="SELECT DISTINCT 분기 FROM newTotalUploadNomissing ORDER BY 분기 ASC";
$quarter_result = $conn->query($quarter_query);
if($quarter_result->num_rows> 0){
    $quarter_options= mysqli_fetch_all($quarter_result, MYSQLI_ASSOC);}

$region_query ="SELECT DISTINCT 권역 FROM newTotalUploadNomissing";
$region_result = $conn->query($region_query);
if($region_result->num_rows> 0){
    $region_options= mysqli_fetch_all($region_result, MYSQLI_ASSOC);}

$hospital_query ="SELECT DISTINCT 기관 FROM newTotalUploadNomissing";
$hospital_result = $conn->query($hospital_query);
if($hospital_result->num_rows> 0){
    $hospital_options= mysqli_fetch_all($hospital_result, MYSQLI_ASSOC);}

$index_query ="SHOW COLUMNS FROM newTotalUploadNomissing";
$index_result = $conn->query($index_query);
if($index_result->num_rows> 0){
    $index_options= mysqli_fetch_all($index_result, MYSQLI_ASSOC);
    if(is_array($index_options)) {
        foreach($index_options as $ii => $index_array) {
            if(!check_index_title($index_array['Field'])) { // 한글로 시작하지 않는 경우 배열에서 삭제함 (영여, 숫자, 한글, 특수문자(_) 허용)
                unset($index_options[$ii]);
            }
            if(in_array($index_array['Field'], array("연도", "분기", "차시", "권역", "기관"))) {   // 연도, 분기, 차시, 권역, 기관 배열에서 삭제함 ==> 삭제할 필드명은 array 에 추가
                unset($index_options[$ii]);
            }
        }
    }
    /*
    $index_options= array_slice($index_options,6);
    $index_options= array_column($index_options, '객담도말시행률');
    */
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>결핵 환자 관리지표</title>
	<meta charset="utf-8">
	<link href="style.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
	<link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.css">
	<link href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
	<script src="https://code.jquery.com/jquery-latest.min.js"></script>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>

	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/bootstrap-table.min.js"></script>
	<script src="https://unpkg.com/bootstrap-table@1.16.0/dist/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
	<script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function(){
            $.ajax({
                url: "basic.php",
                type: "post",
            }).done(function(data) {
                $('#result').html(data);
            });

            $('.selectpicker').selectpicker();

            $("select[name='syear']").on("change", function() {
                setOption($(this).attr("id"), "squarter");
            });
            $("select[name='eyear']").on("change", function() {
                setOption($(this).attr("id"), "equarter");
            });
            $("select[name='region[]']").on("change", function() {

                if($(this).find("option:selected").length == 1) {
                    setOption($(this).attr("id"), "hospital");
                    $("#hospital").attr("disabled", false);
                } else {
                    $("#hospital").prop("checked", false);
                    $("#hospital").attr("disabled", true);
                }

            });

            $("#btn_analysis").on("click", function() {
                if($("#index").val() == "") {
                    alert('지표를 선택하세요');
                    $("#index").focus();
                    return false;
                }
                //console.log($("#searchfrm").serialize());

                $.post("trendResult.php",$("#searchfrm").serialize(), function(data) {
                    $("#result").html(data);
                });
            });
        });

        function setOption(selectid, setid) {
            var param = "";
            if(selectid == "syear" || selectid == "eyear") param = "year=" + $("#"+selectid).val();
            else param = $("#"+selectid).attr("name") + "=" + $("#"+selectid).val();
            
            $.post("ajaxData.php", param, function(data) {
                $("#"+setid).selectpicker('destroy');
                $("#"+setid+" option").remove();
                $("#"+setid).append(data);
                $("#"+setid).selectpicker("refresh");
                console.log(data);
            });
	    }
    </script>

</head>
<body>
    <div class="container w-100" align="left">
        <form method="post" action="analysis.php" id="searchfrm" onsubmit="return false;">
		<div class="title" style="border-radius:5px;">
			<h3>결핵 환자 관리지표 </h3>
			<h6>전국 코호트 활동성 결핵 환자 관리지표입니다</h6>
		</div>

        <div class="upper w-100" style="border-radius:5px;">

            <div class="left">
            
                <div> 
                    <p> ▶ <strong>시작 분기</strong>를 선택해주세요 </p>
                    <select id="syear" name="syear" class="selectpicker" data-width="40%" title="연도 선택" aria-label="start year">
                        <?php
                        if(is_array($year_options)) {
                            foreach($year_options as $ii => $array) {
                        ?>
                        <option><?=$array['연도']?></option>
                        <?php
                            }
                        }
                        ?>
                    </select> 
                    연도 
                    <select id="squarter" name="squarter" class="selectpicker" data-width="40%" title="분기 선택" aria-label="start quarter">
                        <?php
                        if(is_array($quarter_options)) {
                            foreach($quarter_options as $ii => $array) {
                        ?>
                        <option><?=$array['분기']?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    분기
                    <p></p> 
                </div>

                <div> 
                    <p> ▶ <strong>종료 분기</strong>를 선택해주세요 </p>
                    <select id="eyear" name="eyear" class="selectpicker" data-width="40%" title="연도 선택" aria-label="end year">
                        <?php
                        if(is_array($year_options)) {
                            foreach($year_options as $ii => $array) {
                        ?>
                        <option><?=$array['연도']?></option>
                        <?php
                            }
                        }
                        ?>
                    </select> 
                    연도 
                    <select id="equarter" name="equarter" class="selectpicker" data-width="40%" title="분기 선택" aria-label="end quarter">
                        <?php
                        if(is_array($quarter_options)) {
                            foreach($quarter_options as $ii => $array) {
                        ?>
                        <option><?=$array['분기']?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                    분기
                    <p></p> 
                </div>
            </div>

            <div class="right">

                <div> <p> ▶  <strong>권역</strong>을 선택해주세요 </p>
                    <select id="region" name="region[]" class="selectpicker" data-width="70%" multiple title="권역 선택" data-actions-box="true" aria-label="Default select example">
                        <?php foreach ($region_options as $region_options) { ?>
                        <option><?php echo $region_options['권역']; ?> </option>
                        <?php } ?>
                    </select> <p></p>  
                </div>

                <div> <p> ▶  <strong>기관(병원)</strong>을 선택해주세요 </p>
                    <select id="hospital" name="hospital[]" class="selectpicker" data-width="70%" multiple title="복수 선택 가능" data-actions-box="true" aria-label="Default select example">
                        <option value="">권역을 먼저 선택해주세요</option>
                    </select> <p></p> 
                </div>
            </div>

            <div> <p> ▶ 검색하려는 <strong>지표</strong>를 선택해주세요 </p>
                <select id="index" name="index" class="selectpicker" data-width="50%" title="지표" aria-label="Default select example">
                    <?php foreach ($index_options as $index_options) {?>
                    <option><?php echo $index_options['Field']; ?> </option>
                    <?php } ?>

                </select> <p></p> 
            </div>
        </div>

        <div class="container w-50" align="center">
            <input type="submit" name="submit" id="btn_analysis" class="btn btn-primary" value="분석 실행"/>
            <input type="reset" name="reset" class="btn btn-warning" value="설정초기화"/>

    </form>


    </div>
    
    <div class="container w-100" align="left">
        <div id="result" class="basic" align="center" style="border-radius:5px;">
    </div>

    </div>

</body>
</html>
