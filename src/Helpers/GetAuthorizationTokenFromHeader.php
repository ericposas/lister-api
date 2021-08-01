<?php

namespace PHPapp\Helpers;

/**
 * Description of GetAuthorizationTokenFromHeader
 *
 * @author webdev00
 */
class GetAuthorizationTokenFromHeader {
    
    public static function getToken($request)
    {
        // get sent api token from header
        $auth_header = $request->getHeader("Authorization");
        $split_token = explode(" ", $auth_header[0]);
        $api_token = $split_token[1];
        return $api_token;
    }
    
}
