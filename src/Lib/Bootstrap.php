<?php
namespace Admin\Lib;

use Symfony\Component\Yaml\Yaml;

class Bootstrap {

    function __construct() {
        
    }
    
    public static function boot() {
        $projectDir = dirname(__DIR__);
        $ftp = Yaml::parse(file_get_contents($projectDir.'/conf/ftp.yml'));
    }
    
    /**
     * Возвращает путь к папке проекта
     * @return string Путь к папке проекта
     */
    public static function getDir() {
        return dirname(__DIR__);
    }

}