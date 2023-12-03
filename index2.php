<?php
  error_reporting( E_ALL );
  ini_set( "display_errors", 1 );
  
include("db.php");

// UTF-8 문자열 자르기
function php_fn_utf8_to_array($str){
	$re_arr = array(); $re_icount = 0;
	for($i=0,$m=strlen($str);$i<$m;$i++){
		$ch = sprintf('%08b',ord($str[$i]));
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
include "head.php";
?>
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
            $("select[name='region[]']").on("change", function() {

                if($(this).find("option:selected").length == 1) {
                    setOption($(this).attr("id"), "hospital");
                    $("#hospital").attr("disabled", false);
                } else {
                    $("#hospital").attr("selected", false);
                    $("#hospital").attr("disabled", true);
                    $("#hospital").selectpicker('destroy');
                    $("#hospital option").remove();
                    $("#hospital").selectpicker("refresh");
                }

            });

            $("#btn_analysis").on("click", function() {
                if($("#index").val() == "") {
                    alert('지표를 선택하세요');
                    $("#index").focus();
                    return false;
                }
                //console.log($("#searchfrm").serialize());

                var act_page = "analysisResult.php";
                if($("#index").find("option:selected").length > 1) {
                    act_page = "analysisMultiResult.php";
                }

                $.post(act_page,$("#searchfrm").serialize(), function(data) {
                    $("#basic").html(data);
                });
            });
        });

        function setOption(selectid, setid) {
        var param = $("#"+selectid).attr("name") + "=" + $("#"+selectid).val();

        $.post("ajaxData.php", param, function(data) {
            $("#"+setid).selectpicker('destroy');
            $("#"+setid+" option").remove();
            $("#"+setid).append(data);
            $("#"+setid).selectpicker("refresh");

        });
	  }
    </script>

<div class="content">
	    <div class="content-select">          
                <div> <p><span class="icon">▶</span><strong>연도</strong>를 선택해주세요 </p>
                    <select id="year" name="year" class="selectpicker"  title="연도 선택" aria-label="Default select example">
                        <?php foreach ($year_options as $year_options) { ?>
                        <option><?php echo $year_options['연도']; ?> </option>
                        <?php } ?>
                    </select> <p></p> 
                </div>
                <div> <p><span class="icon">▶</span><strong>분기</strong>를 선택해주세요 </p>
                    <select id="quarter" name="quarter" class="selectpicker" title="분기 선택" aria-label="Default select example">
                        <?php foreach ($quarter_options as $quarter_options) { ?>
                        <option><?php echo $quarter_options['분기']; ?> </option>
                        <?php } ?>
                    </select> <p></p> 
                </div>
            </div>
	    <span></span>
	     <div class="right content-select">
                <div> <p><span class="icon">▶</span> <strong>권역</strong>을 선택해주세요 </p>
                    <select id="region" name="region[]" class="selectpicker" multiple title="권역 선택" data-actions-box="true" aria-label="Default select example">
                        <?php foreach ($region_options as $region_options) { ?>
                        <option><?php echo $region_options['권역']; ?> </option>
                        <?php } ?>
                    </select> <p></p>  
                </div>
                <div> <p> <span class="icon">▶</span><strong>기관(병원)</strong>을 선택해주세요 </p>
                    <select id="hospital" name="hospital[]" class="selectpicker" multiple title="복수 선택 가능" data-actions-box="true" aria-label="Default select example">
                        <option value="">권역을 먼저 선택해주세요</option>
                    </select> <p></p> 
                </div>
            </div>
	    <span></span>
	     <div class="content-select-Indicators"> <p><span class="icon">▶</span> 검색하려는 <strong>지표</strong>를 선택해주세요 </p>
                <select id="index" name="index[]" class="selectpicker" data-width="50%" multiple title="지표" data-actions-box="true" aria-label="Default select example">
                    <?php foreach ($index_options as $index_options) {?>
                    <option><?php echo $index_options['Field']; ?> </option>
                    <?php } ?>

                </select> <p></p> 
            </div>
	    <div class="btn-content">
                <input type="submit" name="submit" id="btn_analysis" class="btn-submit" value="분석 실행"/>
                <input type="reset" name="reset" class="btn btn-warning" value="설정초기화"/>
        </div>
    </form>


    </div>

     <div class="content result" >
        <div id="basic" class="basic" align="center">
    </div>
    </div>
<?php
include "foot.php";
?>