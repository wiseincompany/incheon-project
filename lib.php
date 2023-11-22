<?php
// 에러 출력
function error($msg, $go_url=""){

	if($go_url == "") {
		//echo "<script>alert(\"$msg\");history.go(-1);</script>";
        echo "<script>alert(\"$msg\");</script>";
		exit;
	} else {
		echo "<script>alert(\"$msg\");document.location=\"$go_url\";</script>";
		exit;
	}

}

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

//AES 128 암호화
function encryptAES128($string){
    $encrypted = openssl_encrypt($string,"aes-128-cbc", KEY_PASSWD, OPENSSL_RAW_DATA, KEY_IV);
	$base64Encoded = base64_encode($encrypted);
	return $base64Encoded;
}

//AES 128 복호화
function decryptAES128($string){
	echo "str => " . $string."<br>";
	$base64Decoded = base64_decode($string);
	echo "base => " . $base64Decoded."<br>";
    $decrypted = openssl_decrypt($base64Decoded, "aes-128-cbc", KEY_PASSWD, OPENSSL_RAW_DATA, KEY_IV);
	echo "dec => " . $decrypted."<br>";
	return $decrypted;
}

?>