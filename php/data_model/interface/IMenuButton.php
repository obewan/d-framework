<?php
/**
 * IMenuButton interface
 *
 * @author dams
 *
 */
interface IMenuButton
{
    /**
     * @return string button window mode
     */
    public function getWindow();
    
    /**
     * @return string button title
     */
    public function getTitle();
    
    /**
     * @return string button icon name
     */
    public function getIcon();
    
    /**
     * @return string button action
     */
    public function getAction();
    
     
    /**
     * @return string button src
     */
    public function getSrc();
    
    
    /**
     * @return boolean true if actif
     */
    public function isActive();
}

?>