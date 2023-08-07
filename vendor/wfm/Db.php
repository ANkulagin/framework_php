<?php

namespace wfm;

use RedBeanPHP\R;

class Db
{
    use TSingleton;
    private function __construct()
    {
        $db =require_once  ROOT. '/config/config_db.php';
        R::setup($db['dsn'], $db['user'], $db['password']);
        if (!R::testConnection()) {
            throw new \Exception("no connect", 500);
        }
        R::freeze(true);
        if (DEBUG){
            R::debug(true,3);
        }
    }
}