<?php
$g_phpDir = "php";
if (! is_dir($g_phpDir))
    $g_phpDir = "../../" . $g_phpDir; // for ajax calls
if (! is_dir($g_phpDir))
    error_log("Fatal error : php directory not found");

include_once $g_phpDir . '/view_model/interface/IBuilder.php';

/**
 * Template builder
 *
 * @author dams
 *        
 * @example $data = array('title' => 'My title', 'content' => 'My content');
 *          $template = new TemplateBuilder('mypage.html', $data);
 *          echo $template->build();
 */
class TemplateBuilder implements IBuilder
{

    protected $path, $data;

    // / constructor with path of template html file, and variables array to substitute
    public function __construct($path, $data = array())
    {
        $this->path = $path;
        $this->data = $data;
    }

    // / return template content with variables substitued (string buffer)
    public function build()
    {
        if (! file_exists($this->path)) {
            $this->path = 'php/view/' . $this->path;
        }
        if (! file_exists($this->path)) {
            $this->path = '../../' . $this->path; // required for ajax calls
        }
        if (file_exists($this->path)) {
            // Extracts vars to current view scope
            extract($this->data);
            
            // Starts output buffering
            ob_start();
            
            // Includes contents
            include $this->path;
            
            // return content with variables substitued
            return ob_get_clean();
        } else {
            // return error
            return 'error :  [' . $this->path . '] not found';
        }
    }
}


?>
