<?php
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
      echo "Opened database successfully\n";
   }

   // 1.创建表格,创建成功后注释掉
   // 主键要用INTEGER才能自增,用INT不行
   $sql =<<<EOF
   CREATE TABLE DETAIL
   (ID INTEGER PRIMARY KEY     NOT NULL,
   IP           TEXT    NOT NULL,
   TIME         INT     NOT NULL,
   REFERER      TEXT    NOT NULL);
EOF;

  $ret = $db->exec($sql);
  if(!$ret){
     echo $db->lastErrorMsg();
  } else {
     echo "Table created successfully\n";
  }
$db->close();


// $now = time() - 28800;
// $sql =<<<EOF
//       INSERT INTO DETAIL (ID,IP,TIME)
//       VALUES (null, '192.168.1.1', $now);
//       INSERT INTO DETAIL (ID,IP,TIME)
//       VALUES (null, '192.168.1.2', $now);
// EOF;

//    $ret = $db->exec($sql);
//    if(!$ret){
//       echo $db->lastErrorMsg();
//    } else {
//       echo "Records created successfully\n";
//    }
//    $db->close();


// $sql =<<<EOF
//       SELECT * from DETAIL;
// EOF;

//    $ret = $db->query($sql);
//    while($row = $ret->fetchArray(SQLITE3_ASSOC) ){
//       echo "ID = ". $row['ID'] . "\n";
//       echo "IP = ". $row['IP'] ."\n";
//       echo "TIME = ". $row['TIME'] ."\n";
//    }
//    echo "Operation done successfully\n";
//    $db->close();

?>