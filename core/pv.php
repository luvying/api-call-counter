<?php
// 从acgurl.php传过来的参数
$params = getopt('a:b:');
$ip = $params['a'];
$ref = $params['b'];

// 1.连接数据库
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('db/pv.db');
    }
}
$db = new MyDB();
if(!$db){
    echo $db->lastErrorMsg();
} else {
    // 2.连接成功,获取当前时间、ip并存入数据库
    date_default_timezone_set("Asia/Shanghai");
    $now = time();
    //$ip = getIP();
    //$ref = getRef();
    // 注意严格按照EOF的格式,否则编译不通过
    $sql =<<<EOF
    INSERT INTO DETAIL (ID ,IP ,TIME, REFERER)
    VALUES (null, "$ip", $now, "$ref");
EOF;

    $ret = $db->exec($sql);
    if(!$ret){
        echo $db->lastErrorMsg();
    } else {
        //echo "Records created successfully\n";
    }
    $db->close(); 
    //echo "Opened database successfully\n";
}

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