<?php

namespace Application\Validator;
use Application\Validator\AbstractValidator;

class PasswordHash extends AbstractValidator
{
    protected $messageTemplates = array(
        self::INVALID_VALUE    => "The input is not valid",
    );

    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $hash = $this->getFilter()->getSm()->get('Auth\Service')->passwordHash($value);
        //var_dump($value, $this->getFilter()->getUserRow()->password, $hash);exit;
        if($this->getFilter()->getUserRow()->password == $hash){
            return true;
        }
        $this->error(self::INVALID_VALUE);
        return false;
    }
}
