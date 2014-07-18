<?php
namespace Admin\Controllers;
use Admin\Lib\Controller as Controller;

class Test extends Controller {
	function __construct() {
	}
        
        public function index() {
            echo 'Test index';
        }
        
        public function test() {
            echo 'Test test';
        }
}