<?php
define('KEY_PASSWD', "WISEINCOMP".date('his'));
define('KEY_IV', "WISEINCOMP".date('his'));

include "common.php";

echo "test => ".encryptAES128("test")."<br>";
echo "name => ".encryptAES128("테스트")."<br>";

echo "<pre>";
print_r($_REQUEST);
echo "</pre>";

echo "dec id => ".decryptAES128($_REQUEST['uid'])."<br>";

$uid = isset($_REQUEST['uid']) ? decryptAES128($_REQUEST['uid']) : "";
$uname = isset($_REQUEST['uname']) ? decryptAES128($_REQUEST['uname']) : "";

if($uid == "" || $uname == "") {
    error("접속 정보가 누락되었습니다.");
    //exit;
}

$enc_id = encryptAES128($uid);
if($_REQUEST['uid'] != $enc_id) {
    error("접속 정보가 일치하지 않습니다.");    
    //exit;
}

echo "id => ".$_REQUEST['uid']."<br>";
echo "name => ".$_REQUEST['uname']."<br>";
echo "enc => ".$enc_id."<br>";

// 데이터 조회

// 있으면 update

// 없으면 insert

// login
$_SESSION['login_info']['id'] = $uid;
$_SESSION['login_info']['name'] = $uname;

// 페이지 이동
//header("Location : /index.php");
?>
