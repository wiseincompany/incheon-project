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

//AES 128 암호화
function encryptAES128($string){
    $encrypted = openssl_encrypt($string,"aes-128-cbc", KEY_PASSWD, true, KEY_IV);
	$base64Encoded = base64_encode($encrypted);
	return $base64Encoded;
}

//AES 128 복호화
function decryptAES128($string){
	$base64Decoded = base64_decode($string);
    $decrypted = openssl_decrypt($base64Decoded, "aes-128-cbc", KEY_PASSWD, true, KEY_IV);
	return $decrypted;
}

?>