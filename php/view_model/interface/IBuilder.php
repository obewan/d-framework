<?php

/**
 * IBuilder interface
 *
 * @author dams
 *        
 */
interface IBuilder
{

    /**
     * Build the html template with php variables
     *
     * @return string buffer of builded content
     */
    public function build();
}

?>