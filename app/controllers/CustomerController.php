<?php
class CustomerController {
    public function index() {
        // Just render the customer view
        require_once 'views/customer/index.php';
        exit(); // Stop script execution to prevent loading admin layout
    }
    public function datlich() {
        require_once 'views/customer/datlich.php';
        exit();
    }
    public function lichsu() {
        require_once 'views/customer/lichsu.php';
        exit();
    }
    public function dichvu() {
        require_once 'views/customer/dichvu.php';
        exit();
    }
    public function hoso() {
        require_once 'views/customer/hoso.php';
        exit();
    }
} 