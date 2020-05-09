<?php
// ---------- pv统计 ----------
// require 会生成致命错误（E_COMPILE_ERROR）并停止脚本
// include 只生成警告（E_WARNING），并且脚本会继续
// 定义实际运行统计程序的路径,绝对路径
$path = '/www/xxx/xxx/pv.php';  
include('runpv.php');
// ---------- pv统计 ----------

// ---------- dosomething ---------- 

echo "hello!";

// ---------- dosomething ---------- 
?>
