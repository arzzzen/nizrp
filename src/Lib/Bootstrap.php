<?php
namespace Admin\Lib;

use Symfony\Component\Yaml\Yaml;

class Bootstrap {
    public static $projectDir;
    
    /**
     * Читает конфигурацию из Yaml файла $name
     * @param string $name Имя файла в директории конфига
     */
    public static function readConfig($name) {
        $yaml = file_get_contents(self::$projectDir.DIRECTORY_SEPARATOR.'conf'.DIRECTORY_SEPARATOR.$name.'.yml');
        return Yaml::parse($yaml);
    }

    public static function boot() {
        self::$projectDir = dirname(__DIR__);
    }

}