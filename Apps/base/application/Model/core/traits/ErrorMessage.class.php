<?php

/**
 * Created by PhpStorm.
 * User: zhang
 * Date: 2017/4/15
 * Time: 1:55
 */

namespace base\core\traits;

trait ErrorMessage
{

    protected $errorMessage;

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param mixed $errorMessage
     */
    public function setErrorMessage( $errorMessage )
    {
        $this->errorMessage = $errorMessage;
    }


}