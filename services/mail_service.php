<?php
require_once realpath(dirname(__FILE__)) . '/../models/order_item.php';
require_once realpath(dirname(__FILE__)) . '/../models/order_item_print.php';
require_once realpath(dirname(__FILE__)) . '/../models/order.php';
require_once realpath(dirname(__FILE__)) . '/order_manager.php';

class mail_service {
    private $orderManager;
            function __construct() {
        $this->orderManager = new order_manager();
    }

    //put your code here

    public function sendOrderToAdmin($cidNumber,$order, $orderItems, $caller) {
        $msg = $this->msgTemplate($cidNumber,$order,$orderItems);
        var_dump($msg);
        $this->sendEmail("israellieb@gmail.com", "israellieb@gmail.com", "new order {$order->Id}", $msg);
        $this->sendEmail("arieh.global4less@gmail.com", "israellieb@gmail.com", "new order {$order->Id}", $msg);
        
    }

    private function msgTemplate($cidNumber ,order $order,$orderItems) {
        $mapItems = array_map(array($this, 'msgOrderItemArrayTemplate'), $orderItems);
        $msgHeader =  sprintf('<h1>order from line : %1$s </h1><hr> <p> total order : %2$s</p>'
                . '<p> total items : %3$s</p>'
                . '<p> total quntity : %4$s</p>',
                $cidNumber['PhoneNumber'],
                $order->TotalPrice,$order->TotalItems,$order->TotalQuantity);
        $msg = sprintf('<table border="1" style="width:100%%">
            <tr>
              <td>Product CatalogNumber</td>
              <td>Product Name</td>
              <td>Quntitny</td> 
              <td>Unit Price</td>
              <td>Price</td>
            </tr>
            %1$s
          </table>',implode(" ", $mapItems));
        return $msgHeader.$msg;
    }

    private function msgOrderItemArrayTemplate(order_item_print $orderItem) {
        $arr = sprintf( '<tr>
              <td>%1$s</td>
              <td>%2$s</td> 
              <td>%3$s</td>
              <td>%4$s</td>
              <td>%5$s</td>
            </tr>',$orderItem->ProductId,$orderItem->ProductName,$orderItem->Quantity,$orderItem->PriceUnit,$orderItem->PriceOrderItem);
        return $arr;
    }

    
    private function sendEmail($to, $from, $subject, $msg) {
        $message = "
            <html>
            <head>
            <title>new order</title>
            </head>
            <body>
            {$msg}
            </body>
            </html>
            ";

// Always set content-type when sending HTML email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
        $headers .= "From: " . $from . "\r\n";
        mail($to, $subject, $message, $headers);
    }

}
