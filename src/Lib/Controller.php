<?php
namespace Admin\Lib;
use Admin\Lib\Bootstrap;

class Controller {
    private $class_name;

    public final function __construct() {
        $this->class_name = get_called_class();
    }
    
    /**
     * Возвращает клаасс контроллера
     * @return string Класс контроллера
     */
    public function getClassName() {
        return $this->class_name;
    }
    
    /**
     * Генерирует шаблон из $params
     * @param array $params Массив для передачи в шаблон
     * @param string $layout Путь к файлу шаблона относительно директории шаблонов, если нет вычисляется из класса и функции
     * @param bool $echo Выводить или вернуть строкой
     * @return string Отрендеренный шаблон если $echo == false
     */
    public function render($params = null, $layout = null, $echo = true) {
        if (!$layout) {
            $controler_class = new \ReflectionClass($this->getClassName());
            $short_controller_class = mb_strtolower($controler_class->getShortName());
            $called_func = mb_strtolower(debug_backtrace(1)[1]['function']);
            $layout = $short_controller_class.DIRECTORY_SEPARATOR.$called_func.'.twig';
        }
        $projectDir = Bootstrap::getDir();
        $loader = new \Twig_Loader_Filesystem($projectDir.DIRECTORY_SEPARATOR.'Views');
        $twig = new \Twig_Environment($loader);
        $template = $twig->loadTemplate($layout);
        if ($echo) {
            echo $template->render($params);
        } else {
            return $template->render($params);
        }
    }
}