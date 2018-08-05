<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: YoukuNotice.class.php 898 2017-02-16 03:44:41Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
namespace base\dao\impl;

class YoukuNotice extends \dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = 't_notice';
        $this->setPrimaryKeyField( 'notice_id' );
    }

}
