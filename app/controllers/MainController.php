<?php

namespace app\controllers;

use wfm\Controller;

class MainController extends Controller
{

    public function indexAction(){
        $this->setMeta('Главная страницы','descripton','keywords');
    }
}