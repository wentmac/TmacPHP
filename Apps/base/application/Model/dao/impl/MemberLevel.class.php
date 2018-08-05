<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: MemberLevel.class.php 721 2016-12-04 17:05:34Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
namespace base\dao\impl;

class MemberLevel extends \dao_BaseDao_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'member_level';
        $this->setPrimaryKeyField( 'member_level_id' );
    }

}
