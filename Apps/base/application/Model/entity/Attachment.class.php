<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of Attachment.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class Attachment
{
    public $attachment_id;
    public $article_id;
    public $time;
    public $filename;
    public $filetype;
    public $filesize;
    public $fileext;
    public $downloads;
    public $filepath;
    public $thumb_filepath;
    public $thumb_width;
    public $thumb_height;
    public $isimage;
}