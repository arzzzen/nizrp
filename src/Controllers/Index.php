<?php
namespace Admin\Controllers;
use Admin\Lib\Controller;
use Admin\Lib\Ext\Mailer;

class Index extends Controller {

    public function index() {
        
        $mailer = new Mailer();
        $messages = $mailer->getMails();

        $this->render(array('messages' => $messages));
    }
}