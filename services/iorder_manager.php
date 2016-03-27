<?php


interface IOrderManager {
    public function CreateNewOrder($callerId);
    public function AddNewItemForOrder($callerId,$orderId,$producrId,$quantity);
    public function CalculateOrder($orderId);
    
    
}
