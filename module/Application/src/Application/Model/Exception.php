<?php

namespace Application\Model;

class Exception  extends \Exception
{
    public function __construct ($message, $code=null, $previous=null) 
    {
        echo $message;exit;
    }
}