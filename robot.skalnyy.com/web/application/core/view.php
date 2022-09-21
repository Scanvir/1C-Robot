<?php

class View
{
    //public $template_view; // здесь можно указать общий вид по умолчанию.

    function generate($content_view, $template_view = null, $data = null, $get = null)
    {
        if ($template_view)
            include 'application/views/'.$template_view;
        else
            include 'application/views/'.$content_view;
        
        if(is_array($get)) {
            // преобразуем элементы массива в переменные
            extract($get);
        }
    }
}
