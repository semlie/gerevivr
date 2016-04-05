<?php
require_once realpath(dirname(__FILE__)) . '/../models/order_item.php';
require_once realpath(dirname(__FILE__)) . '/../models/order.php';
require_once realpath(dirname(__FILE__)) . '/order_manager.php';

class mail_service {
    private $orderManager;
            function __construct() {
        $this->orderManager = new order_manager();
    }

    //put your code here

    public function sendOrderToAdmin($order, $orderItems, $caller) {
        $msg = $this->msgTemplate($order,$orderItems);
        var_dump($msg);
        $this->sendEmail("israellieb@gmail.com", "israellieb@gmail.com", "new order {$order->ID}", $msg);
        
    }

    private function msgTemplate(order $order,$orderItems) {
        $mapItems = array_map(array($this, 'msgOrderItemArrayTemplate'), $orderItems);
        $msgHeader =  sprintf('<h1>order from line : %1$s </h1><hr> <p> total order : %2$s</p>'
                . '<p> total items : %3$s</p>'
                . '<p> total quntity : %4$s</p>',
                $order->CallerItemId,
                $order->TotalPrice,$order->TotalItems,$order->TotalQuantity);
        $msg = sprintf('<table style="width:100%">
            <tr>
              <td>productId</td>
              <td>quntitny</td> 
              <td>price</td>
            </tr>
            %1$s
          </table>',implode(" ", $mapItems));
        return $msgHeader.$msg;
    }

    private function msgOrderItemArrayTemplate(order_item $orderItem) {
        $result = $this->orderManager->getOrderItemTotalPrice($orderItem);
        $arr = sprintf( '<tr>
              <td>%1$s</td>
              <td>%2$s</td> 
              <td>%3$s</td>
            </tr>',$orderItem->ProductId,$orderItem->Quantity,$result['totalPrice']);
        return $arr;
    }

    
    private function sendEmail($to, $from, $subject, $message) {
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
