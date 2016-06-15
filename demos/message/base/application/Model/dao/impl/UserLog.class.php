<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: UserLog.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
class dao_impl_UserLog_base extends dao_BaseDao_base
{

    public function __construct($link_identifier)
    {
        parent::__construct($link_identifier);
        $this->table = DB_PREFIX . 'user_log';        
        $this->setPrimaryKeyField('log_id');
    }

    
}
