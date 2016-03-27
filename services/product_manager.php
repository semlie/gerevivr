<?php

require_once realpath(dirname(__FILE__)) . '/iproduct_manager.php';
require_once realpath(dirname(__FILE__)) . '/product_dataService.php';
require_once realpath(dirname(__FILE__)) . '/../models/product.php';

class product_manager implements iproduct_manager {

    private $productDataService;

    function __construct() {
        $this->productDataService = new product_dataService();
    }

    public function getProbuctById($productId) {
        $result = $this->productDataService->getById($productId);
        if (!empty($result)) {
            return $result;
        }
        return '';
    }
    
        public function GetProductByCatalogNumber($catalogNumber) {
        $result = $this->productDataService->GetProductByCatalogNumber($catalogNumber);
        if (!empty($result)) {
            $resultArray = $this->mapProductToArray($result);
            return $resultArray;
        }
    }

    private function mapProductToArray(product $product) {
        $row = array();
        $row[] = $product->FirstCategory;
        $row[] = $product->SecondaryCategory;

        if (!empty($product->Thickness)) {
            $row[] = 'thickness';
            $row[] = $product->Thickness;
        }
        if (!empty($product->Size)) {

            $row[] = 'size';
            $row[] = $product->Size;
        }

        if (!empty($product->Color)) {
            $row[] = 'color';
            $row[] = $product->Color;
        }
        
        if (!empty($product->Material)) {
            $row[] = 'material';
            $row[] = $product->Material;
        }
        
        if (!empty($product->Brand)) {
            $row[] = 'brand';
            $row[] = $product->Brand;
        }
        if (!empty($product->Example)) {

            $row[] = 'example';
            $row[] = $product->Example;
        }
        return $row;
    }

//put your code here
}
