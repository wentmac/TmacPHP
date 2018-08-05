<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Trade.class.php 448 2016-10-09 18:01:17Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
namespace base\dao\impl;

class Trade extends \dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'trade';
        $this->setPrimaryKeyField( 'trade_id' );
    }

}
