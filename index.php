<?require_once ('init.php');?>

<head>
<meta charset="utf-8">
<title>Интернет-магазин</title>
<script src="jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="script.js" type="text/javascript"></script>
</head>

<div style="width: 1000px; padding: 30 30 30 30; text-align: center;">
	<h1>Интернет-магазин</h1><hr>
	<div style="width: 400px; display: inline-block; float:left;">
		<b>Корзина</b>		
		<p>В корзине <span id="count_in_cart"><?echo !$_SESSION['cart'] ? 0 : countInCart('quantity');?></span> товаров на суммму <span id="count_in_cart_summ"><?echo !$_SESSION['cart'] ? 0 : countInCart('summ');?></span> руб.</p>
		
		<span id="cart_list" style="text-align:left"><?print_r (showCartList());?></span>
		<p>
			<button onclick="clearCart();">Очитстить</button>
			<button onclick="saveOrder();">Заказать</button>
		</p>
	</div>
	<div style="width: 600px; display: inline-block;">
		<b>Товары</b>
		<div>
		<?$arProducts = getProductData();
		foreach ($arProducts as $key => $value){?>
			<div style="float: left; margin-bottom: 20px;">
				<a href="#"><img src = 1.png><p style="margin-top: 0;"><?=$value['name'];?></p></a>
				<p>Цена: <span id="price_<?=$value['id']?>"><?=$value['price']?></span> руб. </p>
				
				<button id="DownQuantity_<?=$value['id']?>" onclick="DownQuantity(<?=$value['id']?>,<?=$value['price']?>);"> - </button>
				
				<input id="addToCartQuantity_<?=$value['id']?>" type="text" style="width: 25px; text-align: center;" value="1" onchange="getQuantity(<?echo $value['id']?>);">
				
				<button id="UpQuantity_<?=$value['id']?>" onclick="UpQuantity(<?=$value['id']?>,<?=$value['price']?>);"> + </button><br><br>
				
				<button id="addToCart_<?=$value['id']?>" class="addToCart" style="display:<? echo (isset($_SESSION['cart']) && array_key_exists($value['id'],$_SESSION['cart']) ?  'none' : '');?>" onclick="addToCart(<?=$value['id']?>,'<?=$value['name']?>');"> В корзину </button>
				<button id="delFromCart_<?=$value['id']?>" class="delFromCart" style="display:<? echo (isset($_SESSION['cart']) && array_key_exists($value['id'],$_SESSION['cart']) ?  '' : 'none');?>" onclick="delFromCart(<?=$value['id']?>);"> Выложить </button>
			</div>
			
		<?}?>

		</div>
	</div>
</div>
