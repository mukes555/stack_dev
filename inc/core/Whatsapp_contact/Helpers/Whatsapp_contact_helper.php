<?php


function replaceInFile($filename, $from = '', $to='')
{
    $content=file_get_contents($filename);

    if (mb_check_encoding($content, 'WINDOWS-1251')){
        $content = mb_convert_encoding($content, 'UTF-8', 'WINDOWS-1251');
    }
    $content_chunks=explode($from, $content);
    $content=implode($to, $content_chunks);
    file_put_contents($filename,$content);
}
