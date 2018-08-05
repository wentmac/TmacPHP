<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $ 
 */

/**
 * Description of Comment.class.php
 *
 * @author Tracy McGrady
 */
namespace base\entity;

class Comment
{
    public $comment_id;
    public $article_id;
    public $comment_author;
    public $comment_author_email;
    public $comment_author_url;
    public $comment_author_ip;
    public $comment_time;
    public $comment_content;
    public $comment_approved;
    public $comment_agent;
    public $comment_parent;
    public $user_id;
    public $comment_delete;
}