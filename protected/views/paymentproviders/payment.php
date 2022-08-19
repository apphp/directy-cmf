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

<h1><?php echo A::t('app', 'Test Checkout'); ?></h1>

<div class="bloc">
    <div class="title"><?php echo A::t('app', 'Payment Provider'); ?>: <?php echo CString::humanize($type); ?></div>
	<br>
		
    <div class="content">
		
		<fieldset>
			<legend>Payment Details</legend>
			<?php if($type == 'paypal'){ ?>
				<label>PayPal Email: </label> 	<?php echo $providerSettings->merchant_id; ?><br>
			<?php } ?>
			<label>Item Name: </label> 		<?php echo $product['item_name']; ?><br>
			<label>Item Number: </label> 	<?php echo $product['item_number']; ?><br>
			<label>Amount: </label> 		<?php echo $product['amount']; ?><br>
			<label>Currency: </label> 		<?php echo $product['currency_code']; ?><br>
		</fieldset>

		<fieldset>
			<legend>Customer Details</legend>
			<label>First Name: </label> 	<?php echo $product['first_name']; ?><br>
			<label>Last Name: </label> 		<?php echo $product['last_name']; ?><br>
			<label>Email: </label> 			<?php echo $product['email']; ?><br>
			<label>Phone: </label> 			<?php echo $product['phone']; ?><br>
		</fieldset>
		
		<fieldset>
			<legend>Billing Address Details</legend>
			<label>Address: </label> 		<?php echo $product['address1'].$product['address1']; ?><br>
			<label>City: </label> 			<?php echo $product['city']; ?><br>
			<label>Zip: </label> 			<?php echo $product['zip']; ?><br>
			<label>State: </label> 			<?php echo $product['state']; ?><br>
			<label>Country: </label> 		<?php echo $product['country']; ?><br>
		</fieldset>				
		<br>
		
		<?php if($type == 'paypal'){ ?>
		
			<?php 	
				echo $provider->drawPaymentForm(array(
					'merchant_id' 	=> $providerSettings->merchant_id,
					'item_name' 	=> $product['item_name'],
					'item_number' 	=> $product['item_number'],
					'amount'		=> $product['amount'],
					'custom'		=> '', 		// order ID
					'lc'			=> '', 		// country's language  
					'cn'			=> '', 		// If this variable is omitted, the default label above the note field is "Add special instructions to merchant."
					'rm'			=> '', 		// Return method. 0 – all shopping cart payments use the GET method, 1 – the buyer's browser is redirected to the return URL by using the GET method, but no payment variables are included, 2 – the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included
												// The rm variable takes effect only if the return variable is set.
					'currency_code'	=> $product['currency_code'], 	// The currency of the payment. The default is USD.
					'no_shipping'	=> '', 		// Do not prompt buyers for a shipping address.
					'address1'		=> $product['address1'],
					'address2'		=> $product['address2'],
					'city'			=> $product['city'],
					'zip'			=> $product['zip'],
					'state'			=> $product['state'],	
					'country'		=> $product['country'],
					'first_name'	=> $product['first_name'],
					'last_name'		=> $product['last_name'],
					'email'			=> $product['email'],
					'phone'			=> $product['phone'],
	
					'mode'			=> $providerSettings->mode,	
					'notify'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/handlePayment/paypal',	// IPN processing link
					'return'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testPaymentComplete',			// Return order link
					'cancel_return'	=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testCheckout',			// Cancel & return to site link
					'back'			=> $back,		// Back to Shopping Cart - defined by developer
				));
			?>
		
		<?php }else if($type == 'online_order'){ ?>
		
			<?php 	
				echo $provider->drawPaymentForm(array(
					'item_name' 	=> $product['item_name'],
					'item_number' 	=> $product['item_number'],
					'amount'		=> $product['amount'],
					'custom'		=> '', 		// order ID
					'lc'			=> '', 		// country's language  
					'cn'			=> '', 		// If this variable is omitted, the default label above the note field is "Add special instructions to merchant."
					'rm'			=> '', 		// Return method. 0 – all shopping cart payments use the GET method, 1 – the buyer's browser is redirected to the return URL by using the GET method, but no payment variables are included, 2 – the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included
												// The rm variable takes effect only if the return variable is set.
					'currency_code'	=> $product['currency_code'], 	// The currency of the payment. The default is USD.
					'no_shipping'	=> '', 		// Do not prompt buyers for a shipping address.
					'address1'		=> $product['address1'],
					'address2'		=> $product['address2'],
					'city'			=> $product['city'],
					'zip'			=> $product['zip'],
					'state'			=> $product['state'],	
					'country'		=> $product['country'],
					'first_name'	=> $product['first_name'],
					'last_name'		=> $product['last_name'],
					'email'			=> $product['email'],
					'phone'			=> $product['phone'],
	
					'mode'			=> $providerSettings->mode,	
					'notify'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/handlePayment/online_order',	// Payment processing link
					'return'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testPaymentComplete',				// Return order link
					'cancel_return'	=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testCheckout',				// Cancel & return to site link
					'back'			=> $back,		// Back to Shopping Cart - defined by developer
				));
			?>
		
		<?php }else if($type == 'online_credit_card'){ ?>
		
			<?php 	
				echo $provider->drawPaymentForm(array(
					'item_name' 	=> $product['item_name'],
					'item_number' 	=> $product['item_number'],
					'amount'		=> $product['amount'],
					'custom'		=> '', 		// order ID
					'lc'			=> '', 		// country's language  
					'cn'			=> '', 		// If this variable is omitted, the default label above the note field is "Add special instructions to merchant."
					'rm'			=> '', 		// Return method. 0 – all shopping cart payments use the GET method, 1 – the buyer's browser is redirected to the return URL by using the GET method, but no payment variables are included, 2 – the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included
												// The rm variable takes effect only if the return variable is set.
					'currency_code'	=> $product['currency_code'], 	// The currency of the payment. The default is USD.
					'no_shipping'	=> '', 		// Do not prompt buyers for a shipping address.
					'address1'		=> $product['address1'],
					'address2'		=> $product['address2'],
					'city'			=> $product['city'],
					'zip'			=> $product['zip'],
					'state'			=> $product['state'],	
					'country'		=> $product['country'],
					'first_name'	=> $product['first_name'],
					'last_name'		=> $product['last_name'],
					'email'			=> $product['email'],
					'phone'			=> $product['phone'],
	
					'mode'			=> $providerSettings->mode,	
					'notify'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/handlePayment/online_credit_card',	// Payment processing link
					'return'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testPaymentComplete',				// Return order link
					'cancel_return'	=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testCheckout',				// Cancel & return to site link
					'back'			=> $back,		// Back to Shopping Cart - defined by developer
				));
			?>

		<?php }else if($type == 'wire_transfer'){ ?>
		
			<?php 	
				echo $provider->drawPaymentForm(array(
					'item_name' 	=> $product['item_name'],
					'item_number' 	=> $product['item_number'],
					'amount'		=> $product['amount'],
					'custom'		=> '', 		// order ID
					'lc'			=> '', 		// country's language  
					'cn'			=> '', 		// If this variable is omitted, the default label above the note field is "Add special instructions to merchant."
					'rm'			=> '', 		// Return method. 0 – all shopping cart payments use the GET method, 1 – the buyer's browser is redirected to the return URL by using the GET method, but no payment variables are included, 2 – the buyer's browser is redirected to the return URL by using the POST method, and all payment variables are included
												// The rm variable takes effect only if the return variable is set.
					'currency_code'	=> $product['currency_code'], 	// The currency of the payment. The default is USD.
					'no_shipping'	=> '', 		// Do not prompt buyers for a shipping address.
					'address1'		=> $product['address1'],
					'address2'		=> $product['address2'],
					'city'			=> $product['city'],
					'zip'			=> $product['zip'],
					'state'			=> $product['state'],	
					'country'		=> $product['country'],
					'first_name'	=> $product['first_name'],
					'last_name'		=> $product['last_name'],
					'email'			=> $product['email'],
					'phone'			=> $product['phone'],
	
					'mode'			=> $providerSettings->mode,	
					'notify'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/handlePayment/wire_transfer',	// Payment processing link
					'return'		=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testPaymentComplete',				// Return order link
					'cancel_return'	=> A::app()->getRequest()->getBaseUrl().'paymentProviders/testCheckout',				// Cancel & return to site link
					'back'			=> $back,		// Back to Shopping Cart - defined by developer
				));
			?>

		<?php } ?>
		
    </div>
</div>
