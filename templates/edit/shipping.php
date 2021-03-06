<div class="dokan-form-horizontal">
    <input type="hidden" name="product_shipping_class" value="0">

    <div class="dokan-form-group show_if_simple">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php _e( 'Weight (kg)', 'dokan' ); ?></label>
        <div class="dokan-w4 dokan-text-left">
            <?php dokan_post_input_box( $post->ID, '_weight' ); ?>
        </div>
    </div>

    <div class="dokan-form-group show_if_simple">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php _e( 'Dimensions (cm)', 'dokan' ); ?></label>
        <div class="dokan-w8 dokan-text-left product-dimension">
            <?php dokan_post_input_box( $post->ID, '_length', array( 'class' => 'form-control col-sm-1', 'placeholder' => __( 'length', 'dokan' ) ), 'number' ); ?>
            <?php dokan_post_input_box( $post->ID, '_width', array( 'class' => 'form-control col-sm-1', 'placeholder' => __( 'width', 'dokan' ) ), 'number' ); ?>
            <?php dokan_post_input_box( $post->ID, '_height', array( 'class' => 'form-control col-sm-1', 'placeholder' => __( 'height', 'dokan' ) ), 'number' ); ?>
        </div>
    </div>
    
        
	<?php  /*
	$attr = wc_get_attribute_taxonomies();
	foreach($attr as $att){
		echo $att->attribute_label.'<br>';
	} */
	?>
    <!--<div class="dokan-form-group show_if_simple">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php _e( 'Shipping Class', 'dokan' ); ?></label>
        <div class="dokan-w8 dokan-text-left product-dimension">
            <?php
            // Shipping Class
            $classes = get_the_terms( $post->ID, 'product_shipping_class' );
            if ( $classes && ! is_wp_error( $classes ) ) {
                $current_shipping_class = current($classes)->term_id;
            } else {
                $current_shipping_class = '';
            }

            $args = array(
                'taxonomy'          => 'product_shipping_class',
                'hide_empty'        => 0,
                'show_option_none'  => __( 'No shipping class', 'dokan' ),
                'name'              => 'product_shipping_class',
                'id'                => 'product_shipping_class',
                'selected'          => $current_shipping_class,
                'class'             => 'dokan-form-control'
            );
            ?>

            <?php wp_dropdown_categories( $args ); ?>
            <p class="help-block"><?php _e( 'Shipping classes are used by certain shipping methods to group similar products.', 'dokan' ); ?></p>
        </div>
    </div> -->

    <?php do_action( 'dokan_product_options_shipping' ); ?>

</div>

