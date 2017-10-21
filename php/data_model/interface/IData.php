<?php

interface IData
{

    // load from data source
    function load($source);

    // save to data source
    function save();
}

?>
