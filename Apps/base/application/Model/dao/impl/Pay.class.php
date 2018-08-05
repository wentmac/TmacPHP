<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Pay.class.php 442 2016-10-07 14:45:05Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
namespace base\dao\impl;

class Pay extends \dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'pay';
        $this->setPrimaryKeyField( 'pay_id' );
    }

}
