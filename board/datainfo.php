<?php 
header("Content-type: text/html;charset=utf-8");
class msg {
public    $title = 'this is public';
public    $text='text';
public    $id = 1;
public    $sourceId=0;
public    $time = 32457689;
public    $userIp='text';
public    $like=0;
public    $dislike=0;
}

class response{
    public static function show($code,$message,$type='json'){
        if($_REQUEST['page']==0){
            //为0 返回基本信息

             //为0 返回基本信息
             $array = array();
             $servername = "localhost";
             $username = "user";
             $password = "123Jhwl@zjut";
             $dbname = "severData";
             // 创建连接
             $conn = new mysqli($servername, $username, $password);
              
             // Check connection
             if ($conn->connect_error) {
                 die("连接失败: " . $conn->connect_error);
             } 
             $os=0;
             $sql="SELECT COUNT(*) FROM severData.msgData WHERE sourceId=0";
             $ol=$conn->query($sql);
                 if ($ol->num_rows > 0) {
                     // 输出数据
                     while($row = $ol->fetch_assoc()) {
                         $os=(int)$row["COUNT(*)"];
                         break;
                     }
                 }
                
              else {
                 echo $sql.$conn->error;
                 exit;
             }
            
            $conn->close();
            $result=array(
                "allPages"=>(int)($os-1/10)+1,
            );
            echo json_encode($result);
            exit;
        }
      
        if($type=='json'){
            self::json($code,$message,$data);
        }
    }

    public static function json($code,$message,$data=array()){
        $page=$_REQUEST['page'];
        if(!is_numeric($page)){
            return '';
        }
        $left=(int)($page-1)*10;
        $right=$left+10;
        $array = array();
        $servername = "localhost";
        $username = "user";
        $password = "123Jhwl@zjut";
        $dbname = "severData";
     
        // 创建连接
        $conn = new mysqli($servername, $username, $password);
         
        // Check connection
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        } 
       // mysql_query("set character set 'utf8'");//读库 
        $conn->query("set names 'utf8'");//写库
        $sql = "SELECT title, texts ,id,sourceId,likes,dislikes,timess FROM $dbname.msgData ORDER BY id ASC limit $left,$right ";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // 输出数据
            while($row = $result->fetch_assoc()) {
                $ms=new msg;
                $ms->id=(int)$row["id"];
                $ms->title=$row["title"];
                $ms->text=$row["texts"];
                $ms->sourceId=(int)$row["sourceId"];
                $ms->like=(int)$row["likes"];
                $ms->dislike=(int)$row["dislikes"];
                $ms->time=(int)$row["timess"];
                array_push($array, $ms);       
            }
        } else {
            echo "0 结果";
            echo $result;
        }
        $conn->close();
                
        $foo_json = json_encode($array);
        echo $foo_json;
    }   
}
response::show(200,'success','json');
?>