<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Trend.class.php 729 2016-12-05 07:42:21Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
namespace base\dao\impl;

class Trend extends \dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'trend';
        $this->setPrimaryKeyField( 'trend_id' );
    }

}
