<?php
// example: 
//          yourdomain/query.php?query=select * from table                               --> allow
//          yourdomain/query.php?query=delete from table where id=1                      --> forbidden
//          yourdomain/query.php?query=delete from table where id=1&key=yoursecret       --> allow
//          yourdomain/query.php                                                         --> error
$input=$_GET['query'];
$key=$_GET['key'];
// yoursecret
$secret = 'yoursecret';

// forbidden keywords,need secret
$forbidden_arr = array("delete","update","insert","create","drop","alter","begin","rollback","commit","vacuum","attach","detach");
if (!$input) {
   echo "error!";
   exit;
}
$lower_input = strtolower($input);
for($x=0;$x<count($forbidden_arr);$x++)
{
    if (strpos($lower_input, $forbidden_arr[$x]) !== false && $key !== $secret) {
       echo "<div style='text-align:center;margin:auto;color:red'>forbidden!</div>";
       exit;
    }
}
class MyDB extends SQLite3
{
   function __construct()
   {
      $this->open('db/pv.db');
   }
}
$db = new MyDB();
$db->enableExceptions(true); //enableDBExceptions,then we can catch db exception
if(!$db){
   echo $db->lastErrorMsg();
} else {
   // echo "Opened database successfully\n";
}

$sql =<<<EOF
      $input ;
EOF;

try {
   $ret = $db->query($sql);
   $numColumns = $ret->numColumns();
} catch(Exception $e){
   echo 'SQL Execute Error,Message: '.$e->getMessage();
   exit;
}
   $htmlTableColumn = "";
   $has_time = false;
   for ($i=0; $i<$numColumns; $i++) {
      $columnName = $ret->columnName($i);
      $htmlTableColumn = $htmlTableColumn."<th>".$columnName."</th>";
      if ($columnName == 'TIME') {
         // 有时间列的话做个标记
         $has_time = true;
      }
   }
   // 然后把格式化后的时间的表头放到最后一列
   if ($has_time) {
      $formatted_time_col = "FORMATTED_TIME"; // 时间格式化后的列名
      $htmlTableColumn = $htmlTableColumn."<th>".$formatted_time_col."</th>";
   }
   date_default_timezone_set('Asia/Shanghai');
   
   // while($row = $ret->fetchArray(SQLITE3_BOTH)){
   //    if($row['ID'] === null){ $data = "<td>".$row[0]."</td>"; $addCol = "" ; break; }
   //    $t = date('Y-m-d H:i:s', $row['TIME']); // 时间戳转化为时间
   //    $data = $data."<tr> <td>".$row['ID']."</td> <td>".$row['IP']."</td> <td>".$row['TIME']."</td> <td>".$row['REFERER']."</td> <td>".$t."</td> </tr>";
   // }

   $number_of_rows = 0;
   $data = "";
   while($row = $ret->fetchArray(SQLITE3_BOTH)){
      // 构造表格的每行
      $rowdata = " <tr> ";
      for ($j=0; $j<$numColumns; $j++) {
         $rowdata = $rowdata." <td> ".$row[$j]." </td> ";
      }
      // 若有时间,则格式化后放最后一列
      if($row['TIME']){
         $rowdata = $rowdata." <td> ".date('Y-m-d H:i:s', $row['TIME'])." </td> ";
      }
      $rowdata = $rowdata." </tr> ";
      // 将每行的组装进总的(注意不可直接在$data前进行拼接<tr>,会变成<tr><tr><tr><td>xxx</td>...)
      $data = $data.$rowdata;
      $number_of_rows += 1;
   }

   // select t.*,datetime(t.time, 'unixepoch','8 hour') FORMATTED_TIME from detail t where t.time between strftime('%s','2020-05-09 00:04:00','-8 hour') and strftime('%s','2020-05-09 00:05:00','-8 hour') order by t.id desc limit 20

   //$begin = " <div><table style='text-align:center;margin:auto;'> <thead> <tr> ".$htmlTableColumn." ".$addCol." </tr> </thead> <tbody>";
   $begin = " <div><table style='text-align:center;margin:auto;'> <thead> <tr> ".$htmlTableColumn." </tr> </thead> <tbody>";
   $end = "</tbody></table></div>";
   echo $begin;
   echo $data;
   echo $end;
   echo "<div style='text-align:center;margin:auto;'>Operation done successfully ,count:".$number_of_rows."</div>";
   
   $db->close();
?>