<?php
require_once __DIR__ . '/AdminController.php';

class Dashboard extends AdminController {
    public function index() {
        $this->layout->view('dashboard/index');
    }
}
?>
