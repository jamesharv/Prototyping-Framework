<?php
foreach(glob(dirname(__FILE__) . '/Proton/*.php') as $file){
    require_once $file;
}
?>