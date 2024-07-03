
<form method="post" action="">
	<label for="store_name">Store Name:</label>
	<input type="text" id="store_name" name="store_name"
		value="<?php echo isset($vendor_data['store_name']) ? $vendor_data['store_name'][0] : ''; ?>" readonly>

	<label for="business_license">Business License:</label>
	<input type="text" id="business_license" name="business_license"
	value="<?php echo isset($vendor_data['business_license']) ? $vendor_data['business_license'][0] : ''; ?>"
	readonly>


    <!-- <label class="gc-business-hide" for="business_license">Business License Link:</label>
    <a href="#" class="gc-business-licence-link gc-business-hide">To check click here</a> -->

    <label class="gc-business-hide" for="business_license">Business License Link:</label>
    <a href="#" class="gc-business-licence-link gc-business-hide" target="_blank">To check click here</a>


	<label for="business_country">Business Country:</label>
	<input type="text" id="business_country" name="business_country"
		value="<?php echo isset($vendor_data['business_country']) ? $vendor_data['business_country'][0] : ''; ?>"readonly>

	<label for="business_city">Business City:</label>
	<input type="text" id="business_city" name="business_city"
		value="<?php echo isset($vendor_data['business_city']) ? $vendor_data['business_city'][0] : ''; ?>"
		readonly>

	<label for="business_settlement">Business Settlement:</label>
	<input type="text" id="business_settlement" name="business_settlement"
		value="<?php echo isset($vendor_data['business_settlement']) ? $vendor_data['business_settlement'][0] : ''; ?>"
		readonly>

	<label for="telephone_number" class="gc-business-hide">Telephone Number:</label>
	<input type="text" id="telephone_numberpop" class="gc-business-hide" name="telephone_number"
		value="<?php echo isset($vendor_data['telephone_number']) ? $vendor_data['telephone_number'][0] : ''; ?>"
		readonly>


	<label for="owner_first_name">Owner First Name:</label>
	<input type="text" id="owner_first_namepop" name="owner_first_name"
		value="<?php echo isset($vendor_data['owner_first_name']) ? $vendor_data['owner_first_name'][0] : ''; ?>"
		readonly>

	<label for="owner_last_name">Owner Last Name:</label>
	<input type="text" id="owner_last_namepop" name="owner_last_name"
		value="<?php echo isset($vendor_data['owner_last_name']) ? $vendor_data['owner_last_name'][0] : ''; ?>"
		readonly>

	<label for="business_email">Business Email:</label>
	<input type="email" id="business_emailpop" name="business_email"
		value="<?php echo isset($vendor_data['business_email']) ? $vendor_data['business_email'][0] : ''; ?>"
		readonly>

	<label for="business_telephone">Business Telephone:</label>
	<input type="text" id="business_telephone" name="business_telephone"
		value="<?php echo isset($vendor_data['business_telephone']) ? $vendor_data['business_telephone'][0] : ''; ?>"
		readonly>

	<label for="date_of_birth">Date of Birth:</label>
	<input type="text" id="date_of_birthpop" name="date_of_birth"
		value="<?php echo isset($vendor_data['gc_dob']) ? $vendor_data['gc_dob'][0] : ''; ?>" readonly>

	<?php
	$category_name = '';
	if (isset($vendor_data['industry'][0])) {
		//var_dump($vendor_data['industry'][0]);
		$category_id = $vendor_data['industry'][0];
		// Get the category object using the category ID
		$category = get_term($category_id, 'product_cat');

		// Check if category object is obtained successfully
		if ($category && !is_wp_error($category)) {
			// Get the category name
			$category_name = $category->name;
		}
	}
	?>
	<label for="industry">Industry:</label>
	<input type="text" id="gc-pop-industry" name="industry" value="<?php echo $category_name; ?>" readonly>
	
	<?php
	// Assuming $vendor_data['sub_categories'] contains the serialized array data
	if (isset($vendor_data['sub_categories'][0])) {
		$serialized_data = $vendor_data['sub_categories'][0]; // Extracting serialized data

		// Unserialize the data
		$unserialized_data = unserialize($serialized_data);

		// Check if unserialization was successful
		if ($unserialized_data !== false && is_array($unserialized_data)) {
			// Now you can access the values in a foreach loop
			?>
			<div class="gc_subcategory">
				<label for="gc_sub_category">subcategory:</label>
				<p class="gc_sub_category">
					<?php
					$i = 1;
					foreach ($unserialized_data as $value) {
						// Get the term object for the subcategory using its ID
						$subcategory = get_term($value, 'product_cat');
						// Check if term object is obtained successfully
						if ($subcategory && !is_wp_error($subcategory)) {
							// Get the subcategory name
	

							// Now you have the subcategory name
							?>
							<span class="">
								<?php echo $i . '. ' . $subcategory->name; ?><br>
							</span>
						<?php
						}
						$i++;
					}
					?>
				</p>
			</div>

			<?php
		}
	}

	?>

<div class="gc_img_sec">
    <label for="id_img_url">Id Card:</label>
    <img class="id_img_url_preview" src="<?php echo isset($vendor_data['id_img_url']) ? $vendor_data['id_img_url'][0] : ''; ?>" width="100" height="100" alt="id image">
</div>
	</br>
</form>
<div id="mail-message-pop"></div>
<div class="button-container">
    <div class="button-primary" id="send-mail-btn">Send Mail</div>
    <div class="gcunique-close-btn-pop button-primary">Close</div>
</div>

<style>
    .gcunique-form {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    max-width: 600px; /* Adjust max-width as needed */
    margin: 0 auto; /* Center the form horizontally */
}

.gcunique-form label {
    width: calc(40% - 10px); /* Adjust label width */
    margin-bottom: 10px; /* Add spacing between labels */
}

.gcunique-form input[type="text"],
.gcunique-form input[type="email"] {
    width: calc(55% - 10px); /* Adjust input width */
    padding: 5px;
    border: 1px solid #ccc; /* Add border for inputs */
}

.gc_img_sec {
    width: 100%;
    margin-bottom: 10px;
}

.gc_img_sec label {
    display: block;
    margin-bottom: 5px;
}

.gc_img_sec img {
    width: 100px; /* Adjust image width */
    height: 100px; /* Adjust image height */
    border: 1px solid #ccc; /* Add border for image */
    display: block; /* Ensure the image is displayed as a block element */
    margin-top: 5px; /* Add spacing above the image */
    margin-left: 45px;
}

.button-go-to-list {
    display: block;
    margin-top: 20px; /* Add spacing below the form */
}


.button-container {
    display: flex;
    justify-content: space-between; /* Align items to left and right */
}

.button-primary {
    padding: 10px 20px;
    cursor: pointer;
    background-color: #007bff; /* Primary color */
    color: #fff;
    border: none;
    border-radius: 5px;
}

.gcunique-close-btn-pop {
    padding: 10px 20px;
    cursor: pointer;
    background-color: #007bff; /* Primary color */
    color: #fff;
    border: none;
    border-radius: 5px;
}

#mail-message-pop {
    margin-top: 10px;
    margin-bottom: 10px;
    padding: 5px;
    border-radius: 5px;
}

#mail-message-pop.success {
    color: #155724;
}

#mail-message-pop.error {
    color: #721c24;
}
</style>