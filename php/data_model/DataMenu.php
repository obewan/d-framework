<?php
include_once dirname(__FILE__) . '/interface/IData.php';
include_once dirname(__FILE__) . '/interface/IMenuButton.php';

define("MENU_ELT", "menu");
define("MENU_GROUP_ELT", "group");
define("MENU_PAGE_ELT", "page");
define("MENU_SECTION_ELT", "section");
define("MENU_SUBSECTION_ELT", "subsection");

define("MENU_TITLE_ELT", "title");
define("MENU_SRC_ELT", "src");
define("MENU_ACTION_ELT", "action");
define("MENU_ICON_ELT", "icon");
define("MENU_LANG_ELT", "lang");

define("MENU_WINDOW_ATTR", "window");
define("MENU_ACCESS_ATTR", "access");
define("MENU_DEFAULT_ATTR", "default");
define("MENU_DIRECTION_ATTR", "direction");

define("MENU_WINDOW_MODAL_VALUE", "modal");
define("MENU_WINDOW_PAGE_VALUE", "page");

define("MENU_ACCESS_ALL_VALUE", "all");
define("MENU_ACCESS_GUEST_VALUE", "guest");
define("MENU_ACCESS_USER_VALUE", "user");
define("MENU_ACCESS_SUPERUSER_VALUE", "superuser");
define("MENU_ACCESS_MANAGER_VALUE", "manager");
define("MENU_ACCESS_ADMIN_VALUE", "admin");

define("MENU_DIRECTION_HORIZONTAL_VALUE", "horizontal");
define("MENU_DIRECTION_VERTICAL_VALUE", "vertical");

define("MENU_TRUE_VALUE", "true");
define("MENU_FALSE_VALUE", "false");

define("MENU_LANG_ENGLISH_VALUE", "eng");
define("MENU_LANG_FRENCH_VALUE", "fr");
define("MENU_LANG_GERMAN_VALUE", "du");
define("MENU_LANG_JAPANESE_VALUE", "jp");
define("MENU_LANG_CHINESE_VALUE", "ch");
define("MENU_LANG_RUSSIAN_VALUE", "ru");
define("MENU_LANG_GREEK_VALUE", "gr");

define("MENU_LOGOUT_TEXT", "Logout");
define("MENU_LOGOUT_ACTION", "logout");
define("MENU_LOGOUT_ICON", "fi-unlock");

abstract class DataMenu implements IData
{

    protected $title = "";
    protected $lang = "";

    protected $groups = array();

    /**
     * *
     * 
     * @return true if menu data is loaded
     */
    abstract function isLoaded();
    
    public function setTitle($title){
        $this->title = $title;
    }
    
    public function getTitle(){
        return $this->title;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function addGroup(DataMenuGroup $group)
    {
        $this->groups[] = $group;
    }

    public function addGroups(array &$groups)
    {
        $this->groups = array_merge($this->groups, $groups);
    }

    public function getGroups()
    {
        return $this->groups;
    }
}

abstract class DataMenuGroup implements IData
{

    protected $title;

    protected $sections = array();

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function addSection(DataMenuSection $section)
    {
        $this->sections[] = $section;
    }

    public function getSections()
    {
        return $this->sections;
    }
}

abstract class DataMenuPage implements IData, IMenuButton
{

    protected $title = "";

    protected $src = "";

    protected $action = "";

    protected $icon = "";

    protected $active = false;
    
    protected $window = MENU_WINDOW_PAGE_VALUE;

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setSrc($src)
    {
        $this->src = $src;
    }

    public function getSrc()
    {
        return $this->src;
    }
    
    public function setWindow($window)
    {
        $this->window = $window;
    }
    
    public function getWindow()
    {
        return $this->window;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setActive($active)
    {
        $this->active = $active;
    }

    public function isActive()
    {
        return $this->active;
    }
}

abstract class DataMenuSection extends DataMenuPage
{

    protected $subsections = array();

    protected $access = MENU_ACCESS_ALL_VALUE;

    protected $window = MENU_WINDOW_PAGE_VALUE;

    protected $default = false;

    public function addSubSection(DataMenuSubSection $subsection)
    {
        $this->subsections[] = $subsection;
    }

    public function getSubSections()
    {
        return $this->subsections;
    }

    public function setAccess($access)
    {
        $this->access = $access;
    }

    public function getAccess()
    {
        return $this->access;
    }

    public function setWindow($window)
    {
        $this->window = $window;
    }

    public function getWindow()
    {
        return $this->window;
    }

    public function setDefault($default)
    {
        $this->default = $default;
    }

    public function isDefault()
    {
        return $this->default;
    }
}

abstract class DataMenuSubSection extends DataMenuPage
{

    protected $pages = array();

    protected $subsections = array();

    protected $direction = MENU_DIRECTION_VERTICAL_VALUE;

    protected $access = MENU_ACCESS_ALL_VALUE;
    
    protected $window = MENU_WINDOW_PAGE_VALUE;

    public function addPage($page)
    {
        $this->pages[] = $page;
    }

    public function setPages($pages)
    {
        $this->pages = pages;
    }

    public function getPages()
    {
        return $this->pages;
    }
    
    public function setWindow($window)
    {
        $this->window = $window;
    }
    
    public function getWindow()
    {
        return $this->window;
    }

    public function addSubSection(DataMenuSubSection $subsection)
    {
        $this->subsections[] = $subsection;
    }

    public function getSubSections()
    {
        return $this->subsections;
    }

    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    public function getDirection()
    {
        return $this->direction;
    }

    public function setAccess($access)
    {
        $this->access = $access;
    }

    public function getAccess()
    {
        return $this->access;
    }
}



?>
