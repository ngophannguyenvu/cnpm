<?php
require_once 'app/config/database.php';
//require_once 'app/models/ProductModel.php';
//require_once 'app/models/CategoryModel.php';
require_once 'app/helpers/SessionHelper.php';
class DefaultController
{
    private $productModel;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        //$this->productModel = new ProductModel($this->db);
    }
  
    public function index()
    {
        //echo "Trang chính đang xây dựng. Chưa có view.";
    }
}