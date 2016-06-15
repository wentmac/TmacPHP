<?php

/*
 * Tmac PHP MVC framework
 * $Author: zhangwentao $
 * $Id: Article.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 */

/**
 * Description of article
 *
 * @author Tracy McGrady
 */
interface dao_Article_base
{        
    public function getListWhere(entity_parameter_Article_base $entity_parameter_Article_base);
}
