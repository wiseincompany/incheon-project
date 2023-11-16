<?php
include "common.php";

$uid = isset($_REQUEST['uid']) ? $_REQUEST['uid'] : "";
$uname = isset($_REQUEST['uname']) ? $_REQUEST['uname'] : "";

if($uid == "" || $uname == "") {
    echo "<script>alert('접속 정보가 누락되었습니다.');/*history.go(-1);*/</script>";
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