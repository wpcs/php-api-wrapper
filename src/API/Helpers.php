<?php
namespace WPCS\API;

class Helpers
{
    public static function get_error_message($responseBody)
    {
        if (property_exists($responseBody, 'Message'))
        {
            return $responseBody->Message;
        }

        return $responseBody->message;
    }
}