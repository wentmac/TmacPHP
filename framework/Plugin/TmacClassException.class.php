<?php

/**
 * Power By Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: TmacClassException.class.php 6 2014-09-20 15:13:57Z zhangwentao $
 * http://www.t-mac.org； 
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
