<?php 

interface IUserController {
    
    /***
     *
     * @param string $password
     * @return the hashed password
     */
    function getPasswordHash($password);
    
    /**
     * 
     * @param string $username_in hashed text
     * @param string $password_in hashed text     
     * @param string $role_out clear text set, role of the user, set in the function
     * @param boolean $isDisabled in case of disabled user count, set in the function
     * @return true if valid user, and set role_out and isDisabled parameters
     */
    function isValidUser($username_in, $password_in, &$role_out, &$isDisabled);
    
    /***
     *
     * @param DataPage $page to page to control user access
     * @return true if current user is authorized to access the page
     */
    function isUserAuthorized(DataPage $page);
}

?>