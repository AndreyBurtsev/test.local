<?
session_start();
//session_destroy();

function debug($var){
	echo '<pre>'."DEBUG: ";
	print_r ($var);
	echo '</pre>';
}	

$dbhost = "127.0.0.1";
$dbname = "test";
$dbuser = "root";
$dbpassword = "";

mysql_select_db($dbname,mysql_connect($dbhost,$dbuser,$dbpassword));
mysql_set_charset ('utf8');

function getProductData($id = null){
	$sqlQuery = 'SELECT * FROM `products`';
	if (isset ($id)) $sqlQuery .= ' WHERE id = '.$id;
	$sqlRowData = mysql_query ($sqlQuery);
	
	while ($sqlFetchData = mysql_fetch_assoc($sqlRowData)){
		$sqlData[] = $sqlFetchData;
	}
	return $sqlData;
}

function addToCart($id,$quantity){
	if (!$id) return false;
	$result = array();
	$product = getProductData($id)[0];
	$_SESSION['cart'][$id] = array();
	$_SESSION['cart'][$id]['name'] = $product['name'];
	$_SESSION['cart'][$id]['price'] = $product['price'];
	$_SESSION['cart'][$id]['summ'] = $product['price']*$quantity;
	$_SESSION['cart'][$id]['quantity'] = (!isset ($quantity) ? 1 : $quantity);
	$result['count_in_cart'] = countInCart();
	$result['count_in_cart_summ'] = countInCart('summ');
	$result['cart_list'] = showCartList();
	$result = json_encode($result);
	echo $result;
	
}

function delFromCart($id){
	if (!$id) return false;
	$result = array();
	unset ($_SESSION['cart'][$id]);
	$result['count_in_cart'] = countInCart() == null ? 0 : countInCart();
	$result['count_in_cart_summ'] = countInCart('summ') == null ? 0 : countInCart('summ');
	$result['cart_list'] = showCartList();
	$result = json_encode($result);
	echo $result;
}

function clearCart(){
	unset ($_SESSION['cart']);
	if (!$_SESSION['cart']) $result['success'] = 1;
	echo json_encode($result);
	return $result;
}

function countInCart ($i = 'quantity'){
	if (!$_SESSION['cart']) return;
	foreach ($_SESSION['cart'] as $value){
		$count_in_cart += $value['quantity'];
		$summ_in_cart += $value['summ'];
	}
	if ($i == 'summ') return $summ_in_cart;
	if ($i == 'quantity') return $count_in_cart;
	
}

function showCartList(){
	if (!$_SESSION['cart']) return;
	$a = '<ul>';
	foreach ($_SESSION['cart'] as $value){
		$a .= '<li>'.$value['name'].' | '.$value['quantity'].' шт | цена: '.$value['price'].' руб. | Сумма: '.$value['summ'].' руб.'.'</li>';
		$total += $value['summ'];
	}
	$a .= '</ul>';
	$a .= 'Итого: '.$total.' руб.';
	return ($a);
}

function saveOrder(){
	if (!$_SESSION['cart']) return false;
	$date = date('d.m.Y H:i:s');
	$sqlQuery = "INSERT INTO `orders` (`date`) VALUES ('".$date."')";
	$sqlRowData = mysql_query ($sqlQuery);
	if(!$sqlRowData) return false;
	
	$sqlQuery = 'SELECT `id` FROM `orders` ORDER BY `id` DESC LIMIT 1';
	$newId = mysql_fetch_assoc(mysql_query ($sqlQuery))['id'];
	if(!$newId) return false;
	
	foreach ($_SESSION['cart'] as $key => $value){
		$values[] = "('".$newId."','".$key."','".$value['price']."','".$value['quantity']."')";
		$mailValues[] = $value['name'].", ".$value['quantity']." шт., ".$value['price']." руб.\r\n";
	}
	$sqlQuery = "INSERT INTO `order_products` (`order_id`,`product_id`,`price`,`quantity`) VALUES " . implode($values,',');
	$sqlRowData = mysql_query ($sqlQuery);
	if(!$sqlRowData) return false;
	
	unset ($_SESSION['cart']);
	$result['order_id'] = $newId;
	echo json_encode($result);
	
	$to = 'flanchik@bk.ru'; 
	$from_name = 'Сообщение с '.$_SERVER['SERVER_NAME'];
	$from_email = 'sale@'.$_SERVER['SERVER_NAME'];
	$subject = "Заказ №:".$newId." оформлен";
	$message = "Состав заказа:\r\n-------------------------\r\n".implode($mailValues);
	$headers = "Content-Type: text/plain; charset=utf-8\r\n".'From: '.$from_name.' <'.$from_email.'>' . "\r\n" . 'Reply-To: ' . $from_email;
	mail($to, $subject, $message, $headers);
}
?>