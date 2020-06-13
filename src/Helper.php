<?php
class Helper
{
    public static function makeLog($file = 'log.json', $content)
    {
        ob_start();
        var_dump($content);
        $output = ob_get_clean();
        file_put_contents($file, $output);
    }
}
