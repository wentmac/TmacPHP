<?php

/**
 * Power By Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: TmacClassException.class.php 325 2016-05-31 10:07:35Z zhangwentao $
 * http://shop.weixinshow.com； 
 */
class TmacClassException extends Exception
{


    /**
     * 构造器
     *
     * @param string $message
     * @param int $code
     * @access public
     */
    public function __construct($message = 'Unknown Error', $code = 0)
    {
        parent::__construct($message, $code);

    }

}

?>
