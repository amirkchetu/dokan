<div class="dokan-form-horizontal">
    <div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_purchase_note"><?php _e( 'Purchase Note', 'dokan' ); ?></label>
        <div class="dokan-w6 dokan-text-left">
            <?php dokan_post_input_box( $post->ID, '_purchase_note', array(), 'textarea' ); ?>
        </div>
    </div>
    
    
    <div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php _e( 'Shipping Type', 'dokan' ); ?></label>
        <div class="dokan-w8 dokan-text-left product-dimension">
        
            <?php dokan_post_input_box( $post->ID, '_shipping_type', array( 'options' => array(
                'fs' => __( 'Free Shipping', 'dokan' ),
                'frs' => __( 'Fixed Rate Shipping', 'dokan' )
            ) ), 'select' ); ?>
        </div>
    </div>
    
    <?php if($_shipping_type=="frs") {?>
	<script type="text/javascript">
	  jQuery(function($) {		 
		  $('#_shipping_type').change(function(){
			  if($('#_shipping_type').val() == 'frs') {
				  $('#frs_selected').show(); 
			  } else {
				  $('#frs_selected').hide(); 
			  } 
		  });
	  });
	</script>
	<?php } else { ?>
	<script type="text/javascript">
	  jQuery(function($) {
		  $('#frs_selected').hide(); 
		  $('#_shipping_type').change(function(){
			  if($('#_shipping_type').val() == 'frs') {
				  $('#frs_selected').show(); 
			  } else {
				  $('#frs_selected').hide(); 
			  } 
		  });
	  });
	</script>
	<?php }?>
    <div id="frs_selected" class="dokan-clearfix">
        <label class="dokan-w4 dokan-control-label" for="_backorders"><?php _e( 'Fixed Shipping', 'dokan' ); ?></label>
        <div class="dokan-form-group">
            <div class="dokan-input-group">
                <span class="dokan-input-group-addon">Cost. <?php echo get_woocommerce_currency_symbol(); ?></span>
                <?php dokan_post_input_box( $post_id, '_flat_shipping_cost', array( 'placeholder' => '9.99' ) ); ?>
            </div>
        </div>
    </div>

    
	<?php $_enable_reviews = ( $post->comment_status == 'open' ) ? 'yes' : 'no'; ?>
    <?php dokan_post_input_box( $post->ID, '_enable_reviews', array('value' => 'yes', 'label' => __( 'Enable Reviews', 'dokan' ) ), 'hidden' ); ?>
    <?php dokan_post_input_box( $post->ID, '_visibility', array( 'value' =>'visible' ), 'hidden' ); ?>

    <div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_enable_reviews"><?php _e( 'Sold Individually', 'dokan' ); ?></label>
        <div class="dokan-w7 dokan-text-left">
            <?php dokan_post_input_box( $post->ID, '_sold_individually', array('label' => __( 'Allow one of this item to be bought in a single order', 'dokan' ) ), 'checkbox' ); ?>
        </div>
    </div>
</div> <!-- .form-horizontal -->