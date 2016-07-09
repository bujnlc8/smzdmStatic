<?php
/**
 * Created by PhpStorm.
 * User: linghaihui
 * Date: 16/7/9
 * Time: 上午3:24
 */
require_once 'doPage.php';
$do = new doPage();
$do->doMain();
$do->doSecondUrl();
$do->toExcel();