<?php
error_reporting( E_ALL );
ini_set( "display_errors", 1 );

@header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');

@ini_set("session.use_trans_sid", 0);	// PHPSESSID를 자동으로 넘기지 않음	=> session.auto_start = 0 으로 설정 / PHP 5 이상 버전부터 session.use_trans_sid 설정을 ini_set으로 바꿀 수 없음
@ini_set("url_rewriter.tags","");			// 링크에 PHPSESSID가 따라다니는것을 무력화함

if($_SERVER['SESSION_CACHE_LIMITER']) session_cache_limiter($_SERVER['SESSION_CACHE_LIMITER']);
else session_cache_limiter('private, must-revalidate');

@ini_set("session.cache_expire", 1440);			// 세션 캐쉬 보관시간 (분)
@ini_set("session.gc_maxlifetime", 86400);	// session data의 gabage collection 존재 기간을 지정 (초)

@session_start();

@header("Content-Type: text/html; charset=UTF-8");

include "db.php"; // DB Connect
?>