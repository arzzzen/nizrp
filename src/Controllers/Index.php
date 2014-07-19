<?php
namespace Admin\Controllers;
use Admin\Lib\Controller;

class Index extends Controller {

        public function index() {
            $class = $this->getClassName();
            $this->render(array('content' => $class));
        }
}