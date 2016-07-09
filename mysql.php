<?php

/**
 * Created by PhpStorm.
 * User: linghaihui
 * Date: 16/7/8
 * Time: 下午10:02
 */
require_once 'info.php';
require_once 'utils.php';
class mysqlConnect
{
     private static  $url="localhost:3306";
     private static  $user="haihuiling";
     private static  $password="198915oo";
     private static  $database="smzdm";
     private $con;
     static  $mysqlConnect;//单实例
     private function __construct(){
          $this->con = mysqli_connect(self::$url,self::$user,self::$password,self::$database);
         if(mysqli_connect_errno($this->con)){
             echo "连接 MySQL 失败: " . mysqli_connect_error();
         }
    }

    static  function getInstance()
    {
        if (!(self::$mysqlConnect instanceof self)) {
            self::$mysqlConnect = new mysqlConnect();
        }
        return self::$mysqlConnect;
    }

    public  function  getCon(){
        return $this->con;
    }
    public function insertValue($t){
        if(!($t instanceof  info)){
            return false;
        }
        //先判断是否已存在当日的数据,如果存在,删掉之后重新插入
        $sqlD ="delete from info where showTime='".date("Y-m-d")."' and url='".$t->getUrl()."'";
        mysqli_query(self::getInstance()->getCon(),$sqlD);
        $sql ="insert into info(url,title,publishTime,showTime,starNum,commentNum,kind)values('".$t->getUrl()."','".$t->getTitle()."','".$t->getPublishTime()."','".$t->getShowTime()."',".$t->getStarNum().",".$t->getCommentNum().",'".$t->getKind()."')";
        mysqli_escape_string(self::getInstance()->getCon(),$sql);
        mysqli_query(self::getInstance()->getCon(),$sql);
        if(mysqli_affected_rows(self::getInstance()->getCon())>0){
            return true;
        }
    }

    public  function getData($kind){
        $leftDate = getLeft();
        $sql ="select url,title,publishTime,showTime,starNum,commentNum from info where showTime >='".$leftDate."' and kind='".$kind."' order by showTime asc";
        mysqli_escape_string(self::getInstance()->getCon(),$sql);
        $result = mysqli_query(self::getInstance()->getCon(),$sql);
        return $result;
    }
 }