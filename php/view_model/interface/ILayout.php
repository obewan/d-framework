<?php

/**
 * ILayout interface
 *
 * @author dams
 *
 */
interface ILayout extends IView
{

    /**
     * Set a menu view model to render in the layout
     */
    public function setMenuModel(IView $menuModel);

    /**
     * Get the menu model
     * 
     * @return NULL || (IView) the menu model
     */
    public function getMenuModel();

    /**
     * Set a page view model to render in the layout
     * 
     * @param IView $contentModel
     */
    public function setPageModel(IView $contentModel);

    /**
     * Get the page model
     * 
     * @return NULL || (IView) the page model
     */
    public function getPageModel();
}

?>
