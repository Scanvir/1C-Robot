<?php

class Cart {
    
    public static function addToCart($branchCode, $productCode) {
        //пустий масив для корзини
        $cart = [];

        //якщо в корзині уже є які небудь товари(вони зберігаються в сесії)
        if (isset($_SESSION['products'])) {
            // То заповнемо наш масив товарами
            $cart = $_SESSION['products'];
        }

        if (array_key_exists($branchCode, $cart)) {
            $productsInCart = $cart[$branchCode];
            if (array_key_exists($productCode, $cart[$branchCode])) {
                $cart[$branchCode][$productCode] ++;
            } else {
                $cart[$branchCode][$productCode] = 1;
            }
        } else {
            $productsInCart = [];
            $productsInCart[$productCode] = 1;
            
            $cart[$branchCode] = $productsInCart;
        }

        //зберігаєм корзину в сесії
        $_SESSION['products'] = $cart;
    }
    public static function countCarts() {
        
        //return 1;
        
        if (isset($_SESSION['products'])) {
            $count = 0;
            foreach ($_SESSION['products'] as $quantity) {
                $count = $count + 1;
            }
            return $count;
        } else {
            return 0;
        }
    }
    public static function getCart() {
        if (isset($_SESSION['products'])) {
            return $_SESSION['products'];
        }
        return [];
    }
    public static function reserveCart($branchCode, $userId, $codeVR) {
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];
            if(array_key_exists($branchCode, $productsInCart))
                if ($productsInCart[$branchCode]){
                    foreach ($productsInCart[$branchCode] as $productCode => $qty){
                        $tab[] = ['product' => $productCode, 'qty' => $qty];
                    }
                    $body = ['Branch' => $branchCode, 'userId' => $userId, 'codeVR' => $codeVR, 'tab' => $tab];
                    
                    $body = json_encode($body, JSON_UNESCAPED_UNICODE );
                    
                    $resutl = self::ReserveToDB($body);
                    if ($resutl[0]){
                        unset($productsInCart[$branchCode]);
                        $_SESSION['products'] = $productsInCart;
                        return true;
                    }
                }
        }
        return false;
    }
    public static function ReserveToDB($body) {
        $endpoint = 'https://api.bilaromashka.com.ua/reserve/reserve/new.php';
        
        $headers = [];
		$headers[] = "Authorization: Basic YmlsYXJvbWFzaGxhLmNvbS51YToyMDA4MjQwOHdlYkFjY2Vzcw==";
		$headers[] = 'accept: application/json';

        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
		    echo 'Error:' . curl_error($ch);
		}
		return $result;
        curl_close($ch);
        $data = json_decode($result, true);
        
		return $data;
    }
    
    public static function plusCart($branchCode, $productCode) {
        //$rest = Product::getProductRest($productCode, $branchCode);
        
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];
            $productsInCart[$branchCode][$productCode] ++;
            
            //if((float)$rest['qty'] < (float)$productsInCart[$branchCode][$productCode])
              //  $productsInCart[$branchCode][$productCode] = (float)$rest['qty'];
            $_SESSION['products'] = $productsInCart;
            
            return $productsInCart[$branchCode][$productCode];
        }
        return 0;
    }
    public static function minusCart($branchCode, $productCode) {
        $product = Product::getProductByCode($productCode);
        $delivery = (float)$product['Delivery'];
        if ($delivery == 0)
            $delivery = 1;
        
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];
            if($productsInCart[$branchCode][$productCode] > 1)
                $productsInCart[$branchCode][$productCode] --;
            else
                $productsInCart[$branchCode][$productCode] = $delivery;
            if($delivery > (float)$productsInCart[$branchCode][$productCode])
                $productsInCart[$branchCode][$productCode] = $delivery;
            $_SESSION['products'] = $productsInCart;
            
            return $productsInCart[$branchCode][$productCode];
        }
        return 0;
    }
    public static function plusCartdel($branchCode, $productCode) {
        $product = Product::getProductByCode($productCode);
        $delivery = (float)$product['Delivery'];
        //$rest = Product::getProductRest($productCode, $branchCode);
        
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];
            $productsInCart[$branchCode][$productCode] += $delivery;
            //if((float)$rest['qty'] < (float)$productsInCart[$branchCode][$productCode])
                //$productsInCart[$branchCode][$productCode] = (float)$rest['qty'];
            $_SESSION['products'] = $productsInCart;
            
            return $productsInCart[$branchCode][$productCode];
        }
        return 0;
    }
    public static function minusCartdel($branchCode, $productCode) {
        $product = Product::getProductByCode($productCode);
        $delivery = (float)$product['Delivery'];
        
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];
            if($productsInCart[$branchCode][$productCode] > $delivery)
                $productsInCart[$branchCode][$productCode] -= $delivery;
            if($delivery > (float)$productsInCart[$branchCode][$productCode])
                $productsInCart[$branchCode][$productCode] = $delivery;
            $_SESSION['products'] = $productsInCart;
            
            return $productsInCart[$branchCode][$productCode];
        }
        return 0;
    }
    public static function clearCart($branchCode) {
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];

            
            
            unset($productsInCart[$branchCode]);

            $_SESSION['products'] = $productsInCart;
        }
    }
    
    public static function deleteProduct($id) {
        if (isset($_SESSION['products']))
        {
            $productsInCart = $_SESSION['products'];

            if (array_key_exists($id, $productsInCart)) {
                if ($productsInCart[$id] > 1) {
                    $productsInCart[$id] --;
                } else {
                    unset($productsInCart[$id]);
                }
            }
            $_SESSION['products'] = $productsInCart;
        }
    }
    public static function canselReserve($id) {
		$endpoint = 'https://api.bilaromashka.com.ua/reserve/reserve/cancel.php?id='.$id;
		return Web::webGet($endpoint);
    }
    public static function ReserveFromDB($userId) {
        $endpoint = 'https://api.bilaromashka.com.ua/reserve/reserve/list.php?userId='.$userId;
		return Web::webGet($endpoint);
    }
   
    
}