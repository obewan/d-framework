<?php
$g_phpDir = "php";
if (! is_dir($g_phpDir))
    $g_phpDir = "../../" . $g_phpDir; // for ajax calls
if (! is_dir($g_phpDir))
    error_log("Fatal error : php directory not found");

include_once 'builder/TemplateBuilder.php';
include_once 'builder/MenuButtonBuilder.php';
include_once 'interface/IView.php';
include_once $g_phpDir . '/data_model/DataMenu.php';

/**
 * Menu view model, decorator of Menu data model
 */
class ViewMenu extends DataMenu implements IView
{

    protected $menuData;

   /**
    * 
    * {@inheritDoc}
    * @see DataMenu::isLoaded()
    */
    public function isLoaded()
    {
        if ($this->menuData != null) {
            return $this->menuData->isLoaded();
        }
        return false;
    }

    /**
     * 
     * {@inheritDoc}
     * @see IData::load()
     */    
    public function load($source)
    {
        if ($source instanceof DataMenu) {
            return $this->loadMenu($source);
        }
        return false;
    }

    /**
     * 
     * {@inheritDoc}
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadMenu(DataMenu $menu)
    {
        $this->menuData = $menu;
        
        $title = $this->menuData->getTitle();
        $this->setTitle($title);
        
        $lang = $this->menuData->getLang();
        $this->setLang($lang);
        
        $groups = $this->menuData->getGroups();
        foreach ($groups as $group) {
            $menuGroup = new ViewMenuGroup();
            $menuGroup->load($group);
            $this->addGroup($menuGroup);
        }
        return true;
    }

    /**
     * 
     * {@inheritDoc}
     * @see IView::build()
     */
    public function build()
    {
        $menuContent = "";
        $menuTitle = $this->getTitle();        
        $groups = $this->getGroups();
        $groupNum = 0;
        $groupsLength = count($groups);
        
        foreach ($groups as $group) {
            $menuContent .= $group->build();
            
            if ($groupNum < $groupsLength - 1) {
                $menuContent .= '<li class="divider"></li>' . PHP_EOL;
            }
            $groupNum ++;
        }
        
        // username
        if (isset($_SESSION['user'])) {
            $menuContent .= '<li><a><span class="radius label">' . $_SESSION['user'] . '</span></a></li>' . PHP_EOL;
        }
        
        // template
        $data = array(
            'title' => $menuTitle,
            'menu' => $menuContent
        );
        $template = new TemplateBuilder('menu.phtml', $data);
        
        return $template->build();
    }
}

/**
 *
 * @author dams
 *        
 */
class ViewMenuGroup extends DataMenuGroup implements IView
{

    protected $menuGroup;

    /**
     *
     * {@inheritdoc}
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataMenuGroup) {
            $this->loadMenuGroup($source);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadMenuGroup(DataMenuGroup $menuGroup)
    {
        $this->menuGroup = $menuGroup;
        
        $sections = $menuGroup->getSections();
        foreach ($sections as $section) {
            $menuSection = new ViewMenuSection();
            $menuSection->load($section);
            $this->addSection($menuSection);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $sections = $this->getSections();
        $ret = "";
        
        foreach ($sections as $section) {
            $ret .= $section->build();
        }
        
        return $ret;
    }
}

/**
 *
 * @author dams
 *        
 */
class ViewMenuPage extends DataMenuPage implements IView
{

    protected $menuPage;

    /**
     *
     * {@inheritdoc}
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataMenuPage) {
            $this->loadMenuPage($source);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadMenuPage(DataMenuPage $menuPage)
    {
        $this->menuPage = $menuPage;
    }

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $button = new MenuButtonBuilder($this->menuPage);
        return $button->build();
    }
}

/**
 * MenuSection view model class
 * 
 * @author dams
 *        
 */
class ViewMenuSection extends DataMenuSection implements IView
{

    protected $menuSection;

    /**
     *
     * {@inheritdoc}
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataMenuSection) {
            $this->loadMenuSection($source);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadMenuSection(DataMenuSection $menuSection)
    {
        $this->menuSection = $menuSection;
        
        $subsections = $menuSection->getSubSections();
        foreach ($subsections as $subsection) {
            $menuSubSection = new ViewMenuSubSection();
            $menuSubSection->load($subsection);
            $this->addSubSection($menuSubSection);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $subsections = $this->getSubSections();
        
        if (! ViewController::has_access($this->menuSection)) {
            return "";
        }
        
        $active = '';
        if ($this->menuSection->isActive()) {
            $active = "active";
        }
        
        if (count($subsections) > 0) {
            // build drop menu
            $ret = "";
            foreach ($subsections as $subsection) {
                $ret .= $subsection->build();
            }
            
            $data = array(
                'li_id' => 'li_' . $this->menuSection->getTitle(),
                'active' => $active,
                'a_id' => 'a_' . $this->menuSection->getTitle(),
                'icon' => $this->menuSection->getIcon(),
                'title' => $this->menuSection->getTitle(),
                'dropMenu' => $ret
            );
            $template = new TemplateBuilder('menuDrop.phtml', $data);
            return $template->build();
        } else {
            // build button
            $button = new MenuButtonBuilder($this->menuSection);
            return $button->build();
        }
    }
}

/**
 * MenuSubSection view model class
 * 
 * @author dams
 *        
 */
class ViewMenuSubSection extends DataMenuSubSection implements IView
{

    protected $menuSubSection;

    /**
     *
     * {@inheritdoc}
     * @see IData::load()
     */
    public function load($source)
    {
        if ($source instanceof DataMenuSubSection) {
            $this->loadMenuSubSection($source);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IData::save()
     */
    public function save()
    {}

    protected function loadMenuSubSection(DataMenuSubSection $menuSubSection)
    {
        $this->menuSubSection = $menuSubSection;
        
        $pages = $menuSubSection->getPages();
        foreach ($pages as $page) {
            $menuPage = new ViewMenuPage();
            $menuPage->load($page);
            $this->addPage($menuPage);
        }
        
        $subsections = $menuSubSection->getSubSections();
        foreach ($subsections as $subsection) {
            $menuSubSection = new ViewMenuSubSection();
            $menuSubSection->load($subsection);
            $this->addSubSection($menuSubSection);
        }
    }

    /**
     *
     * {@inheritdoc}
     * @see IView::build()
     */
    public function build()
    {
        $subsections = $this->getSubSections();
        if (count($subsections) > 0) {
            //list of subsections
            $ret = "";
            foreach ($subsections as $subsection) {
                $ret .= $subsection->build();
            }
            // TODO recursive subsections...
            return $ret;
        } else {          
            $pages = $this->getPages();
            if (count($pages) > 0) {
                //list of pages
                $section = "";
                foreach ($pages as $page) {
                    $section .= $page->build();
                }
                $data = array(
                    'li_id' => 'li_' . $this->menuSubSection->getTitle(),
                    'label' => $this->menuSubSection->getTitle(),
                    'section' => $section
                );
                $template = new TemplateBuilder('menuSection.phtml', $data);
                return $template->build();
            } else {           
                // build button
                $button = new MenuButtonBuilder($this->menuSubSection);
                return $button->build();
            }
        }
    }
}

?>
