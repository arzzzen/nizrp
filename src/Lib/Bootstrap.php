<?php
namespace Admin\Lib;

use Symfony\Component\Yaml\Yaml;

class Bootstrap {

    function __construct() {
        
    }
    
    public static function boot() {
        $projectDir = dirname(__DIR__);
        $yaml = Yaml::parse(file_get_contents($projectDir.'/conf/conf.yml'));
        echo var_dump($yaml);
    }

}