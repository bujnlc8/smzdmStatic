<?php
/**
 * Created by PhpStorm.
 * User: linghaihui
 * Date: 16/7/9
 * Time: 上午2:33
 */
require_once 'PHPMailer/PHPMailerAutoload.php';
require_once 'doPage.php';

function getLeft(){
    date_default_timezone_set("PRC");
    $week = date("w");
    $date = date('Y-m-d');
    switch ($week){
        case 0:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("1"."days"));
            break;
        case 1:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("2"."days"));
            break;
        case 2:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("3"."days"));
            break;
        case 3:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("4"."days"));
            break;
        case 4:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("5"."days"));
            break;
        case 5:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("6"."days"));
            break;
        case 6:
            $leftDate = date_sub(date_create($date),date_interval_create_from_date_string("0"."days"));
            break;

    }
    return $leftDate->format('Y-m-d');
}


function sendEmail(){
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'smtp.qq.com';
        $mail->SMTPAuth = true;
        $mail->Username = "75124771@qq.com";
        $mail->Password = "75124771@qq.cnn";
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = "utf-8";
        $mail->setFrom("75124771@qq.com", "笨笨");
        $mail->addAddress("375178773@qq.com", '蛋蛋');
        $mail->addAddress("75124771@qq.com","haihui");
        $mail->addReplyTo('75124771@qq.com', '笨笨');
        //判断文件是否存在,不存在说明没有去爬数据,并且没有生成excel文件
        if(!file_exists("file/SMZDM_".date('Y-m-d').".xlsx")){
            $do = new doPage();
            $do->doMain();
            $do->doSecondUrl();
            $do->toExcel();
        }
        $mail->addAttachment("file/SMZDM_".date('Y-m-d').".xlsx","SMZDM_".date('Y-m-d').".xlsx");
        $mail->isHTML(true);
        $mail->Subject = date('Y-m-d')."汇总";
        $mail->Body    = getJoke()."<br/>"."亲爱的,查收附件!";
        if(!$mail->send()) {
            return 'n';
        } else {
            return 'y';
        }
    }

function getJoke()
{
    $ch = curl_init();
    $url = 'http://apis.baidu.com/showapi_open_bus/showapi_joke/joke_text?page=1';
    $header = array(
        'apikey: 5c339000efff88167085cbeb67ae9f24',
    );
    // 添加apikey到header
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 执行HTTP请求
    curl_setopt($ch, CURLOPT_URL, $url);
    $res = curl_exec($ch);
    $joke = json_decode($res,true)['showapi_res_body']['contentlist'][rand(0,15)];
    $plain = "<p style='text-align: center;color: #0a2b1d;'>".$joke['title']."</p>".$joke['text'];
    return $plain;

}