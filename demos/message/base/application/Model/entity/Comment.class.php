<?php

/*
 * Tmac PHP MVC framework
 * $Author: ZhangWentao<zwt007@gmail.com> $
 * $Id: Comment.class.php 0 2016-04-19 16:08:04Z zhangwentao $
 */

/**
 * Description of Comment.class.php
 *
 * @author Tracy McGrady
 */
class entity_Comment_base
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