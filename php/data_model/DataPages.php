<?php

include_once dirname(__FILE__) . '/interface/IData.php';

abstract class DataPages implements IData
{

    protected $pages = array();

    public function addPage(DataPage $page)
    {
        $this->pages[] = $page;
    }

    public function getPages()
    {
        return $this->pages;
    }
}

abstract class DataChapter implements IData
{

    protected $title;

    protected $author;

    protected $date;

    protected $image;

    protected $screens = array();

    protected $abstract;

    protected $comment;

    protected $progress;

    protected $formulas = array();

    protected $texts = array();

    protected $include;

    protected $infos = array();

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function addScreen(DataScreen $screen)
    {
        $this->screens[] = $screen;
    }

    public function getScreens()
    {
        return $this->screens;
    }

    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    }

    public function getAbstract()
    {
        return $this->abstract;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setProgress($progress)
    {
        $this->progress = $progress;
    }

    public function getProgress()
    {
        return $this->progress;
    }

    public function addText($text)
    {
        $this->texts[] = $text;
    }

    public function getTexts()
    {
        return $this->texts;
    }

    public function addInfo(DataInfo $info)
    {
        $this->infos[] = $info;
    }

    public function getInfos()
    {
        return $this->infos;
    }

    public function setInclude($include)
    {
        $this->include = $include;
    }

    public function getInclude()
    {
        return $this->include;
    }

    public function addFormula(DataFormula $formula)
    {
        $this->formulas[] = $formula;
    }

    public function getFormulas()
    {
        return $this->formulas;
    }
}

abstract class DataPage extends DataChapter implements IData
{

    protected $chapters = array();

    protected $type;
    
    protected $icon;

    public function addChapter(DataChapter $chapter)
    {
        $this->chapters[] = $chapter;
    }

    public function getChapters()
    {
        return $this->chapters;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }
    
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }
    
    public function getIcon()
    {
        return $this->icon;
    }    
}

/**
 * Screenshots class
 */
abstract class DataScreen implements IData
{

    protected $thumb;

    protected $big;

    public function setThumb($thumb)
    {
        $this->thumb = $thumb;
    }

    public function getThumb()
    {
        return $this->thumb;
    }

    public function setBig($big)
    {
        $this->big = $big;
    }

    public function getBig()
    {
        return $this->big;
    }
}

abstract class DataInfo implements IData
{

    protected $date;

    protected $scale;

    protected $text;

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setScale($scale)
    {
        $this->scale = $scale;
    }

    public function getScale()
    {
        return $this->scale;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }
}

abstract class DataFormula implements IData
{

    protected $name;

    protected $quote;

    protected $cite;

    protected $comment;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setQuote($quote)
    {
        $this->quote = $quote;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function setCite($cite)
    {
        $this->cite = $cite;
    }

    public function getCite()
    {
        return $this->cite;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getComment()
    {
        return $this->comment;
    }
}
	


?>