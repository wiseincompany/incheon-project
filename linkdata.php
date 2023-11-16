<?php
include "common.php";

define('KEY_PASSWD', "WISEINCOMP".date('his'));
define('KEY_IV', "WISEINCOMP".date('his'));

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

// 데이터 조회

// 있으면 update

// 없으면 insert

// login
$_SESSION['login_info']['id'] = $uid;
$_SESSION['login_info']['name'] = $uname;

// 페이지 이동
//header("Location : /index.php");
?>
