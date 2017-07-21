<?
include_once('init.php');

if (isset($_GET['addtocart'])){
	addToCart($_GET['addtocart'],$_GET['quantity']);
}

if (isset($_GET['delfromcart'])){
	delFromCart($_GET['delfromcart']);
}

if (isset($_GET['clearcart'])){
	clearCart();
}

if (isset($_GET['saveorder'])){
	saveOrder();
}

?>