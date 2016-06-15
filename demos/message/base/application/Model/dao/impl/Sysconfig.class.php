<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Sysconfig.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 */

/**
 * Description of Category
 *
 * @author Tracy McGrady
 */
class dao_impl_Sysconfig_base extends dao_BaseDao_base
{

    public function __construct($link_identifier)
    {
        parent::__construct($link_identifier);
        $this->table = DB_PREFIX . 'sysconfig';
        $this->setPrimaryKeyField('sys_id');
    }
   
    
}
