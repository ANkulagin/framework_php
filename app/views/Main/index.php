<?php
/** @var MainController $names */
use app\controllers\MainController;
if (!empty($names)):
    foreach ($names as $name):
        echo $name->id . ' => ' . $name->name;
    endforeach;
endif;
?>
<h1>HEllo test</h1>


