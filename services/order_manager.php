<?php

require_once realpath(dirname(__FILE__)) . '/iorder_manager.php';
require_once realpath(dirname(__FILE__)) . '/order_dataService.php';
require_once realpath(dirname(__FILE__)) . '/orderItem_dataService.php';
require_once realpath(dirname(__FILE__)) . '/../models/order.php';
require_once realpath(dirname(__FILE__)) . '/../models/order_item.php';
require_once realpath(dirname(__FILE__)) . '/product_manager.php';

class order_manager implements IOrderManager {

    private $orderDataService, $orderItemDataService, $productManager;

    function __construct() {
        $this->orderDataService = new order_dataService();
        $this->orderItemDataService = new orderItem_dataService();
        $this->productManager = new product_manager();
    }

    public function AddNewItemForOrder($callerId, $orderId, $producrId, $quantity) {
        $orderItem = new order_item();

        $orderItem->CollerId = $callerId;
        $orderItem->OrderId = $orderId;
        $orderItem->ProductId = $producrId;
        $orderItem->Quantity = $quantity;

        $this->orderItemDataService->Add($orderItem);
    }

    public function CalculateOrder($orderId) {
        $order = $this->UpdateOrderSum($orderId);
        return $order;
    }

    public function MapOrderTotal(order $order) {
        $row = array();

        $row[] = 'order-id';
        $row[] = $order->Id;

        $row[] = 'total-items';
        $row[] = $order->TotalItems;

        $row[] = 'total-price';
        $row[] = $order->TotalPrice;

        $row[] = 'total-quantity';
        $row[] = $order->TotalQuantity;

        return $row;
    }

    public function getOrderItemTotalPrice(order_item $item) {

        $product = $this->productManager->getProbuctById($item->ProductId);

        $totalPrice = ($product->Price * $item->Quantity);
        $totalQuntity = $item->Quantity;

        return array('totalPrice' => $totalPrice, 'totalQuntity' => $totalQuntity);
    }
    public function getOrderItems($orderId) {
        return $this->orderItemDataService->GetAllItemsOfOrder($orderId);
    }
    public function getOrderItemsPrinModel($orderId) {
        return $this->orderItemDataService->GetAllItemsOfOrderToPrintModel($orderId);
    }

    private function UpdateOrderSum($orderId) {
        $order = $this->orderDataService->getById($orderId);
        $allItems = $this->getOrderItems($orderId); // $this->orderItemDataService->GetAllItemsOfOrder($orderId);
        $totalPrice = 0;
        $totalQuntity = 0;
        $totalItems = count($allItems);
        if ($totalItems > 0) {
            foreach ($allItems as $item) {
                $result = $this->getOrderItemTotalPrice($item);
                
//                $product = $this->productManager->getProbuctById($item->ProductId);
//                $totalPrice = $totalPrice + ($product->Price * $item->Quantity);
//                $totalQuntity = $totalQuntity + $item->Quantity;
                $totalPrice = $totalPrice + $result['totalPrice'];
                $totalQuntity = $totalQuntity + $result['totalQuntity'];
            }
        }
        $order->TotalItems = $totalItems;
        $order->TotalPrice = $totalPrice;
        $order->TotalQuantity = $totalQuntity;

        $this->orderDataService->Update($order);
        return $order;
    }

    public function CreateNewOrder($callerId) {
        $order = new order();
        $order->CallerItemId = $callerId;

        $this->orderDataService->Add($order);
        if (!empty($order->Id)) {
            return $order->Id;
        }
    }

//put your code here
}
