<?php
foreach(glob(dirname(__FILE__) . '/lib/*.php') as $file){
    require_once $file;
}
?>