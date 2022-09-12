<?php

class View
{
    //public $template_view; // здесь можно указать общий вид по умолчанию.

    function generate($content_view, $data = null, $get = null)
    {
        include 'application/views/'.$content_view;

        if(is_array($get)) {
            // преобразуем элементы массива в переменные
            extract($get);
        }
    }
}
