<?php
/**
 * Created by PhpStorm.
 * User: martin
 * Date: 28.01.19
 * Time: 16:25
 */





namespace AppBundle\Service;


class AutoService
{
    public function getHappyMessage()
    {
        $link='https://mitarbeiterautohaus.meinauto.de/data/modelle/17d323d6fd823d9e7f8953085bab63f6';

       $autos=file_get_contents($link);




        return $messages[$index];
    }


}