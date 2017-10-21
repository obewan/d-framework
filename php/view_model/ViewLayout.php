<?php
include_once 'interface/ILayout.php';
include_once 'builder/TemplateBuilder.php';

/**
 * Layout view model
 *
 * @author dams
 *        
 */
class ViewLayout implements ILayout
{

    protected $pageModel;

    protected $menuModel;

    protected $viewPage = 'layout.phtml';

    protected $mainTitle = 'Δ-Framework';

    /**
     *
     * {@inheritdoc}
     * @see ILayout::setMenuModel()
     */
    public function setMenuModel(IView $menuModel)
    {
        $this->menuModel = $menuModel;
    }

    /**
     *
     * {@inheritdoc}
     * @see ILayout::getMenuModel()
     */
    public function getMenuModel()
    {
        return $this->menuModel;
    }

    /**
     *
     * {@inheritdoc}
     * @see ILayout::setPageModel()
     */
    public function setPageModel(IView $contentModel)
    {
        $this->pageModel = $contentModel;
    }

    /**
     *
     * {@inheritdoc}
     * @see ILayout::getPageModel()
     */
    public function getPageModel()
    {
        return $this->pageModel;
    }

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $data = array(           
            'title' => $this->mainTitle,
            'menu' => $this->menuModel->build(),            
            'content' => $this->pageModel->build()
        );
        
        $template = new TemplateBuilder($this->viewPage, $data);
        
        return $template->build();
    }
}

?>
