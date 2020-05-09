## api接口调用/页面访问计数器
### 简介
为了统计php开放给外界的接口调用情况，写了本脚本  
为了方便数据统计和管理方便，而且php自带支持sqllite，所以便使用sqllite进行数据储存  
数据库记录了接口/页面调用ip，来源地址，调用时间等信息  
可通过url的参数注入SQL语句的方式执行SQL语句，从而完成数据的查询/操作  

- 文件结构
  - gendb.php    生成数据库、表的脚本
  - runpv.php    用于异步调用pv.php的脚本
  - pv.php       执行统计的脚本(不可直接调用)
  - query.php    查询统计数据的脚本

### 使用方法
#### 1 拉取core代码
```
https://github.com/cxying/api-call-counter.git
```
#### 2 使用
2.1 复制core的文件到自己php服务器上对应文件夹内  
2.2 浏览器访问gendb.php，将自动生成数据库文件，并保存在部署目录下db/pv.db下  
2.3 参考example.php将下面的代码加到需要统计的api或页面中  
```php
// 定义实际运行统计程序的路径,即pv.php的绝对路径
$path = '/www/open/acgimg/pv.php';  
include('runpv.php');
```
（ps：runpv.php用了popen，所以要注意修改此处的$path，用popen是为了不阻塞正常页面的访问，曾经尝试过直接include('pv.php')文件，并发的情况下统计数据来不及写入数据库而阻塞，导致被统计页面无法正常响应，所以用多加了runpv.php去间接异步调用pv.php了。当然这种情况下统计数据肯定会有一定不准确，但总比因为一个统计导致页面挂了好吧~）

#### 3 查询数据
可通过query.php注入SQL语句进行查询
任何人都能够注入SQL执行SELECT语句，但无法修改数据  
可以自定义一个key来防止他人修改数据库，用这个key就可以执行一些敏感的语句（增删改）
```
example: 
         yourdomain/query.php?query=select * from table                               --> allow
         yourdomain/query.php?query=delete from table where id=1                      --> forbidden
         yourdomain/query.php?query=delete from table where id=1&key=yoursecret       --> allow
         yourdomain/query.php                                                         --> error
```
示例：
![image](https://user-images.githubusercontent.com/46587259/81475620-f5a44580-923f-11ea-992c-1ab3ab52490a.png)
### 其他说明
其实也就个很简单的脚本，还是比较容易看得懂的~ 代码里也有相应备注~
