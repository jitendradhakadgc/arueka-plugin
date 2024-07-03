<?php
$block = 'none';
$edit_addons = 'no';
global $post;

if (!empty($post)) {
    $product_id = $post->ID;
    foreach (ADDON_PREFIXES as $addon_prefix) {
        $serialized_data = get_post_meta($product_id, 'addon_data_' . $addon_prefix, true);
        if (!empty($serialized_data)) {
            $addon_data = unserialize($serialized_data);
            $block = 'block';
            $edit_addons = 'yes';
            break; // Exit the loop once the addon is found
        }
    }
}
?>
<div id="subscription_product_options" class="panel woocommerce_options_panel">
    <div class="gc-wrap">
	
	
	
	<!-- For clone only -->
		
		<div class="gc-settings-form-cont2" style="display:none;">
                        <div class="gc-top-panel2">
                            <div class="addon-name">Dynamic Addon Name</div>
                            <div class="gc-addon-actions">
                                <span class="show-hide-addon" onclick="toggleAddon(this)"><i
                                        class="fa-solid fa-caret-down gc-arrows-down"></i></span>
                            </div>
                        </div>

                        <div class="gc-dropdown-addon">

                            <select name="select-addon-dropdown[]" class="gc-addon-dropdown"
                                onchange="updateInputNames(this)" id="select-addon-dropdown">
                                <option value="0">Select Option</option>
                                <option value="additional_locations">Locations</option>
                                <option value="additional_staff">Staff</option>
                                <option value="additional_products">Products</option>
                                
                                <option value="delivery_management">Management</option>
                                
                                <option value="advertisement">Advertisement</option>
                                <option value="additional_services">Services</option>
                                <option value="additional_bookable_products">Bookable Products</option>
                            </select>

                            <select name="permission" class="gc-addon-dropdown2 gc-addon-permission">
                                <option value="" selected="selected" disabled>Select Permission</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="gc-input-row">
                            <div class="gc-input-rows">
                                <div class="gc-row">
                                    <h4>Tier 1</h4>
                                    <div class="tier-input-group">
                                        <label for="layer1_min_quantity" class="tier-label">Min Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer1_min_quantity"
                                            id="layer1_min_quantity" data-original-name="layer1_min_quantity"
                                            placeholder="Min Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer1_max_quantity" class="tier-label">Max Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer1_max_quantity"
                                            id="layer1_max_quantity" data-original-name="layer1_max_quantity"
                                            placeholder="Max Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer1_price" class="tier-label">Price:</label>
                                        <input type="text" class="gc-addon-input" name="layer1_price" id="layer1_price"
                                            data-original-name="layer1_price" placeholder="Price">
                                    </div>
                                </div>
                            </div>
                            <div class="gc-input-rows">
                                <div class="gc-row">
                                    <h4>Tier 2</h4>
                                    <div class="tier-input-group">
                                        <label for="layer2_min_quantity" class="tier-label">Min Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer2_min_quantity"
                                            id="layer2_min_quantity" data-original-name="layer2_min_quantity"
                                            placeholder="Min Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer2_max_quantity" class="tier-label">Max Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer2_max_quantity"
                                            id="layer2_max_quantity" data-original-name="layer2_max_quantity"
                                            placeholder="Max Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer2_price" class="tier-label">Price:</label>
                                        <input type="text" class="gc-addon-input" name="layer2_price" id="layer2_price"
                                            data-original-name="layer2_price" placeholder="Price">
                                    </div>
                                </div>
                            </div>
                            <div class="gc-input-rows">
                                <div class="gc-row">
                                    <h4>Tier 3</h4>
                                    <div class="tier-input-group">
                                        <label for="layer3_min_quantity" class="tier-label">Min Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer3_min_quantity"
                                            id="layer3_min_quantity" data-original-name="layer3_min_quantity"
                                            placeholder="Min Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer3_max_quantity" class="tier-label">Max Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer3_max_quantity"
                                            id="layer3_max_quantity" data-original-name="layer3_max_quantity"
                                            placeholder="Max Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer3_price" class="tier-label">Price:</label>
                                        <input type="text" class="gc-addon-input" name="layer3_price" id="layer3_price"
                                            data-original-name="layer3_price" placeholder="Price">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
		
		
		
        <!-- For clone only -->
	
	
	
	
	
        <?php if ($edit_addons == 'yes') { ?>
            <div id="gc-settings-form3" class="gc-settings-form-new">
                <?php
                foreach (ADDON_PREFIXES as $addon_prefix) {
                    $serialized_data = get_post_meta($product_id, 'addon_data_' . $addon_prefix, true);
                    if (!empty($serialized_data)) {
                        $addon_data = unserialize($serialized_data);
                        ?>
                        <div class="gc_append_here">
                            <div class="gc-settings-form-cont">
                                <div class="gc-top-panel">
                                    <div class="addon-name">Dynamic Addon Name</div>
                                    <div class="gc-addon-actions">
                                        <span class="remove-addon" onclick="removeAddon(this)"><i
                                                class="fa-solid fa-xmark"></i></span>
                                        <span class="show-hide-addon" onclick="toggleAddon(this)"><i
                                                class="fa-solid fa-caret-down gc-arrows-down"></i></span>
                                    </div>
                                </div>
                                <div class="gc-dropdown-addon">
                                    <select name="select-addon-dropdown[]" class="gc-addon-dropdown"
                                        onchange="updateInputNames(this)" id="select-addon-dropdown" required>
                                        <option value="0">Select Option</option>
                                        <option value="additional_locations" <?php if ($addon_prefix == "additional_locations") {
                                            echo 'selected';
                                        } ?>>Locations</option>
                                        <option value="additional_staff" <?php if ($addon_prefix == "additional_staff") {
                                            echo 'selected';
                                        } ?>>Staff</option>
                                        <option value="additional_products" <?php if ($addon_prefix == "additional_products") {
                                            echo 'selected';
                                        } ?>>Products</option>
                                        
                                        <option value="delivery_management" <?php if ($addon_prefix == "delivery_management") {
                                            echo 'selected';
                                        } ?>>Delivery Management</option>
                                        
                                        
                                        <option value="advertisement" <?php if ($addon_prefix == "advertisement") {
                                            echo 'selected';
                                        } ?>>
                                            Advertisement</option>
                                        <option value="additional_services" <?php if ($addon_prefix == "additional_services") {
                                            echo 'selected';
                                        } ?>>Services</option>
                                        <option value="additional_bookable_products" <?php if ($addon_prefix == "additional_bookable_products") {
                                            echo 'selected';
                                        } ?>>Bookable Products
										</option>
                                    
                                    </select>
                                    <select name="<?php echo $addon_prefix; ?>_permission"
                                        class="gc-addon-dropdown2 gc-addon-permission">
                                        <option value="" selected="selected" disabled>Select Permission</option>
                                        <option value="Yes" <?php if (get_post_meta($product_id, $addon_prefix . '_permission', true) === 'Yes') {
                                            echo 'selected';
                                        } ?>>Yes</option>
                                        <option value="No" <?php if (get_post_meta($product_id, $addon_prefix . '_permission', true) === 'No') {
                                            echo 'selected';
                                        } ?>>No</option>
                                    </select>
                                </div>
                                <div class="gc-input-row">
                                    <div class="gc-row">
                                        <h4>Tier 1</h4>
                                        <div id="tier-input-group">
                                            <label for="<?php echo $addon_prefix; ?>layer1_min_quantity" class="tier-label">Min
                                                Quantity</label>
                                            <input type="text" class="gc-addon-input"
                                                name="<?php echo $addon_prefix; ?>layer1_min_quantity"
                                                data-original-name="layer1_min_quantity"
                                                value="<?php echo isset($addon_data['layer1']['min_quantity']) ? $addon_data['layer1']['min_quantity'] : ''; ?>"
                                                placeholder="Min Quantity">
                                        </div>
                                        <div id="tier-input-group">
                                            <label for="<?php echo $addon_prefix; ?>layer1_max_quantity" class="tier-label">Max
                                                Quantity</label>
                                            <input type="text" class="gc-addon-input"
                                                name="<?php echo $addon_prefix; ?>layer1_max_quantity"
                                                data-original-name="layer1_max_quantity"
                                                value="<?php echo isset($addon_data['layer1']['max_quantity']) ? $addon_data['layer1']['max_quantity'] : ''; ?>"
                                                placeholder="Max Quantity">
                                        </div>
                                        <div id="tier-input-group">
                                            <label for="<?php echo $addon_prefix; ?>layer1_price" class="tier-label">Price</label>
                                            <input type="text" class="gc-addon-input"
                                                name="<?php echo $addon_prefix; ?>layer1_price" data-original-name="layer1_price"
                                                value="<?php echo isset($addon_data['layer1']['price']) ? $addon_data['layer1']['price'] : ''; ?>"
                                                placeholder="Price">
                                        </div>
                                    </div>
                                    <div class="gc-input-row">
                                        <div class="gc-row">
                                            <h4>Tier 2</h4>
                                            <div class="tier-input-group">
                                                <label for="<?php echo $addon_prefix; ?>layer2_min_quantity" class="tier-label">Min
                                                    Quantity</label>
                                                <input type="text" class="gc-addon-input"
                                                    name="<?php echo $addon_prefix; ?>layer2_min_quantity"
                                                    data-original-name="layer2_min_quantity"
                                                    value="<?php echo isset($addon_data['layer2']['min_quantity']) ? $addon_data['layer2']['min_quantity'] : ''; ?>"
                                                    placeholder="Min Quantity">
                                            </div>
                                            <div class="tier-input-group">
                                                <label for="<?php echo $addon_prefix; ?>layer2_max_quantity" class="tier-label">Max
                                                    Quantity</label>
                                                <input type="text" class="gc-addon-input"
                                                    name="<?php echo $addon_prefix; ?>layer2_max_quantity"
                                                    data-original-name="layer2_max_quantity"
                                                    value="<?php echo isset($addon_data['layer2']['max_quantity']) ? $addon_data['layer2']['max_quantity'] : ''; ?>"
                                                    placeholder="Max Quantity">
                                            </div>
                                            <div class="tier-input-group">
                                                <label for="<?php echo $addon_prefix; ?>layer2_price"
                                                    class="tier-label">Price</label>
                                                <input type="text" class="gc-addon-input"
                                                    name="<?php echo $addon_prefix; ?>layer2_price"
                                                    data-original-name="layer2_price"
                                                    value="<?php echo isset($addon_data['layer2']['price']) ? $addon_data['layer2']['price'] : ''; ?>"
                                                    placeholder="Price">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="gc-input-row">
                                        <div class="gc-row">
                                            <h4>Tier 3</h4>
                                            <div class="tier-input-group">
                                                <label for="<?php echo $addon_prefix; ?>layer3_min_quantity" class="tier-label">Min
                                                    Quantity</label>
                                                <input type="text" class="gc-addon-input"
                                                    name="<?php echo $addon_prefix; ?>layer3_min_quantity"
                                                    data-original-name="layer3_min_quantity"
                                                    value="<?php echo isset($addon_data['layer3']['min_quantity']) ? $addon_data['layer3']['min_quantity'] : ''; ?>"
                                                    placeholder="Min Quantity">
                                            </div>
                                            <div class="tier-input-group">
                                                <label for="<?php echo $addon_prefix; ?>layer3_max_quantity" class="tier-label">Max
                                                    Quantity</label>
                                                <input type="text" class="gc-addon-input"
                                                    name="<?php echo $addon_prefix; ?>layer3_max_quantity"
                                                    data-original-name="layer3_max_quantity"
                                                    value="<?php echo isset($addon_data['layer3']['max_quantity']) ? $addon_data['layer3']['max_quantity'] : ''; ?>"
                                                    placeholder="Max Quantity">
                                            </div>
                                            <div class="tier-input-group">
                                                <label for="<?php echo $addon_prefix; ?>layer3_price"
                                                    class="tier-label">Price</label>
                                                <input type="text" class="gc-addon-input"
                                                    name="<?php echo $addon_prefix; ?>layer3_price"
                                                    data-original-name="layer3_price"
                                                    value="<?php echo isset($addon_data['layer3']['price']) ? $addon_data['layer3']['price'] : ''; ?>"
                                                    placeholder="Price">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
                        </div>
                    <?php }
                }
                ?>
            </div>
            <button type="submit" name="gc-addon-submit" class="button button-primary">Save</button>

            <button type="button" class="button add-addon" onclick="addNewAddon(this)">Add New Addon</button>
        <?php } ?>
        <?php if ($edit_addons == 'no') {
            //echo 'i am here';
            ?>
            <!-- first time show  -->

            <div id="gc-settings-form2" class="gc-settings-form-new">
                <div class="gc_append_here" style="display:none">
                    <div class="gc-settings-form-cont">
                        <div class="gc-top-panel">
                            <div class="addon-name">Dynamic Addon Name</div>
                            <div class="gc-addon-actions">
                                <span class="remove-addon" onclick="removeAddon(this)"><i
                                        class="fa-solid fa-xmark"></i></span>
                                <span class="show-hide-addon" onclick="toggleAddon(this)"><i
                                        class="fa-solid fa-caret-down gc-arrows-down"></i></span>
                            </div>
                        </div>

                        <div class="gc-dropdown-addon">

                            <select name="select-addon-dropdown[]" class="gc-addon-dropdown"
                                onchange="updateInputNames(this)" id="select-addon-dropdown">
                                <option value="0">Select Option</option>
                                <option value="additional_locations">Locations</option>
                                <option value="additional_staff">Staff</option>
                                <option value="additional_products">Products</option>
                                
                                <option value="delivery_management">Management</option>
                                
                                <option value="advertisement">Advertisement</option>
                                <option value="additional_services">Services</option>
                                <option value="additional_bookable_products">Bookable Products</option>
                            </select>

                            <select name="permission" class="gc-addon-dropdown2 gc-addon-permission">
                                <option value="" selected="selected" disabled>Select Permission</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="gc-input-row">
                            <div class="gc-input-rows">
                                <div class="gc-row">
                                    <h4>Tier 1</h4>
                                    <div class="tier-input-group">
                                        <label for="layer1_min_quantity" class="tier-label">Min Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer1_min_quantity"
                                            id="layer1_min_quantity" data-original-name="layer1_min_quantity"
                                            placeholder="Min Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer1_max_quantity" class="tier-label">Max Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer1_max_quantity"
                                            id="layer1_max_quantity" data-original-name="layer1_max_quantity"
                                            placeholder="Max Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer1_price" class="tier-label">Price:</label>
                                        <input type="text" class="gc-addon-input" name="layer1_price" id="layer1_price"
                                            data-original-name="layer1_price" placeholder="Price">
                                    </div>
                                </div>
                            </div>
                            <div class="gc-input-rows">
                                <div class="gc-row">
                                    <h4>Tier 2</h4>
                                    <div class="tier-input-group">
                                        <label for="layer2_min_quantity" class="tier-label">Min Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer2_min_quantity"
                                            id="layer2_min_quantity" data-original-name="layer2_min_quantity"
                                            placeholder="Min Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer2_max_quantity" class="tier-label">Max Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer2_max_quantity"
                                            id="layer2_max_quantity" data-original-name="layer2_max_quantity"
                                            placeholder="Max Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer2_price" class="tier-label">Price:</label>
                                        <input type="text" class="gc-addon-input" name="layer2_price" id="layer2_price"
                                            data-original-name="layer2_price" placeholder="Price">
                                    </div>
                                </div>
                            </div>
                            <div class="gc-input-rows">
                                <div class="gc-row">
                                    <h4>Tier 3</h4>
                                    <div class="tier-input-group">
                                        <label for="layer3_min_quantity" class="tier-label">Min Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer3_min_quantity"
                                            id="layer3_min_quantity" data-original-name="layer3_min_quantity"
                                            placeholder="Min Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer3_max_quantity" class="tier-label">Max Quantity:</label>
                                        <input type="text" class="gc-addon-input" name="layer3_max_quantity"
                                            id="layer3_max_quantity" data-original-name="layer3_max_quantity"
                                            placeholder="Max Quantity">
                                    </div>
                                    <div class="tier-input-group">
                                        <label for="layer3_price" class="tier-label">Price:</label>
                                        <input type="text" class="gc-addon-input" name="layer3_price" id="layer3_price"
                                            data-original-name="layer3_price" placeholder="Price">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
                </div>
            </div>
            <button type="submit" name="gc-addon-submit" class="gc-save-addons-first button-primary"
                style="display:none">Save</button>
            <button type="button" class="button add-addon" onclick="addNewAddon2(this)">Add New Addon</button>
            <!-- first time show end -->
            <?php
        }
        ?>
        <!-- </form> -->
        
		
		
		
		
    </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
		$('.gc-addon-input').on('input', function() {
        // Remove non-numeric characters using a regular expression
        $(this).val($(this).val().replace(/[^0-9.]/g, ''));
    });
        $('button[name="gc-addon-submit"]').click(function(event) {
            var isValidAddon = true;
            var isValidLayer = true;

            // Add-on validation
            $('.gc-addon-input').each(function() {
                var inputValue = $(this).val();
                // Regular expression to match positive integers or decimal numbers
                if (parseFloat(inputValue) <= 0) {
                    isValidAddon = false;
                    return false;
                }
            });

            // Layer validation
            $('.gc-input-row').each(function() {
                var tier1Max = $(this).find('[name$="layer1_max_quantity"]').val();
                var tier2Min = $(this).find('[name$="layer2_min_quantity"]').val();
                var tier2Max = $(this).find('[name$="layer2_max_quantity"]').val();
                var tier3Min = $(this).find('[name$="layer3_min_quantity"]').val();

                // Convert to integers for comparison
                tier1Max = parseInt(tier1Max);
                tier2Min = parseInt(tier2Min);
                tier2Max = parseInt(tier2Max);
                tier3Min = parseInt(tier3Min);

                // Check if tier1Max is greater than or equal to tier2Min
                // and if tier2Max is greater than or equal to tier3Min
                if (tier1Max >= tier2Min || tier2Max >= tier3Min) {
                    isValidLayer = false;
                    return false; // Exit the loop early if validation fails
                }
            });

            if (!isValidAddon) {
                alert("Please make sure all input values are positive numbers.");
                event.preventDefault();
            } else if (!isValidLayer) {
                alert("Please ensure that the minimum quantity of each level is greater than the maximum quantity of the previous level.");
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });
    });
</script>


