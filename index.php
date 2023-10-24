<?php
include("db.php");

// UTF-8 문자열 자르기
function php_fn_utf8_to_array($str){
	$re_arr = array(); $re_icount = 0;
	for($i=0,$m=strlen($str);$i<$m;$i++){
		$ch = sprintf('%08b',ord($str{$i}));
		if(strpos($ch,'11110')===0){$re_arr[$re_icount++]=substr($str,$i,4);$i+=3;}
		else if(strpos($ch,'1110')===0){$re_arr[$re_icount++]=substr($str,$i,3);$i+=2;}
		else if(strpos($ch,'110')===0){$re_arr[$re_icount++]=substr($str,$i,2); $i+=1;}
		else if(strpos($ch,'0')===0){$re_arr[$re_icount++]=substr($str,$i,1);}
	}
	return $re_arr;
}

//utf8문자열을 잘라낸다.
function php_fn_utf8_substr($str,$start,$length=NULL){
	return implode('',array_slice(php_fn_utf8_to_array($str),$start,$length));
}

//utf8문자열의 길이를 구한다.
function php_fn_utf8_strlen($str){
	return count(php_fn_utf8_to_array($str));
}

// 지표명 체크
function check_index_title($title = "") {

	if($title == "") return false;	// 2023-07-28 : 세로보기가 없을 경우 _1 로 들어가기 때문에 Q_번호로 지정될 수 있도록 조건 처리 ----- kky

	$title_size = php_fn_utf8_strlen($title);

	for($ii = 0; $ii < $title_size; $ii++) {
		// 알파벳, 숫자, 한글, 특수문자(_) 만 입력 가능
		//$pattern = "/[A-Za-z0-9\xA1-\xFE_]/";
		$pattern = "/[0-9a-zA-Z\xA1-\xFE\_]/";

		// 첫글자는 한글만 입력 가능
		if($ii == 0) {
			$pattern = "/[\xA1-\xFE]+/";
		}

		$str = php_fn_utf8_substr($title,$ii,1);

		if(!preg_match($pattern, $str)) {
			//echo $str."<br>";
			return false;
		}
	}

	return true;

}

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
            if(in_array($index_array['Field'], array("연도", "분기", "차시", "권역", "기관"))) {   // 연도, 분기, 차시, 권역, 기관 배열에서 삭제함
                unset($index_options[$ii]);
            }
        }
    }
    echo "<pre>";
    print_r($index_options);
    echo "</pre>";
    // check_index_title
    $index_options= array_slice($index_options,6);
    $index_options= array_column($index_options, '객담도말시행률');
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
                $('#basic').html(data);
            });

            $('.selectpicker').selectpicker();

            $("select[name='year']").on("change", function() {
                setOption($(this).attr("id"), "quarter");
            });
            $("select[name='region']").on("change", function() {
                setOption($(this).attr("id"), "hospital");
            });

            $("#btn_analysis").on("click", function() {
                if($("#index").val() == "") {
                    alert('지표를 선택하세요');
                    $("#index").focus();
                    return false;
                }
            console.log($("#searchfrm").serialize());

                $.post("analysis.php",$("#searchfrm").serialize(), function(data) {
                    $("#basic").html(data);
                });
            });
        });

        function setOption(selectid, setid) {
        var param = $("#"+selectid).attr("name") + "=" + $("#"+selectid).val();

        $.post("ajaxData.php", param, function(data) {
            console.log(data);
            $("#"+setid).selectpicker('destroy');
            $("#"+setid+" option").remove();
            $("#"+setid).append(data);
            $("#"+setid).selectpicker("refresh");

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
            
                <div> <p> ▶ <strong>연도</strong>를 선택해주세요 </p>
                    <select id="year" name="year" class="selectpicker" data-width="70%" title="연도 선택" aria-label="Default select example">
                        <?php foreach ($year_options as $year_options) { ?>
                        <option><?php echo $year_options['연도']; ?> </option>
                        <?php } ?>
                    </select> <p></p> 
                </div>

                <div> <p> ▶  <strong>분기</strong>를 선택해주세요 </p>
                    <select id="quarter" name="quarter" class="selectpicker" data-width="70%" title="분기 선택" aria-label="Default select example">
                        <?php foreach ($quarter_options as $quarter_options) { ?>
                        <option><?php echo $quarter_options['분기']; ?> </option>
                        <?php } ?>
                    </select> <p></p> 
                </div>
            </div>

            <div class="right">

                <div> <p> ▶  <strong>권역</strong>을 선택해주세요 </p>
                    <select id="region" name="region" class="selectpicker" data-width="70%" title="권역 선택" aria-label="Default select example">
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
        <div id="basic" class="basic" align="center" style="border-radius:5px;">
    </div>

    </div>

</body>
</html>
