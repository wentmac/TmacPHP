<?php
/**
 * Power By Tmac PHP MVC framework
 * $Author: zhangwentao $  <zwttmac@qq.com>
 * $Id: Controller.class.php 325 2016-05-31 10:07:35Z zhangwentao $
 * http://www.t-mac.org；
 */

class Container
{
    private $_instances = [];//已经实例化的服务

    /**
     * 返回服务，需要初始化
     */
    public function get( $name, $params = [] )
    {
        $object = new \ReflectionClass( $name );
        return $object->newInstanceArgs( $params );
    }

    /**
     * 取共享服务，返回单例，无需初始化
     */
    public function getShared( $name, $params = [], $alias = '' )
    {
        $alias = empty( $alias ) ? $name : $alias;
        if ( isset( $this->_instances[ $alias ] ) ) {
            $object = $this->_instances[ $alias ];
        } else {
            $classReflection = new \ReflectionClass( $name );
            $instance = $classReflection->newInstanceArgs( $params );
            $object = $this->_instances[ $alias ] = $instance;
        }

        return $object;
    }
}