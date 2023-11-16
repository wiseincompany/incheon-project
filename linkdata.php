<?php
include "common.php";
// 데이터 조회

// 있으면 update

// 없으면 insert

// login
$_SESSION['login_info']['id'] = $uid;
$_SESSION['login_info']['name'] = $uname;

// 페이지 이동
header("Location : /index.php");
?>