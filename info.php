<?php

/**
 * Created by PhpStorm.
 * User: linghaihui
 * Date: 16/7/8
 * Time: 下午9:47
 * 导出信息类
 */
class info
{
    private $url;
    private $title;
    private $publishTime;
    private $showTime;
    private $starNum;
    private $commentNum;
    private $kind;//0 main 1 second(5)


    /**
     * info constructor.
     * @param $url
     * @param $title
     * @param $publishTime
     * @param $showTime
     * @param $starNum
     * @param $commentNum
     * @param $kind
     */
    public function __construct($url, $title, $publishTime, $showTime, $starNum, $commentNum,$kind)
    {
        $this->url = $url;
        $this->title = $title;
        $this->publishTime = $publishTime;
        $this->showTime = $showTime;
        $this->starNum = $starNum;
        $this->commentNum = $commentNum;
        $this->kind =$kind;

    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getPublishTime()
    {
        return $this->publishTime;
    }

    /**
     * @param mixed $publishTime
     */
    public function setPublishTime($publishTime)
    {
        $this->publishTime = $publishTime;
    }

    /**
     * @return mixed
     */
    public function getShowTime()
    {
        return $this->showTime;
    }

    /**
     * @param mixed $showTime
     */
    public function setShowTime($showTime)
    {
        $this->showTime = $showTime;
    }

    /**
     * @return mixed
     */
    public function getStarNum()
    {
        return $this->starNum;
    }

    /**
     * @param mixed $starNum
     */
    public function setStarNum($starNum)
    {
        $this->starNum = $starNum;
    }

    /**
     * @return mixed
     */
    public function getCommentNum()
    {
        return $this->commentNum;
    }

    /**
     * @param mixed $commentNum
     */
    public function setCommentNum($commentNum)
    {
        $this->commentNum = $commentNum;
    }

    /**
     * @return mixed
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param mixed $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }


}