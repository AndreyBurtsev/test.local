function addToCart(id){
	getQuantity(id);
	$.ajax({
		type: 'POST',
		async: false,
		url: 'ajax.php/?addtocart=' + id + '&quantity=' + quantity,
		dataType: 'json',
		success: function(data) {
			console.log(data);
			$('#count_in_cart').html(data['count_in_cart']);
			$('#count_in_cart_summ').html(data['count_in_cart_summ']);
			$('#addToCart_'+ id).hide();
			$('#delFromCart_'+ id).show();
			$('#cart_list').html(data['cart_list']);
			
		},
	});
}

function delFromCart(id){
	$.ajax({
		type: 'POST',
		async: false,
		url: 'ajax.php/?delfromcart=' + id,
		dataType: 'json',
		success: function(data) {
			console.log(data);
			$('#count_in_cart').html(data['count_in_cart']);
			$('#count_in_cart_summ').html(data['count_in_cart_summ']);
			$('#addToCart_'+ id).show();
			$('#delFromCart_'+ id).hide();
			$('#cart_list').html(data['cart_list']);
		},
	});
}

function clearCart(){
	$.ajax({
		type: 'POST',
		async: false,
		url: 'ajax.php/?clearcart=true',
		dataType: 'json',
		success: function(data) {
			console.log(data);
			$('#count_in_cart').html(0);
			$('#count_in_cart_summ').html(0);
			$('#cart_list').html(null);
			$('.addToCart').show();
			$('.delFromCart').hide();
		},
	});
}

function getQuantity(id){
	q = $('#addToCartQuantity_' + id);
	quantity = q[0]['value'];
	if (quantity < 0) quantity = 0;
}

function UpQuantity(id,price){
	getQuantity(id);
	quantity++;
	newPrice = price*quantity;
	$('#addToCartQuantity_' + id).val(quantity);
	$('#price_' + id).text(newPrice);
	
}

function DownQuantity(id,price){
	getQuantity(id);
	if (quantity > 1) quantity--;
	newPrice = price*quantity;
	$('#addToCartQuantity_' + id).val(quantity);
	$('#price_' + id).text(newPrice);
}

function saveOrder(){
	$.ajax({
		type: 'POST',
		async: false,
		url: 'ajax.php/?saveorder=true',
		dataType: 'json',
		success: function(data) {
			console.log(data);
			$('#count_in_cart').html(0);
			$('#count_in_cart_summ').html(0);
			$('#cart_list').html(null);
			$('.addToCart').show();
			$('.delFromCart').hide();
			alert("Ваш заказ №: " + data['order_id']);
		},
	});
}
