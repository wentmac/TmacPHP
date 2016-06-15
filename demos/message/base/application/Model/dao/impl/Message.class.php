<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: User.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
class dao_impl_Message_base extends dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'message';
        $this->setPrimaryKeyField( 'message_id' );
    }

}
