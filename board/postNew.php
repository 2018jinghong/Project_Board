<?php


$array = array();
///数据库信息
$servername = "localhost";
$username = "user";
$password = "123Jhwl@zjut";
$dbname = "severData";

///获取post
$resp = file_get_contents('php://input');
$resp=json_decode($resp, true);
$command= $resp['command'];

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$conn->query("set names 'utf8'");

$data= $resp['data'];
$sourceId=(int)$data['sourceId'];

// 判断命令
if ($command=='msg') {
    $title=$data['title'];
    $text=$data['text'];
    $time=$data['time'];
    $sql = "INSERT INTO severData.msgData(title,texts,sourceId,timess) VALUES ('$title','$text','$sourceId',$time)";
    if ($conn->query($sql) === true) {
        echo "200 Ok";
    } else {
        echo $sql.$conn->error;
    }
    $conn->close();
} elseif ($command=='like') {
    $os=0;
    $sql="SELECT likes FROM severData.msgData WHERE id=$sourceId";
    $ol=$conn->query($sql);
    if ($ol->num_rows > 0) {
        // 输出数据
        while ($row = $ol->fetch_assoc()) {
            $os=(int)$row["likes"];
            $os=$os+1;
            break;
        }
    } else {
        echo $sql.$conn->error;
        exit;
    }
    $sql="UPDATE severData.msgData SET likes=$os WHERE id=$sourceId";
    if ($conn->query($sql) === true) {
        echo "200 Ok";
    } else {
        echo $sql.$conn->error;
    }
    $conn->close();
} elseif ($command=='dislike') {
    $os=0;
    $sql="SELECT dislikes FROM severData.msgData WHERE id=$sourceId";
    $ol=$conn->query($sql);
    if ($ol->num_rows > 0) {
        // 输出数据
        while ($row = $ol->fetch_assoc()) {
            $os=(int)$row["dislikes"];
            $os=$os+1;
            break;
        }
    } else {
        echo $sql.$conn->error;
        exit;
    }
    $sql="UPDATE severData.msgData SET dislikes=$os WHERE id=$sourceId";
    if ($conn->query($sql) === true) {
        echo "200 Ok";
    } else {
        echo $sql.$conn->error;
    }
    $conn->close();
} elseif ($command=='del') {
    if ($data['adminCode']=='yyzzs') {
        $sql = "DELETE FROM severData.msgData WHERE id=$sourceId";
        if ($conn->query($sql) === true) {
        } else {
            echo $sql.$conn->error;
        }
        $sql = "DELETE FROM severData.msgData WHERE sourceId=$sourceId";
        if ($conn->query($sql) === true) {
        } else {
            echo $sql.$conn->error;
        }
        echo "200 Ok";
        $conn->close();
     }else{
        echo '想破解，没门';
     }   
    } 
?>
