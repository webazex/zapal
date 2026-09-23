<?php
$consantsPath = get_template_directory().DIRECTORY_SEPARATOR.'inc'.DIRECTORY_SEPARATOR.'constants.php';
if(!file_exists($consantsPath)) {
    wp_die("Constants File Not Found");
}
//load zapal constants
require_once $consantsPath;

//add css&js
$sourcesPath = INC_DIR.'sources.php';
if(!file_exists($sourcesPath)) {
    wp_die("CSS & JS file Not Enabled");
}
require_once $sourcesPath;