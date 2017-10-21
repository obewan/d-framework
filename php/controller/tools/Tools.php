<?php

/***
 * Tools for Controllers
 * **/
class Tools {

    /// return a Json code and message for ajax receiver
    /// ex : echo Tools::JsonMessage("success", "message test");
    public static function JsonMessage($code, $message)
    {
        if(function_exists('json_encode')){
            $arr = array(
                'code' => $code,
                'message' => $message
            );
            return json_encode($arr);
        }else{
            $str = '{"code" : "' . $code .
                     '","message" : "'. $message . '"}'; 
            return $str;
        }
    }
    
    /// Format a string fr date dd/MM/YYYY into a english litteral date 
    //  with month and years, like "May 2010"
    public static function FormatDateFrToEng($date)
    {
        $ret = "";
        if(!empty($date)){
            $fields = explode("/", $date);
            if(!empty($fields) && count($fields)==3){
                $dayIndex = 0;
                $monthIndex = 1;
                $yearIndex = 2;
                $format = "F Y"; //F = full month text in english
                
                $timestamp = mktime(0, 0, 0, $fields[$monthIndex], $fields[$dayIndex], $fields[$yearIndex]);
                $ret = date($format, $timestamp);
            }
        }
        return $ret;
    }

}

?>
