<?php
    Website::setMetaTags(array('title'=>A::t('app', 'Test Checkout')));
	
	$product = array(
		'item_name' 	=> 'Product',
		'item_number' 	=> '002',
		'amount'		=> 9.90,
		'currency_code'	=> 'USD',
		'address1'		=> 'st. Big Street, 1',
		'address2'		=> '',	
		'city'			=> 'New York',	
		'zip'			=> '1001',
		'state'			=> 'NY',	
		'country'		=> 'us',
		'first_name'	=> 'John',
		'last_name'		=> 'Smith',
		'email'			=> 'j.smith@email.me',
		'phone'			=> '12345678',
	);
	
?>

<h1><?= A::t('app', 'Test Checkout'); ?></h1>

<div class="bloc">    
	<br>
		
    <div class="content">
		
		<fieldset>
			<legend>Product Details:</legend>
			<label>Item Name: </label> 		<?= $product['item_name']; ?><br>
			<label>Item Number: </label> 	<?= $product['item_number']; ?><br>
			<label>Amount: </label> 		<?= $product['amount']; ?><br>
			<label>Currency: </label> 		<?= $product['currency_code']; ?><br>
		</fieldset>
		<br>

		<form action="paymentProviders/testPayment">
			<fieldset>
				<legend>Payment Method:</legend>
				<select name="type">
					<?php
						if(is_array($providers)){
							foreach($providers as $key => $provider){
								echo '<option value="'.$provider['code'].'">'.$provider['name'].'</option>';	
							}						
						}
					?>
				</select>
			</fieldset>
			<br>
			
			<input type="submit" value="<?= A::t('app', 'Go To Payment'); ?>" />
		</form>
    </div>
</div>
