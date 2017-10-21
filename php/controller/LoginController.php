<?php
$g_phpDir = "php";
if(!is_dir($g_phpDir)) $g_phpDir = "../../" . $g_phpDir;  //for ajax calls
if(!is_dir($g_phpDir)) error_log("Fatal error : php directory not found");

include_once 'tools/Tools.php';
include_once $g_phpDir . '/view_model/Login.php';
include_once 'XMLUserController.php';

define("POST_DATA", "data");
define("POST_USERNAME", "username");
define("POST_PWD", "password");

define("SESSION_USER", 'user');
define("SESSION_ROLE", 'role');
define("SESSION_ATTEMPT", 'attempt');

define("CODE_SUCCESS", "success");
define("CODE_ERROR", "error");
define("CODE_MAX_ATTEMPT", "attemptmax");

define("LOGIN_ATTEMPT_MAX_COUNT", 3);

define("LOGIN_ACCESS_GRANTED_MSG", "Access granted");
define("LOGIN_ERROR_MSG", "Access denied : invalid username or password");
define("LOGIN_DISABLED_MSG", "Access denied : invalid user account");
define("LOGIN_MAX_ATTEMPT_ERROR_MSG", "Access denied : maximum of attempts reach.");
    
class LoginController  {
    
    public static function isLogged(){
        return isset($_SESSION[SESSION_USER]);
    }
    
    public static function getRole(){
        return $_SESSION[SESSION_ROLE];
    }


    /// Valid login form and set user if valid
    public static function valid(){
        if(isset($_SESSION[SESSION_USER])){
            unset($_SESSION[SESSION_USER]);
        }
        if(isset($_SESSION[SESSION_ROLE])){
            unset($_SESSION[SESSION_ROLE]);
        }       
        
        if(isset($_SESSION[SESSION_ATTEMPT]) &&
            $_SESSION[SESSION_ATTEMPT] >= LOGIN_ATTEMPT_MAX_COUNT){
                $code = CODE_MAX_ATTEMPT;
                echo Tools::JsonMessage($code, LOGIN_MAX_ATTEMPT_ERROR_MSG);
                session_write_close();
                return;
        }
        if(isset($_POST[POST_USERNAME]) && isset($_POST[POST_PWD])){
            $controller = new XMLUserController();
            $post_username = $_POST[POST_USERNAME];
            $post_pwd = $_POST[POST_PWD];           
            $role_out = "";
            $isDisabled = false;
            $isValid = $controller->isValidUser($post_username, $post_pwd, $role_out, $isDisabled);
            if($isValid){
                $_SESSION[SESSION_USER] = $post_username;
                $_SESSION[SESSION_ROLE] = $role_out;
                unset($_SESSION[SESSION_ATTEMPT]);
                
                $code = CODE_SUCCESS;
                echo Tools::JsonMessage($code, LOGIN_ACCESS_GRANTED_MSG);
                
            }else{                
                if(!isset($_SESSION[SESSION_ATTEMPT])){
                    $_SESSION[SESSION_ATTEMPT] = 1;
                }else{
                    $_SESSION[SESSION_ATTEMPT]++;                   
                }
                
                $code = CODE_ERROR;
                if($isDisabled){
                    echo Tools::JsonMessage($code, LOGIN_DISABLED_MSG);
                }else{
                    echo Tools::JsonMessage($code, LOGIN_ERROR_MSG);
                }
            }
        }         
        
    }
    
    
    
    public static function logout()
    {
        if(isset($_SESSION[SESSION_USER])){
            unset($_SESSION[SESSION_USER]);  
            unset($_SESSION[SESSION_ROLE]);
            unset($_SESSION[SESSION_ATTEMPT]);
        }
    }
}

?>
