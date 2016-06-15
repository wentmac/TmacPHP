<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: UserType.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
class dao_impl_UserType_base extends dao_BaseDao_base
{

    public function __construct($link_identifier)
    {
        parent::__construct($link_identifier);
        $this->table = DB_PREFIX . 'user_type';        
        $this->setPrimaryKeyField('rank');
    }

    
}
