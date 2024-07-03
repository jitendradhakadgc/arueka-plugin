<?php
	//Your HTML content
	$custom_html='';
	$vendor_Id='';
	$user='';
	if($vendor_data['business_email'][0]){
		$email = $vendor_data['business_email'][0];
		$user = get_user_by( 'email', $email );

	}
	if(empty($user)){
		exit;
	}
	$vendor_Id = $user->ID; 
	$current_user_email='';
	if ($vendor_Id) {
		//$current_user = wp_get_current_user();
		//$current_user_email = $current_user->user_email;
		$current_user_email = 'kapom83343@acentni.com'; 
	}
	// echo "sonali";

	$requests = get_user_meta( $vendor_Id, 'request-quote', true );
	$product_id = get_user_meta( $vendor_Id, 'request-quote-productid', true );
	
	if(empty($requests)){	
		echo "<p>No Request Found.</p>";
		exit;
	}
	

 if (wcs_user_has_subscription($vendor_id, '', 'active')) {
	$subscription = dokan()->vendor->get( $vendor_Id )->subscription;
	//$subscription = dokan()->vendor->get_vendor_subscription($vendor_id);
	?>
	<div class="seller_subs_info">
        <p>You are using <span><?php echo $subscription->get_package_title()?></span> package</p>
	</div>
	<?php
}
	
	
	//$product_id = $subscription->get_id();
	
	$product = wc_get_product($product_id);
	$product_content = get_post_field('post_content', $product_id);
	
	if ($product && $vendor_Id) {		
		?>
		<div class="gcc-card-parent">
		<div class="gcc-card">
				<div class="gc-enter-busi">
				<h2><span><i class="fa-solid fa-business-time"></i></span><?php echo $product->get_name();?></h2>
					<p><?php echo $product_content;?></p>
					<h3>Price:<?php echo $product->get_price_html();?></h3>
				</div>
			</div>
		</div>
		<?php
	}
						//$product = wc_get_product($product_id);
						$product = wc_get_product($product_id); ?>		
						<div class="gc-dokan-sub-addon" style="display:block;">
						<?php
						if ($product) {
							$addon_data = array();
							//ADDON_PREFIXES
							foreach ($requests as $request) {
								
								/* if(isset($request['productid'])){
									continue;
								} */
							?>	
						
                            <div class="gc-addon-container">
                                <div class="gc-addon">
                                    <div class="gc-addon-title" addon_title="<?php echo $request['addon_name'];?>">
                                        <p>Name:<?php echo $request['addon_name'];?></p>
                                    </div>
									
                                    
                                    <div class="gc-quantity-controls">
                                        <button type="button" class="gc-decrement1">-</button>
										<input type="number" value="<?php echo $request['quantity'];?>" class="gc-quantity-input Gc-quantity" name="number" max="" min="">
                                        <button type="button" class="gc-increment1">+</button>
										</div>
									<div class="gc-addon-price">
                                        <!--<p>Price: <span class="gc_change_price">50</span></p>-->
                                    <p>Price: <input type="number" class="gc_change_price" value="0"></p>
									</div>
									
									
                                    <input type="checkbox" class="Gc-add-to-cart Gc_add_check" value="yex" price="5" data-product-id="<?php echo $product_id;?>"></input>
									</div>
                            </div>
							<?php
							}
							}
							
							?>
							
							
							<button class="Gc_send_a_quote" type="button" vendor_email="<?php echo $email;?>">Send a Quote</button>
							</div>
	
	
	
	<!-- request a quote popup -->
	<div class="gcunique-overlay" id="gcunique-overlay"></div>
      <div class="gcunique-popup" id="gcunique-popup">
          <span class="gcunique-close-btn-x" onclick="hidegcuniquePopup()">X</span>
          <div class="gcunique-popup-content">
            <p class="gcunique-review">If you want to add more addons, please fill out the form below and admin will contact you.</p>
              <form id="gcunique-form" action="#" method="post">
                <label for="email">Email:</label>
                <input type="email" id="gcunique-email" name="email" required value="">
				<label for="gcunique-quantity-msg">Quantity:</label>
                <input type="text" id="gcunique-quantity-msg" class="gcunique-quantity-msg" name="quantity">
                
				<label for="gcunique-addon">Addon name:</label><input type="text" id="gcunique-addon" class="gcunique-quantity-msg" name="addon_name">
                <label for="gcunique-description">Description:</label>
                <textarea id="gcunique-description" name="gcunique-description" required></textarea>
                <input type="submit" value="Submit" name="request_quote">
              </form>           
             
              </div>
          </div>
