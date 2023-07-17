<?php

namespace App\Api;

class ApiMessages{
    
    public static function getSuccessMessage(string $message,$data=[]){
        $msg['message'] = $message;
        $msg['data']  = $data;

        return $msg;
    }

    public static function getErrorMessage(string $message,$data=[]){
        $msg['message'] = $message;

        if(isset($data)){
            $msg['errors']  = $data;
        }
        

        return $msg;
    }
}
