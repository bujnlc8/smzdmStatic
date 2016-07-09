<?php
/**
 * Created by PhpStorm.
 * User: linghaihui
 * Date: 16/7/8
 * Time: 下午9:59
 */
require_once 'mysql.php';
require_once 'info.php';
require_once 'PHPExcel/PHPExcel.php';
require_once 'PHPExcel/PHPExcel/Writer/Excel2007.php';

class doPage
{
    private  $mainUrl ="http://www.smzdm.com/";
    private $secondUrl ="http://post.smzdm.com/";

    /**
     * 处理主页面的url
     */
    function doMain(){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->mainUrl);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $html = curl_exec($ch);
        $preg ="/class=\"img_list rFloat\">(\s+<div><a.*<\/a><\/div>)(\s+<div><a.*<\/a><\/div>)(\s+<div><a.*<\/a><\/div>)/";
        preg_match_all($preg,$html,$match);
        if(null!=$match&&sizeof($match)>=3){
            $str= $match[2][0];
        }
        $pregUrl ="/href=\"(.*\d{6})\/.?\" onclick=\"dataLayer\.push/";
        preg_match_all($pregUrl,$str,$match2);
        $url = $match2[1][0];
        $pregTitle ="/右2','文章标题':'(.*)'}/";
        preg_match_all($pregTitle,$html,$matchTitle);
        $title = $matchTitle[1][0];
        curl_setopt($ch,CURLOPT_URL,$url);
        $newHtml = curl_exec($ch);
        $pregCollect ="/<i class=\"icon-collect\">.*<em>(.*)<\/em>/";
        preg_match_all($pregCollect,$newHtml,$matchCollect);
        $collect = intval($matchCollect[1][0]);
        $pregComment ="/<em class=\"commentNum\">(.*)<\/em>/";
        preg_match_all($pregComment,$newHtml,$matchComment);
        $comment = intval($matchComment[1][0]);
        $pregPublishTime ="/20\d{2}-\d{2}-\d{2} \d{2}:\d{2}/";
        preg_match_all($pregPublishTime,$newHtml,$matchPublish);
        $publishTime = $matchPublish[0][0];
        date_default_timezone_set("PRC");
        $showTime = date('Y-m-d');
        $i = new info($url,$title,$publishTime,$showTime,$collect,$comment,'0');
        mysqlConnect::getInstance()->insertValue($i);
        curl_close($ch);
    }

    /**
     * 处理secondUrl
     */
    function doSecondUrl(){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->secondUrl);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        $html = curl_exec($ch);
        $pregUrl = "/class=\"slider-box\" href=\"(.*)\/.*\" target/";
        preg_match_all($pregUrl,$html,$match);
        $urlArr = $match[1];
        for($index=0;$index<sizeof($urlArr);$index++){
            $url = $urlArr[$index];
            curl_setopt($ch,CURLOPT_URL,$url);
            $newHtml = curl_exec($ch);
            $pregCollect ="/<i class=\"icon-collect\">.*<em>(.*)<\/em>/";
            preg_match_all($pregCollect,$newHtml,$matchCollect);
            $collect = intval($matchCollect[1][0]);
            $pregComment ="/<em class=\"commentNum\">(.*)<\/em>/";
            preg_match_all($pregComment,$newHtml,$matchComment);
            $comment = intval($matchComment[1][0]);
            $pregPublishTime ="/20\d{2}-\d{2}-\d{2} \d{2}:\d{2}/";
            preg_match_all($pregPublishTime,$newHtml,$matchPublish);
            $publishTime = $matchPublish[0][0];
            date_default_timezone_set("PRC");
            $showTime = date('Y-m-d');
            $pregTitle = "/<h1 itemprop=\"headline\" class=\"item-name\">(.*)<\/h1>/";
            preg_match_all($pregTitle,$newHtml,$matchTitle);
            $title =$matchTitle[1][0];
            $i = new info($url,$title,$publishTime,$showTime,$collect,$comment,'1');
            mysqlConnect::getInstance()->insertValue($i);
        }
        curl_close($ch);
    }

    function  toExcel(){
        $objPHPExcel = new PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        $objProps->setCreator("wcstctc");
        //先设置sheet
        $objPHPExcel->setactivesheetindex(0);
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth(35);
        $objActSheet->getColumnDimension('B')->setWidth(35);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(15);
        $objActSheet->getColumnDimension('E')->setWidth(10);
        $objActSheet->getColumnDimension('F')->setWidth(10);
        //设置标题
        $objActSheet->setCellValue('A1', '链接');
        $objActSheet->setCellValue('B1', '标题');
        $objActSheet->setCellValue('C1', '发布时间');
        $objActSheet->setCellValue('D1', '展示时间');
        $objActSheet->setCellValue('E1', '收藏数');
        $objActSheet->setCellValue('F1', '评论数');
        //设置数据
        $result = mysqlConnect::getInstance()->getData('0');
        $index =1;
        while ($row = mysqli_fetch_row($result)){
            $index++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$index)->getAlignment()->setWrapText(true);
            $objActSheet->setCellValue('A'.$index, $row[0]);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$index)->getAlignment()->setWrapText(true);
            $objActSheet->setCellValue('B'.$index, $row[1]);
            $objActSheet->setCellValue('C'.$index, $row[2]);
            $objActSheet->setCellValue('D'.$index, $row[3]);
            $objActSheet->setCellValue('E'.$index, $row[4]);
            $objActSheet->setCellValue('F'.$index, $row[5]);
        }
        $objPHPExcel->createSheet();
        $objPHPExcel->setActiveSheetIndex(1);
        $objActSheet = $objPHPExcel->getActiveSheet();
        $objActSheet->getColumnDimension('A')->setWidth(35);
        $objActSheet->getColumnDimension('B')->setWidth(35);
        $objActSheet->getColumnDimension('C')->setWidth(20);
        $objActSheet->getColumnDimension('D')->setWidth(15);
        $objActSheet->getColumnDimension('E')->setWidth(10);
        $objActSheet->getColumnDimension('F')->setWidth(10);
        //设置标题
        $objActSheet->setCellValue('A1', '链接');
        $objActSheet->setCellValue('B1', '标题');
        $objActSheet->setCellValue('C1', '发布时间');
        $objActSheet->setCellValue('D1', '展示时间');
        $objActSheet->setCellValue('E1', '收藏数');
        $objActSheet->setCellValue('F1', '评论数');
        //设置数据
        $result = mysqlConnect::getInstance()->getData('1');
        $index =1;
        while ($row = mysqli_fetch_row($result)){
            $index++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$index)->getAlignment()->setWrapText(true);
            $objActSheet->setCellValue('A'.$index, $row[0]);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$index)->getAlignment()->setWrapText(true);
            $objActSheet->setCellValue('B'.$index, $row[1]);
            $objActSheet->setCellValue('C'.$index, $row[2]);
            $objActSheet->setCellValue('D'.$index, $row[3]);
            $objActSheet->setCellValue('E'.$index, $row[4]);
            $objActSheet->setCellValue('F'.$index, $row[5]);
        }
        //导出数据
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save("file/SMZDM_".date('Y-m-d').".xlsx");
    }

}