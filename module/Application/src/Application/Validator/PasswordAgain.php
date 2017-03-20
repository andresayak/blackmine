<?php

namespace Application\Validator;
use Application\Validator\AbstractValidator;

class PasswordAgain extends AbstractValidator
{
    const INVALID_VALUE = 'invalid';
    protected $messageTemplates = array(
        self::INVALID_VALUE    => "Password not same",
    );

    public function isValid($value, $context = null)
    {
        if(isset($context['password']) && $value == $context['password']){
            return true;
        }
        $this->error(self::INVALID_VALUE);
        return false;
    }
}
