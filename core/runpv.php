<?php
// 本脚本用于异步调用真正执行统计的pv.php并传入参数,防止阻塞
$ip = getIP();
$ref = getRef();
// include/require的方式会阻塞,导致并发时有些请求来不及写入pv.php中的数据库,无法正常重定向,所以用这种方式实现非阻塞
// 但是此时相关信息已经丢失了,需要手动获取ip和调用地址传入
fclose(popen('php '.$path.' -a '.$ip.' -b '.$ref.' &', 'r'));
// 获取客户端ip地址
function getIP()
{
	global $ip;
	if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
	else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
	else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
	else 
		$ip = "Unknow";
    return $ip;
}

function getRef()
{
    if(isset($_SERVER['HTTP_REFERER'])){
        return $_SERVER['HTTP_REFERER'];
    } else {
        return "url";
    }
}
?>