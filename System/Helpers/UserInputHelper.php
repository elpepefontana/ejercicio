<?php

namespace System\Helpers;

class UserInputHelper 
{
    
    // VALIDATION FUNCTIONS - FUNCIONES DE VALIDACION 
    
    public function emailValidation($toValidate)
    {
        return filter_var(trim($toValidate), FILTER_VALIDATE_EMAIL);
    }
    
    public function integerValidation($toValidate)
    {
        return filter_var(trim($toValidate), FILTER_VALIDATE_INT);
    }
    
    public function floatValidation($toValidate)
    {
        return filter_var(trim($toValidate), FILTER_VALIDATE_FLOAT);
    }
    
    public function booleanValidation($toValidate)
    {
        return filter_var(trim($toValidate), FILTER_VALIDATE_BOOLEAN);
    }
    
    // SANITATION FUNCTIONS - FUNCIONES DE SALUD
    
    public function emailSanitation($toSanitize)
    {
        $sanitized = htmlspecialchars($toSanitize);
        return !empty($sanitized) ? filter_var(trim($sanitized), FILTER_SANITIZE_EMAIL) : 'NULL';
    }
    
    public function integerSanitation($toSanitize)
    {
        $sanitized = htmlspecialchars($toSanitize);
        return !empty($sanitized) ? filter_var(trim($sanitized), FILTER_SANITIZE_NUMBER_INT) : 'NULL';
    }
    
    public function floatSanitation($toSanitize)
    {
        $sanitized = htmlspecialchars($toSanitize);
        return !empty($sanitized) ? filter_var(trim($sanitized), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION|FILTER_FLAG_ALLOW_THOUSAND) : 'NULL';
    }
    
    public function stringSanitation($toSanitize)
    {
        $sanitized = htmlspecialchars($toSanitize);
        return !empty($sanitized) ? filter_var(trim($sanitized), FILTER_SANITIZE_STRING) : 'NULL';
    }
    
}
