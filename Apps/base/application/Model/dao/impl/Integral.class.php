<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Integral.class.php 372 2016-06-27 18:23:40Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
class dao_impl_Integral_base extends dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'integral';
        $this->setPrimaryKeyField( 'integral_id' );
    }

}
