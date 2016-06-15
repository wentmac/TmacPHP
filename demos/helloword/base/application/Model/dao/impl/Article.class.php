<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Article.class.php 330 2016-06-01 14:04:25Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
class dao_impl_Article_base extends dao_BaseDao_base implements dao_Article_base
{

    public function __construct( $link_identifier )
    {
        parent::__construct( $link_identifier );
        $this->table = DB_WS_PREFIX . 'article';        
        $this->setPrimaryKeyField( 'article_id' );
    }

    /**
     * 根据筛选条件返回where语句
     * @param entity_parameter_Article_base $entity_parameter_Article_base


     */
    public function getListWhere( entity_parameter_Article_base $entity_parameter_Article_base )
    {

    }

}
