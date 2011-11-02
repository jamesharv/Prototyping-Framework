<?php
namespace ViewHelper;

class Button extends \ViewHelper
{
    public function invoke($text)
    {
        return '<a href="#">' . $text . '</a>';
    }
}
?>