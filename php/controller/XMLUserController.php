<?php
$g_phpDir = "php";
if(!is_dir($g_phpDir)) $g_phpDir = "../../" . $g_phpDir;  //for ajax calls
if(!is_dir($g_phpDir)) error_log("Fatal error : php directory not found");

include_once 'interface/IUserController.php';
include_once $g_phpDir . '/data_model/DataPagesXML.php';


class XMLUserController implements IUserController {
    private $fileUsers = "xml/.users.xml";
    //! THIS SALT IS FOR EXAMPLE 
    //! USE YOUR OWN SALT AND SSL SECURE CONNEXION
    private $saltPwd = "108357";
    
    private $doc;
    private $isLoaded = false;
    
    public function __construct (){
        $this->loadUsers();        
    }
    
    private function loadUsers(){
        if (!file_exists($this->fileUsers)) {
            //check from js/controller dir
            $this->fileUsers = "../../" . $this->fileUsers;
            if (!file_exists($this->fileUsers)) {
                error_log("XMLUserController::loadUsers() error : " . $this->fileUsers . " not found");
                $this->isLoaded = false;
                return;
            }
        }
        
        $this->doc = new DOMDocument();
        $this->doc->preserveWhiteSpace = false; //avoid whitespace #text node
        $this->doc->load($this->fileUsers);
        
        $root = $this->doc->documentElement;
        $pages = $root->childNodes;
                
        $this->isLoaded = true;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see IUserController::isValidUser()
     */
    public function isValidUser($username_in, $password_in, &$role_out, &$isDisabled)
    {
        if(!$this->isLoaded){
            error_log("XMLUserController::isValidUser() error");
            return false;
        }
        $root = $this->doc->documentElement;
        $users = $root->childNodes;
        $found = false;
        $valid = false;
        
        error_log("checking user [".$username_in."] and password");
        foreach($users as $user){
            $usernameXml = $user->getElementsByTagName("name")->item(0)->nodeValue;
            $passwordXml = $user->getElementsByTagName("password")->item(0)->nodeValue;
            $roleXml = $user->getAttribute("role");
            $enableXml = $user->getAttribute("enable");
          
            if($username_in == $usernameXml && $password_in == $passwordXml){
                $found = true;
                if($enableXml != "true"){
                    error_log("user [".$username_in."] is disabled");
                    $isDisabled = true;
                    return false;
                }else{
                    $isDisabled = false;
                    $role_out = $roleXml;
                    return true;                 
                }                
            }else if($username_in == $usernameXml && $password_in != $passwordXml){
                error_log("user [".$username_in."] found but wrong password"); 
                return false;
            }
        }
        error_log("user [".$username_in."] not found");
        return false;        
    }
    
   
    
    /**
     * 
     * {@inheritDoc}
     * @see IUserController::getPasswordHash()
     */
    public function getPasswordHash($password){
        return hash("sha256", $password.$this->saltPwd);
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see IUserController::isUserAuthorized()
     */
    public function isUserAuthorized(DataPage $page){
        //TODO
        return false;
    }
}

?>