<?php
include_once 'interface/IView.php';
include_once 'builder/TemplateBuilder.php';

/**
 * Content view model
 *
 * @author dams
 *        
 */
class Content implements IView
{

    protected $m_ref = "";

    public function __construct($ref)
    {
        $this->m_ref = $ref;
    }

    public function build()
    {
        $template = new TemplateBuilder($this->m_ref);
        
        return $template->build();
    }
}

?>

