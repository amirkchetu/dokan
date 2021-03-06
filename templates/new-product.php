<div class="dokan-dashboard-wrap">
    <?php dokan_get_template( 'dashboard-nav.php', array( 'active_menu' => 'product' ) ); ?>

    <div class="dokan-dashboard-content">

        <div class="dokan-new-product-area">
            <?php if ( Dokan_Template_Shortcodes::$errors ) { ?>
                <div class="dokan-dashboard-content dokan-alert dokan-alert-danger">
                    <a class="dokan-close" data-dismiss="alert">&times;</a>

                    <?php foreach ( Dokan_Template_Shortcodes::$errors as $error) { ?>

                        <strong><?php _e( 'Error!', 'dokan' ); ?></strong> <?php echo $error ?>.<br>

                    <?php } ?>
                </div>
            <?php } ?>

            <?php

            $can_sell = apply_filters( 'dokan_can_post', true );

            if ( $can_sell ) {

                if ( dokan_is_seller_enabled( get_current_user_id() ) ) { ?>

                <form class="dokan-form-container" method="post">

                    <div class="row product-edit-container dokan-clearfix">
                        <div class="dokan-w4">
                            <div class="dokan-feat-image-upload">
                                <div class="instruction-inside">
                                    <input type="hidden" name="feat_image_id" class="dokan-feat-image-id" value="0">
                                    <i class="fa fa-cloud-upload"></i>
                                    <a href="#" class="dokan-feat-image-btn dokan-btn"><?php _e( 'Upload Product Image', 'dokan' ); ?></a>
                                </div>

                                <div class="image-wrap dokan-hide">
                                    <a class="close dokan-remove-feat-image">&times;</a>
                                        <img src="" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="dokan-w6">
                            <div class="dokan-form-group">
                                <input class="dokan-form-control" name="post_title" id="post-title" type="text" placeholder="<?php esc_attr_e( 'Product name..', 'dokan' ); ?>" value="<?php echo dokan_posted_input( 'post_title' ); ?>">
                            </div>

                            <div class="dokan-form-group">
                                <div class="dokan-input-group">
                                    <span class="dokan-input-group-addon">M.R.P. <?php echo get_woocommerce_currency_symbol(); ?></span>
                                    <input class="dokan-form-control" name="mrp" id="product-price" type="text" placeholder="9.99" value="<?php echo dokan_posted_input( 'price' ); ?>">
                                </div>
                            </div>
                            
                            <div class="dokan-form-group">
                                <div class="dokan-input-group">
                                    <span class="dokan-input-group-addon">List Price <?php echo get_woocommerce_currency_symbol(); ?></span>
                                    <input class="dokan-form-control" name="price" id="product-price" type="text" placeholder="9.99" value="<?php echo dokan_posted_input( 'price' ); ?>">
                                </div>
                            </div>

                            <div class="dokan-form-group">
                                <textarea name="post_excerpt" id="post-excerpt" rows="5" class="dokan-form-control" placeholder="<?php esc_attr_e( 'Short description about the product...', 'dokan' ); ?>"><?php echo dokan_posted_textarea( 'post_excerpt' ); ?></textarea>
                            </div>

                            <div class="dokan-form-group">
                            <?php
                            wp_dropdown_categories( array(
                                'show_option_none' => __( '- Select a category -', 'dokan' ),
                                'hierarchical'     => 1,
                                'hide_empty'       => 0,
                                'name'             => 'product_cat',
                                'id'               => 'product_cat',
                                'taxonomy'         => 'product_cat',
                                'title_li'         => '',
                                'class'            => 'product_cat dokan-form-control',
                                'exclude'          => '',
                                'selected'         => Dokan_Template_Shortcodes::$product_cat,
                            ) );
                            ?>
                            </div>
                        </div>
                    </div>

                    <!-- <textarea name="post_content" id="" cols="30" rows="10" class="span7" placeholder="Describe your product..."><?php echo dokan_posted_textarea( 'post_content' ); ?></textarea> -->
                    <div class="dokan-form-group">
                    	<span class="dokan-input-group-addon" style="background-color: #f5f5f5; border-bottom: 0 none; border-radius: 5px 5px 0 0;">Details about your product...</span>
                        <?php wp_editor( Dokan_Template_Shortcodes::$post_content, 'post_content', array('editor_height' => 50, 'quicktags' => false, 'media_buttons' => false, 'teeny' => true, 'editor_class' => 'post_content') ); ?>
                    </div>
                    
                    <?php //do_action( 'dokan_new_product_form' ); ?>

                    <div class="dokan-form-group">
                        <input type="submit" name="add_product" class="dokan-btn" value="<?php esc_attr_e( 'Add Product', 'dokan' ); ?>"/>
                    </div>

                </form>

                <?php } else { ?>

                    <?php dokan_seller_not_enabled_notice(); ?>

                <?php } ?>

            <?php } else { ?>

                <?php do_action( 'dokan_can_post_notice' ); ?>

            <?php } ?>
    </div> <!-- #primary .content-area -->
</div><!-- .dokan-dashboard-wrap -->