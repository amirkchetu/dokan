<?php
/**
 * Dokan settings Class
 *
 * @author weDves
 */
class Dokan_Template_Settings {

    public static function init() {
        static $instance = false;

        if ( !$instance ) {
            $instance = new Dokan_Template_Settings();
        }

        return $instance;
    }

    /**
     * Save settings via ajax
     *
     * @return void
     */
    function ajax_settings() {

        if ( ! dokan_is_user_seller( get_current_user_id() ) ) {
            wp_send_json_error( __( 'Are you cheating?', 'dokan' ) );
        }

        $_POST['dokan_update_profile'] = '';

        switch( $_POST['form_id'] ) {
            case 'profile-form':
                if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_profile_settings_nonce' ) ) {
                    wp_send_json_error( __( 'Are you cheating?', 'dokan' ) );
                }
                $ajax_validate =  $this->profile_validate();
                break;
            case 'store-form':
                if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_store_settings_nonce' ) ) {
                    wp_send_json_error( __( 'Are you cheating?', 'dokan' ) );
                }
                $ajax_validate =  $this->store_validate();
                break;
            case 'payment-form':
                if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_payment_settings_nonce' ) ) {
                    wp_send_json_error( __( 'Are you cheating?', 'dokan' ) );
                }
                $ajax_validate =  $this->payment_validate();
                break;
        }

        if ( is_wp_error( $ajax_validate ) ) {
            wp_send_json_error( $ajax_validate->errors );
        }

        // we are good to go
        $save_data = $this->insert_settings_info();

        $progress_bar = dokan_get_profile_progressbar();
        $success_msg = __( 'Your information has been saved successfully', 'dokan' ) ;

        $data = array(
            'progress' => $progress_bar,
            'msg'      => $success_msg,
        );

        wp_send_json_success( $data );
    }

    /**
     * Validate settings submission
     *
     * @return void
     */
    function validate() {

        if ( !isset( $_POST['dokan_update_profile'] ) ) {
            return false;
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_settings_nonce' ) ) {
            wp_die( __( 'Are you cheating?', 'dokan' ) );
        }

        $error = new WP_Error();

        $dokan_name = sanitize_text_field( $_POST['dokan_store_name'] );

        /*if ( empty( $dokan_name ) ) {
            $error->add( 'dokan_name', __( 'Dokan name required', 'dokan' ) );
        }

        if ( isset( $_POST['setting_category'] ) ) {

            if ( !is_array( $_POST['setting_category'] ) || !count( $_POST['setting_category'] ) ) {
                $error->add( 'dokan_type', __( 'Dokan type required', 'dokan' ) );
            }
        }

        if ( !empty( $_POST['setting_paypal_email'] ) ) {
            $email = filter_var( $_POST['setting_paypal_email'], FILTER_VALIDATE_EMAIL );
            if ( empty( $email ) ) {
                $error->add( 'dokan_email', __( 'Invalid email', 'dokan' ) );
            }
        }*/

        /* Address Fields Validation */
        $required_fields  = array(
            'street_1',
            'city',
            'zip',
            'country',
        );
        if ( $_POST['dokan_address']['state'] != 'N/A' ) {
            $required_fields[] = 'state';
        }
        foreach ( $required_fields as $key ) {
            if ( empty( $_POST['dokan_address'][$key] ) ) {
                $code = 'dokan_address['.$key.']';
                $error->add( $code, sprintf( __('Address field for %s is required','dokan'), $key ) );
            }
        }


        if ( $error->get_error_codes() ) {
            return $error;
        }

        return true;
    }

    /**
     * Validate profile settings
     *
     * @return bool|WP_Error
     */
    function profile_validate() {

        if ( !isset( $_POST['dokan_update_profile_settings'] ) ) {
            return false;
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_profile_settings_nonce' ) ) {
            wp_die( __( 'Are you cheating?', 'dokan' ) );
        }

        $error = new WP_Error();

        if ( isset( $_POST['setting_category'] ) ) {

            if ( !is_array( $_POST['setting_category'] ) || !count( $_POST['setting_category'] ) ) {
                $error->add( 'dokan_type', __( 'Dokan type required', 'dokan' ) );
            }
        }

        if ( !empty( $_POST['setting_paypal_email'] ) ) {
            $email = filter_var( $_POST['setting_paypal_email'], FILTER_VALIDATE_EMAIL );

            if ( empty( $email ) ) {
                $error->add( 'dokan_email', __( 'Invalid email', 'dokan' ) );
            }
        }

        if ( $error->get_error_codes() ) {
            return $error;
        }

        return true;
    }

    /**
     * Validate store settings
     *
     * @return bool|WP_Error
     */
    function store_validate() {

        if ( !isset( $_POST['dokan_update_store_settings'] ) ) {
            return false;
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_store_settings_nonce' ) ) {
            wp_die( __( 'Are you cheating?', 'dokan' ) );
        }

        $error = new WP_Error();

        $dokan_name = sanitize_text_field( $_POST['dokan_store_name'] );

        if ( empty( $dokan_name ) ) {
            $error->add( 'dokan_name', __( 'Dokan name required', 'dokan' ) );
        }

        if ( isset( $_POST['setting_category'] ) ) {

            if ( !is_array( $_POST['setting_category'] ) || !count( $_POST['setting_category'] ) ) {
                $error->add( 'dokan_type', __( 'Dokan type required', 'dokan' ) );
            }
        }

        if ( !empty( $_POST['setting_paypal_email'] ) ) {
            $email = filter_var( $_POST['setting_paypal_email'], FILTER_VALIDATE_EMAIL );
            if ( empty( $email ) ) {
                $error->add( 'dokan_email', __( 'Invalid email', 'dokan' ) );
            }
        }

        if ( $error->get_error_codes() ) {
            return $error;
        }

        return true;

    }

    /**
     * validate payment settings
     * @return bool|WP_Error
     */
    function payment_validate() {

        if ( !isset( $_POST['dokan_update_payment_settings'] ) ) {
            return false;
        }

        if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dokan_payment_settings_nonce' ) ) {
            wp_die( __( 'Are you cheating?', 'dokan' ) );
        }

        $error = new WP_Error();


        if ( !empty( $_POST['setting_paypal_email'] ) ) {
            $email = filter_var( $_POST['setting_paypal_email'], FILTER_VALIDATE_EMAIL );
            if ( empty( $email ) ) {
                $error->add( 'dokan_email', __( 'Invalid email', 'dokan' ) );
            }
        }

        if ( $error->get_error_codes() ) {
            return $error;
        }

        return true;

    }

    /**
     * Save store settings
     *
     * @return void
     */
    function insert_settings_info() {

        $store_id            = get_current_user_id();
        $prev_dokan_settings = get_user_meta( $store_id, 'dokan_profile_settings', true );

        if ( wp_verify_nonce( $_POST['_wpnonce'], 'dokan_profile_settings_nonce' ) ) {

            // update profile settings info
            $social         = $_POST['settings']['social'];
            $social_fields  = dokan_get_social_profile_fields();
            $dokan_settings = array( 'social' => array() );

            if ( is_array( $social ) ) {
                foreach ($social as $key => $value) {
                    if ( isset( $social_fields[ $key ] ) ) {
                        $dokan_settings['social'][ $key ] = filter_var( $social[ $key ], FILTER_VALIDATE_URL );
                    }
                }
            }

        } elseif ( wp_verify_nonce( $_POST['_wpnonce'], 'dokan_store_settings_nonce' ) ) {

            //update store setttings info
            $dokan_settings = array(
                'store_name'   => sanitize_text_field( $_POST['dokan_store_name'] ),
                'address'      => isset( $_POST['dokan_address'] ) ? $_POST['dokan_address'] : array(),
                'location'     => sanitize_text_field( $_POST['location'] ),
                'find_address' => sanitize_text_field( $_POST['find_address'] ),
                'banner'       => absint( $_POST['dokan_banner'] ),
                'phone'        => sanitize_text_field( $_POST['setting_phone'] ),
                'show_email'   => sanitize_text_field( $_POST['setting_show_email'] ),
                'gravatar'     => absint( $_POST['dokan_gravatar'] ),
                'enable_tnc'   => isset( $_POST['dokan_store_tnc_enable'] ) ? $_POST['dokan_store_tnc_enable'] : '',
                'store_tnc'    => isset( $_POST['dokan_store_tnc'] ) ? $_POST['dokan_store_tnc']: ''
            );

        } elseif ( wp_verify_nonce( $_POST['_wpnonce'], 'dokan_payment_settings_nonce' ) ) {

            //update payment settings info
            $dokan_settings = array(
                'payment'      => array(),
            );

            if ( isset( $_POST['settings']['bank'] ) ) {
                $bank = $_POST['settings']['bank'];

                $dokan_settings['payment']['bank'] = array(
                    'ac_name'   => sanitize_text_field( $bank['ac_name'] ),
                    'ac_number' => sanitize_text_field( $bank['ac_number'] ),
                    'bank_name' => sanitize_text_field( $bank['bank_name'] ),
                    'bank_addr' => sanitize_text_field( $bank['bank_addr'] ),
                    'swift'     => sanitize_text_field( $bank['swift'] ),
                );
            }

            if ( isset( $_POST['settings']['paypal'] ) ) {
                $dokan_settings['payment']['paypal'] = array(
                    'email' => filter_var( $_POST['settings']['paypal']['email'], FILTER_VALIDATE_EMAIL )
                );
            }

            if ( isset( $_POST['settings']['skrill'] ) ) {
                $dokan_settings['payment']['skrill'] = array(
                    'email' => filter_var( $_POST['settings']['skrill']['email'], FILTER_VALIDATE_EMAIL )
                );
            }

        }
        
        $dokan_settings = array(
            'legal_stat'	=> $_POST['legal_stat'],
			'firm_name'		=> $_POST['firm_name'],
			'add_line_1'	=> $_POST['add_line_1'],
			'add_line_2'	=> $_POST['add_line_2'],
			'pin_code'		=> $_POST['pin_code'],
			'state'			=> $_POST['state'],
			'city'			=> $_POST['city'],
			'padd_line_1'	=> $_POST['padd_line_1'],
			'padd_line_2'	=> $_POST['padd_line_2'],
			'ppin_code'		=> $_POST['ppin_code'],
			'pstate'		=> $_POST['pstate'],
			'pcity'			=> $_POST['pcity'],
			'pan_no'		=> $_POST['pan_no'],
			'vat_no'		=> $_POST['vat_no'],
			'billing_phone'	=> $_POST['billing_phone'],
			'bus_cat'		=> $_POST['bus_cat'],
            'store_name'    => $_POST['dokan_store_name'],			
			'full_name'		=> $_POST['full_name'],
			'plans'			=> $_POST['plans'],
			'agreement1'	=> $_POST['agreement1'],
			'mou'			=> $_POST['mou'],
			'toa'			=> $_POST['toa'],
			'sfp'			=> $_POST['sfp']
                );

        $dokan_settings = array_merge($prev_dokan_settings,$dokan_settings);

        $profile_completeness = $this->calculate_profile_completeness_value( $dokan_settings );
        $dokan_settings['profile_completion'] = $profile_completeness;

        update_user_meta( $store_id, 'dokan_profile_settings', $dokan_settings );
        
        //Custom Update
        update_user_meta( $store_id, 'billing_company', $dokan_settings['firm_name'] );
        update_user_meta( $store_id, 'billing_address_1', $dokan_settings['add_line_1'] );
        update_user_meta( $store_id, 'billing_address_2', $dokan_settings['add_line_1'] );
        update_user_meta( $store_id, 'billing_postcode', $dokan_settings['pin_code'] );
        update_user_meta( $store_id, 'billing_city', $dokan_settings['city'] );
        update_user_meta( $store_id, 'billing_state', $dokan_settings['state'] );
        update_user_meta( $store_id, 'shipping_address_1', $dokan_settings['padd_line_1'] );
        update_user_meta( $store_id, 'shipping_address_2', $dokan_settings['padd_line_1'] );
        update_user_meta( $store_id, 'shipping_postcode', $dokan_settings['ppin_code'] );
        update_user_meta( $store_id, 'shipping_city', $dokan_settings['pcity'] );
        update_user_meta( $store_id, 'shipping_state', $dokan_settings['pstate'] );
        
        do_action( 'dokan_store_profile_saved', $store_id, $dokan_settings );

        if ( ! defined( 'DOING_AJAX' ) ) {
            $_GET['message'] = 'profile_saved';
        }
    }
    function setting_field( $validate = '' ) {
        global $current_user;
		require_once ('template-agreement.php');
        if ( isset($_GET['message'])) {
            ?>
            <div class="dokan-alert dokan-alert-success">
                <button type="button" class="dokan-close" data-dismiss="alert">&times;</button>
                <strong><?php _e('Your profile has been updated successfully!','dokan'); ?></strong>
            </div>
            <?php
        }
        
		$profile_info = dokan_get_store_info( $current_user->ID );
		$legal_stat = isset( $profile_info['legal_stat'] ) ? esc_attr( $profile_info['legal_stat'] ) : esc_attr( $profile_info['legal_stat']);
		$firm_name = isset( $profile_info['firm_name'] ) ? esc_attr( $profile_info['firm_name'] ) : esc_attr( $profile_info['firm_name'] );
		
		//Office Address
		$add_line_1 = isset( $profile_info['add_line_1'] ) ? esc_textarea( $profile_info['add_line_1'] ) : esc_textarea( $profile_info['add_line_1'] );
		$add_line_2 = isset( $profile_info['add_line_2'] ) ? esc_textarea( $profile_info['add_line_2'] ) : esc_textarea( $profile_info['add_line_2'] );
		$pin_code = isset( $profile_info['pin_code'] ) ? esc_textarea( $profile_info['pin_code'] ) : esc_textarea( $profile_info['pin_code'] );
		$state  = isset( $profile_info['state'] ) ? esc_textarea( $profile_info['state'] ) :  esc_textarea( $profile_info['state'] );
		$city  = isset( $profile_info['city'] ) ? esc_textarea( $profile_info['city'] ) : esc_textarea( $profile_info['city'] );
		$full_address = $address.', '.$add_line_2.', '.$city.'-'.$pin_code.', '.$state;
		//Pickup Address
		$padd_line_1 = isset( $profile_info['padd_line_1'] ) ? esc_textarea( $profile_info['padd_line_1'] ) : esc_textarea( $profile_info['padd_line_1'] );
		$padd_line_2 = isset( $profile_info['padd_line_2'] ) ? esc_textarea( $profile_info['padd_line_2'] ) : esc_textarea( $profile_info['padd_line_2'] );
		$ppin_code = isset( $profile_info['ppin_code'] ) ? esc_textarea( $profile_info['ppin_code'] ) : esc_textarea( $profile_info['ppin_code'] );
		$pstate  = isset( $profile_info['pstate'] ) ? esc_textarea( $profile_info['pstate'] ) :  esc_textarea( $profile_info['pstate'] );
		$pcity  = isset( $profile_info['pcity'] ) ? esc_textarea( $profile_info['pcity'] ) : esc_textarea( $profile_info['pcity'] );
		$pfull_address = $paddress.', '.$padd_line_2.', '.$pcity.'-'.$ppin_code.', '.$pstate;
		
		$pan_no  = isset( $profile_info['pan_no'] ) ? esc_textarea( $profile_info['pan_no'] ) : esc_textarea( $profile_info['pan_no'] );
		$vat_no  = isset( $profile_info['vat_no'] ) ? esc_textarea( $profile_info['vat_no'] ) : esc_textarea( $profile_info['vat_no'] );
		$setting_phone = isset( $profile_info['billing_phone'] ) ? esc_attr( $profile_info['billing_phone'] ) : esc_attr( $profile_info['billing_phone'] );
		$bus_cat  = isset( $profile_info['bus_cat'] ) ? esc_textarea( $profile_info['bus_cat'] ) : esc_textarea( $profile_info['bus_cat'] );
		$plans  = isset( $profile_info['plans'] ) ? esc_textarea( $profile_info['plans'] ) : esc_textarea( $profile_info['plans'] );
		
		$full_name = isset( $profile_info['full_name'] ) ? esc_attr( $profile_info['full_name'] ) : esc_attr( $profile_info['full_name'] );		
		$agreement1 = isset( $profile_info['agreement1'] ) ? esc_attr( $profile_info['agreement1'] ) : 'no';	
		$mou = isset( $profile_info['mou'] ) ? esc_attr( $profile_info['mou'] ) : 'no' ;	
		$toa = isset( $profile_info['toa'] ) ? esc_attr( $profile_info['toa'] ) : esc_attr( $profile_info['toa'] );	
		$sfp = isset( $profile_info['sfp'] ) ? esc_attr( $profile_info['sfp'] ) : esc_attr( $profile_info['sfp'] );		
		if($agreement1 == 'yes') {
			$disabled = 'readonly';
		}
		
		
        $banner = isset( $profile_info['banner'] ) ? absint( $profile_info['banner'] ) : 0;
        $storename = isset( $profile_info['store_name'] ) ? esc_attr( $profile_info['store_name'] ) : '';		
        $gravatar = isset( $profile_info['gravatar'] ) ? absint( $profile_info['gravatar'] ) : 0;
        $fb = isset( $profile_info['social']['fb'] ) ? esc_url( $profile_info['social']['fb'] ) : '';
        $twitter = isset( $profile_info['social']['twitter'] ) ? esc_url( $profile_info['social']['twitter'] ) : '';
        $gplus = isset( $profile_info['social']['gplus'] ) ? esc_url ( $profile_info['social']['gplus'] ) : '';
        $linkedin = isset( $profile_info['social']['linkedin'] ) ? esc_url( $profile_info['social']['linkedin'] ) : '';
        $youtube = isset( $profile_info['social']['youtube'] ) ? esc_url( $profile_info['social']['youtube'] ) : '';
        // bank
        
        $show_email = isset( $profile_info['show_email'] ) ? esc_attr( $profile_info['show_email'] ) : 'no';
        
        $map_location = isset( $profile_info['location'] ) ? esc_attr( $profile_info['location'] ) : '';
        $map_address = isset( $profile_info['find_address'] ) ? esc_attr( $profile_info['find_address'] ) : '';
        $dokan_category = isset( $profile_info['dokan_category'] ) ? $profile_info['dokan_category'] : '';
        if ( is_wp_error( $validate) ) {
            $social       = $_POST['settings']['social'];
            $storename    = $_POST['dokan_store_name'];
            $fb           = esc_url( $social['fb'] );
            $twitter      = esc_url( $social['twitter'] );
            $gplus        = esc_url( $social['gplus'] );
            $linkedin     = esc_url( $social['linkedin'] );
            $youtube      = esc_url( $social['youtube'] );
            $phone        = $_POST['billing_phone'];
            $address      = $_POST['setting_address'];
            $map_location = $_POST['location'];
            $map_address  = $_POST['find_address'];
        }
		
		//Custom query
		//$shipping_postcode = get_user_meta( $current_user->ID, 'billing_city', true );
		
		/*Custom Shortcode */
		$billing_company = get_user_meta( get_current_user_id(), 'billing_company', true );
		$billing_add1 = get_user_meta( get_current_user_id(), 'billing_address_1', true );
		$billing_add2 = get_user_meta( get_current_user_id(), 'billing_address_2', true );
		$billing_postcode = get_user_meta( get_current_user_id(), 'billing_postcode', true );
		$billing_city=get_user_meta( get_current_user_id(), 'billing_city', true );
		$billing_state=get_user_meta( get_current_user_id(), 'billing_state', true );
		$shipping_company = get_user_meta( get_current_user_id(), 'shipping_company', true );
		$shipping_add1 = get_user_meta( get_current_user_id(), 'shipping_address_1', true );
		$shipping_add2 = get_user_meta( get_current_user_id(), 'shipping_address_2', true );
		$shipping_postcode = get_user_meta( get_current_user_id(), 'shipping_postcode', true );
		$shipping_city=get_user_meta( get_current_user_id(), 'shipping_city', true );
		$shipping_state=get_user_meta( get_current_user_id(), 'shipping_state', true );
		
		function agree_add() {	return '<span id="agreement_address"><span id="add_line_1_t">'.get_user_meta( get_current_user_id(), 'billing_address_1', true ).'</span>,<span id="add_line_2_t">'.get_user_meta( get_current_user_id(), 'billing_address_2', true ).'</span>,<span>'.get_user_meta( get_current_user_id(), 'billing_city', true ).','.get_user_meta( get_current_user_id(), 'billing_state', true ).'</span><span id="pin_code_t">'.get_user_meta( get_current_user_id(), 'billing_postcode', true ).'</span></span>';}
add_shortcode('agreement_address', 'agree_add');
        function agree_firm() {	$dokan_info = get_user_meta( get_current_user_id(), 'dokan_profile_settings', true ); return '<span id="agreement_name">'.get_user_meta( get_current_user_id(), 'billing_company', true ).' (PAN:'.$dokan_info['pan_no'].')</span>';
		}
		add_shortcode('agreement_name', 'agree_firm');
?>
<div class="dokan-ajax-response"></div>
<form method="post" id="settings-form"  action="" class="dokan-form-horizontal">
<script>
jQuery(function($) {
  $("#firm_name").keyup(function(){
    var text = this.value;
	document.getElementById('agreement_name').innerHTML= text;
  });
  $("#add_line_1").keyup(function(){
    var text = this.value;
	document.getElementById('add_line_1_t').innerHTML= text;
  });
  $("#add_line_2").keyup(function(){
    var text = this.value;
	document.getElementById('add_line_2_t').innerHTML= text;
  });
  $("#pin_code").keyup(function(){
    var text = this.value;
	document.getElementById('pin_code_t').innerHTML= text;
  });
  $("#state").change(function(){
    var text = $("#city").val()+','+this.value;
	$('#agreement_address span:last-child').text(text);
  });
  $("#city").change(function(){
    var text = this.value+','+$("#state").val();
	$('#agreement_address span:last-child').text(text);
  });
  $("#pan_no").keyup(function(){
    var text = $("#firm_name").val()+' (PAN:'+this.value+')';
	$('#agreement_name').text(text);
  });
});
</script>
<?php $disable="readonly";
wp_nonce_field( 'dokan_settings_nonce' ); ?> 
    <div class="step_form_settings">
      <h3>Seller Agreement</h3>
      <section>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="legal_stat">
                <?php _e( 'Legal Status', 'dokan' ); ?>           
                <a type="button" class="btn btn-default cust_tool" data-toggle="tooltip" data-placement="bottom" title="Choose Your Business Type">?</a>
              </label>
              <div class="col-md-8">
                <?php 
              if(!empty($legal_stat) && $agreement1 == 'yes') { ?>
                
                <input id="legal_stat" required value="<?php echo $legal_stat; ?>" <?php echo $disable; ?> name="legal_stat" class="form-control input-md" type="text">
                <?php } else { ?>
                
                <select id="legal_stat" name="legal_stat" class="form-control input-md" required>
                  <option value="Individual">Individual</option>
                  <option value="Partnership Firm">Partnership Firm</option>
                  <option value="Proprietorship Firm">Proprietorship Firm</option>
                  <option value="A Company">A Company</option>
                </select>                
                <?php } ?>
              </div>
            </div>              
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="firm_name">
                <?php _e( 'Individual / Firm Name', 'dokan' ); ?>
                <a type="button" class="btn btn-default cust_tool" data-toggle="tooltip" data-placement="bottom" title="Name of your business or Your Name in case of Individual">?</a>
              </label>
              <div class="col-md-8">
                <input id="firm_name" required value="<?php echo $billing_company; ?>" name="firm_name" placeholder="Firm Name" class="form-control input-md" type="text" <?php echo $billing_company?$disable:''; ?>>            
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="add_line_1">
                <?php _e( 'Office Address', 'dokan' ); ?>    
                <a type="button" class="btn btn-default cust_tool" data-toggle="tooltip" data-placement="bottom" title="This will be you office Address.">?</a>            
              </label>
              <div class="col-md-8">
                <input id="add_line_1" required value="<?php echo $billing_add1; ?>" name="add_line_1" placeholder="Address Line 1" class="form-control input-md" type="text" <?php echo $billing_add1?$disable:''; ?>>            
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="add_line_2">
                <?php _e( '&nbsp;', 'dokan' ); ?>                
              </label>
              <div class="col-md-8">
                <input id="add_line_2" required value="<?php echo $billing_add2; ?>" name="add_line_2" placeholder="Address Line 2" class="form-control input-md" type="text" <?php echo $billing_add2?$disable:''; ?>>            
              </div>
            </div> 
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="pin_code">
                <?php _e( 'Pin Code', 'dokan' ); ?>                
              </label>
              <div class="col-md-8">
                <input id="pin_code" required value="<?php echo $billing_postcode; ?>" name="pin_code" placeholder="Pin Code" class="form-control input-md" type="text" <?php echo $billing_postcode?$disable:''; ?>>            
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="state">
                <?php _e( 'State', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
              <?php
                $userpro = get_option( 'userpro_fields' );
                $states = implode('","', $userpro['billing_state']['options']);
				$cities = implode('","', $userpro['billing_city']['options']);?>
                <?php if($billing_state==''):?>
                <select id="state" required class="form-control input-md" name="state">
                <script language="javascript">
                var states = new Array(<?php echo '"'.$states.'"'; ?>);
				document.write("<option value='' selected='selected'>Select State:</option>");
                for(var hi=0; hi<states.length; hi++)
                	document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>");
				</script>
                </select>
                <?php else:?>
                <input id="state" value="<?php echo $billing_state; ?>" name="state" placeholder="State" class="form-control input-md" type="text" <?php echo $billing_state?$disable:''; ?>>  
                <?php endif;?>
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="city">
                <?php _e( 'City', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
                <?php if($billing_city==''): ?>
                <select required id="city" class="form-control input-md" name="city">
                <script language="javascript">
                var cities = new Array(<?php echo '"'.$cities.'"'; ?>);
				document.write("<option value='' selected='selected'>Select City:</option>");
                for(var hi=0; hi<cities.length; hi++)
					document.write("<option value=\""+cities[hi]+"\">"+cities[hi]+"</option>");
                </script>
                </select>
                <?php else:?>
                <input id="city" value="<?php echo $billing_city; ?>" name="city" placeholder="City" class="form-control input-md" type="text" <?php echo $billing_city?$disable:''; ?>>  
                <?php endif;?>
              </div>
            </div>               
          </div>
          <div class="col-md-6">            
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="firm_name">
                <?php _e( 'PAN Number *', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
                <input id="pan_no" required value="<?php echo $pan_no; ?>" name="pan_no" placeholder="PAN Number" class="form-control input-md" type="text" <?php echo $pan_no?$disable:''; ?>>            
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="firm_name">
                <?php _e( 'VAT Number', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
                <input id="vat_no" value="<?php echo $vat_no; ?>" name="vat_no" placeholder="VAT Number" class="form-control input-md" type="text" <?php echo $vat_no?$disable:''; ?>>
              </div>
            </div>            
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="bus_cat">
                <?php _e( 'Business Category', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
                <?php 
              if(!empty($city) && $agreement1 == 'yes'){ ?>
                <?php 
                $theCatId = get_term_by( 'slug', $bus_cat, 'product_cat' );?>
                <input id="bus_cat" value="<?php echo $theCatId->name; ?>" name="bus_cat" class="form-control input-md" type="text" <?php echo $theCatId->name?$disable:''; ?>>
                <?php } else { ?>
                <select id="bus_cat" name="bus_cat" class="form-control input-md" required>
                  <?php
                   $args = array(						  
                        'orderby'    => 'name',
                        'order'      => 'ASC',						  						  
                    );              
                    $product_categories = get_terms( 'product_cat', $args );
                    foreach($product_categories as $cat){
                        if( 0 == $cat->parent ){
                            echo '<option value="' . $cat->slug . '"', $bus_cat == $cat->slug ? ' selected="selected"' : '', '>', $cat->name, '</option>';
                        }
                    }
                   ?>
                </select>
                <?php } ?>
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="add_line_1">
                <?php _e( 'Pickup Address', 'dokan' ); ?>    
                <a type="button" class="btn btn-default cust_tool" data-toggle="tooltip" data-placement="bottom" title="This will be you Pickup Address from where the products will be picked up">?</a>            
              </label>
              <div class="col-md-8">
                <input id="add_line_1" required value="<?php echo $shipping_add1; ?>" name="padd_line_1" placeholder="Address Line 1" class="form-control input-md" type="text" <?php echo $shipping_add1?$disable:''; ?>>            
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="add_line_2">
                <?php _e( '&nbsp;', 'dokan' ); ?>                
              </label>
              <div class="col-md-8">
                <input id="add_line_2" required value="<?php echo $shipping_add2; ?>" name="padd_line_2" placeholder="Address Line 2" class="form-control input-md" type="text" <?php echo $shipping_add2?$disable:''; ?>>            
              </div>
            </div> 
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="pin_code">
                <?php _e( 'Pin Code', 'dokan' ); ?>                
              </label>
              <div class="col-md-8">
                <input id="pin_code" required value="<?php echo $shipping_postcode; ?>" name="ppin_code" placeholder="Pin Code" class="form-control input-md" type="text" <?php echo $shipping_postcode?$disable:''; ?>>            
              </div>
            </div>
            <div class="form-group" style="text-align:right">
            <style>.wpo-wrapper.test-style2_dw > option { display: none !important; }</style>
              <label class="col-md-4 control-label" for="pstate">
                <?php _e( 'State', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
                <?php if($shipping_state==''):?>
                        <script>var stt = new Array();</script>
						<?php
                        global $wpdb;
                        $mycity = $wpdb->prefix . "mycity";
                        $states=$wpdb->get_results("select distinct(state) from `$mycity` order by state asc");
                        foreach($states as $s):  
                            $citiess='';
                            $cities=$wpdb->get_results("select distinct(name) from `$mycity` where state='$s->state' order by name asc");
                            $statess.='"'.$s->state.'",';
                            foreach($cities as $c):  
                                $citiess.=$c->name.',';
                            endforeach;
                            $citiess=rtrim($citiess,',');
                            ?>
                            <script>
                            stt['<?php echo $s->state?>']='<?php echo $citiess?>';
                            </script>
                            <?php
                        endforeach;
                        $statess=rtrim($statess,',');
                        ?>
				<select id="pstate" class="form-control input-md" name="pstate">
                    <option value="">Select State:</option>
                    <script language="javascript">
                    var states = new Array(<?php echo $statess; ?>);
                    for(var hi=0; hi<states.length; hi++)
                    document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>");
                    </script>
                </select>
                <?php else:?>
                <input id="pstate" required value="<?php echo $shipping_state; ?>" name="pstate" placeholder="State" class="form-control input-md" type="text" <?php echo $disable; ?>>  
                <?php endif;?>
              </div>
            </div>
            <div class="form-group" style="text-align:right">
              <label class="col-md-4 control-label" for="pcity">
                <?php _e( 'City', 'dokan' ); ?>
              </label>
              <div class="col-md-8">
                <?php if($shipping_city==''): ?>
                <select id="pcity" class="form-control input-md" name="pcity">
                
                </select>
                        <script>
						console.log(stt);
						jQuery(document).ready(function() {
							jQuery('#pstate').change(function(){
							jQuery('#pcity').html('');
							var st = jQuery(this).val();
							if(st==''){
								jQuery('#pcity').html('<option val="">Select City</option>');
							} else {
								var s = stt[st].split(",");
								for (var i=0; i<s.length; i++) {
									jQuery('#pcity').append('<option val="'+s[i]+'">'+s[i]+'</option>');
								}
							}
							});
						});
						</script>
                <?php else:?>
                <input id="pcity" required value="<?php echo $shipping_city; ?>" name="pcity" placeholder="City" class="form-control input-md" type="text" <?php echo $disable; ?>>  
                <?php endif;?>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
          	<div style="border: 1px solid #ddd;text-align:left;max-height: 300px; overflow-y: scroll;padding: 15px; background:#fff"> 
            <?php			  
			  $seller_terms_id = get_post(4850);
			  $content = $seller_terms_id->post_content;
			  $content = apply_filters('the_content', $content);			 
			  echo $content;
			?>
            </div>
          </div>
          <?php 
		  if($agreement1 == 'yes') { ?>
		  <div class="form-group" style="">
			<div class="col-md-1">
              <div class="checkboxFive">                  
                  <input type="hidden" name="agreement1" value="no" >
                  <input type="checkbox" name="agreement1" value="yes"<?php checked( $agreement1, 'yes' ); ?> <?php echo $disabled; ?> required>
                  <label for="checkboxFiveInput"></label>
              </div>			  
            </div>
			<div class="col-md-7" style="text-align:left">
			  <p>Agreement Signed by <b style="font-weight:bold"><?php echo $full_name;?></b> on <b style="font-weight:bold"><?php echo $toa;?></b></p>
			</div>
			<div class="col-md-4" >
			  <p style="color:red; text-align:right" >Contact Us for any Important Changes & Updates</p>
			</div>
		  </div>
		  <input id="full_name" required value="<?php echo $full_name; ?>" name="full_name" class="form-control input-md" type="hidden" <?php echo $disabled; ?>>
		  <input id="agreement1" required value="<?php echo $agreement1; ?>" name="agreement1"  class="form-control input-md" type="hidden" <?php echo $disabled; ?>>
		  <input id="toa" value="<?php echo $toa; ?>" name="toa" type="hidden">
		  <?php } else { ?>
		  <div class="form-group">
			<div class="col-md-1">
              <div class="checkboxFive">                  
                  <input type="hidden" name="agreement1" value="no" >
                  <input id="checkboxFiveInput" type="checkbox" name="agreement1" value="yes"<?php checked( $agreement1, 'yes' ); ?> <?php echo $disabled; ?> required>
                  <label for="checkboxFiveInput"></label>
              </div>			  
			</div>			
			<label class="col-md-7 control-label" for="setting_phone" style="text-align:left">
			  <?php _e( 'I hereby accept the above mentioned terms & conditions', 'dokan' ); ?>
			</label>			
            <div class="col-md-4">
			  <input id="full_name" required value="<?php echo $full_name; ?>" name="full_name" placeholder="Full Name" class="form-control input-md" type="text" <?php echo $disabled; ?>>
			  <input id="toa" value="<?php echo date('d F Y' ); ?>" name="toa" type="hidden">
			</div>
		  </div>
		  <?php } ?>
        </div>
      </section>
      
      <h3>Seller Obligations (MOU)</h3>
      <section>
        <div style="border: 1px solid #ddd;text-align:left;max-height: 500px; overflow-y: scroll;padding: 15px; background:#fff"> 
        <?php			  
		  $seller_mou = get_post(4859);
		  $content_mou = $seller_mou->post_content;
		  $content_mou = apply_filters('the_content', $content_mou);			 
		  echo $content_mou;
		?>
        </div>
        <div class="form-group">
          <div class="col-md-1">
            <div class="checkboxFive">                  
                <input type="hidden" name="mou" value="no" >
                <input id="checkboxFiveInput2" type="checkbox" name="mou" value="yes"<?php checked( $mou, 'yes' ); ?> <?php echo $disabled; ?> required>
                <label for="checkboxFiveInput2"></label>
            </div>            
          </div>
          <label class="col-md-11 control-label" for="setting_phone" style="text-align:left">
            <?php _e( ' By clicking this checkbox, I agree with Shopwow.in Policy & Rules, User Agreement, Privacy Policy, and MOU agreement above.', 'dokan' ); ?>
          </label>            
        </div>        
      </section>
      
      <h3>Service Fee Plan</h3>
      <section>
		<?php 
       // if(!empty($plans)) { ?>
        Plan Type : <?php echo $plans; ?>
        <input id="plans" value="<?php echo $plans; ?>" name="plans" type="hidden" <?php echo $disabled; ?>>
        <?php //} else { ?>
        <img src="<?php the_field('seller_table',4859);?>" style="width: 95%;position: absolute;left: 0;z-index: 9;background: rgba(0,0,0,0.6);margin: 0 2.5%;">
        <div class="row" style="margin-top:85px; margin-bottom: 15px;">
          <?php 
			 $query = new WP_Query( array('post_type'  => 'service_fee_plans',) );?>
			 <?php if ( $query->have_posts() ) : ?>
			   <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <div class="col-md-4" >
                  <div class="type">
                    <p><?php the_title(); ?></p>
                  </div>
                  <div class="plan">
                    <input id="<?php the_ID(); ?>" name="plans" type="radio"  value="<?php the_title(); ?>">
                    <label for="<?php the_ID(); ?>">
                    <div class="header"> <span>Rs.</span><?php echo get_post_meta(get_the_ID(),'fee_price', true); ?>
                      <p class="month"><?php echo get_post_meta(get_the_ID(),'fee_period', true); ?></p>
                    </div>
                    <div class="content">
                      <ul>
                        <li><i class="fa fa-arrow-right"></i><?php echo get_post_meta(get_the_ID(),'plan_feature_1', true); ?></li>
                        <li><i class="fa fa-arrow-right"></i><?php echo get_post_meta(get_the_ID(),'plan_feature_2', true); ?></li>
                        <li><i class="fa fa-arrow-right"></i><?php echo get_post_meta(get_the_ID(),'plan_feature_3', true); ?></li>
                        <li><i class="fa fa-arrow-right"></i><?php echo get_post_meta(get_the_ID(),'plan_feature_4', true); ?></li>
                        <li><i class="fa fa-arrow-right"></i><?php echo get_post_meta(get_the_ID(),'plan_feature_5', true); ?></li>
                        <li><i class="fa fa-arrow-right"></i><?php echo get_post_meta(get_the_ID(),'plan_feature_6', true); ?></li>
                      </ul>
                    </div>
                    <div class="price">
                      <p class="cart">Select</p>
                    </div>
                    </label>
                  </div>
                </div>
               <?php endwhile; ?>
			   <?php wp_reset_postdata(); ?>
		     <?php endif; ?>
        </div>
        <div class="row">
        <?php			  
		  $seller_mou = get_post(4910);
		  $content_mou = $seller_mou->post_content;
		  $content_mou = apply_filters('the_content', $content_mou);			 
		  echo $content_mou;
		?>
        </div>
        <?php  //} ?>
        <div class="form-group">
          <div class="col-md-1">
            <div class="checkboxFive">                  
                <input type="hidden" name="sfp" value="no" >
                <input id="checkboxFiveInput3" type="checkbox" name="sfp" value="yes"<?php checked( $sfp, 'yes' ); ?> <?php echo $disabled; ?> required>
                <label for="checkboxFiveInput3"></label>
            </div>            
          </div>
          <label class="col-md-11 control-label" for="setting_phone" style="text-align:left">
            <?php _e( ' By clicking this checkbox, I agree to PAY Service Fee and Shipping Charges as mentioned above.', 'dokan' ); ?>
          </label>            
        </div>
      </section>
      
      <h3>Bank Details</h3>
      <section>
          <!-- payment tab -->
          <div class="form-group">      
            <div class="col-md-12">
              <?php $methods = dokan_withdraw_get_active_methods(); ?>        
              <div class="col-md-12">
                <?php
                                      $count = 0;
                                      foreach ($methods as $method_key) {
                                          $method = dokan_withdraw_get_method( $method_key );
          
                                          ?>
                <div class="tab-pane<?php echo ($count == 0) ? ' active': ''; ?>" id="dokan-payment-<?php echo $method_key; ?>">
                  <?php if ( is_callable( $method['callback']) ) {
                                                  call_user_func( $method['callback'], $profile_info );
                                              } ?>
                </div>
                <?php
                                          $count++;
                                      } ?>
              <?php $binfo = get_user_meta( get_current_user_id(), 'dokan_profile_settings', true );
					if($binfo['payment']['bank']['ac_number']!=''){?>
						<script language="javascript">
						jQuery("#dokan-payment-bank input").prop("readonly",true);
						jQuery('#dokan-payment-bank input').attr('readonly', true);
						jQuery("#dokan-payment-bank textarea").prop("readonly",true); 
						jQuery('#dokan-payment-bank textarea').attr('readonly', true);
						jQuery('#dokan-payment-bank input').css('background-color' , '#eeeeee');
						jQuery('#dokan-payment-bank input').css('cursor' , 'not-allowed');
						jQuery('#dokan-payment-bank textarea').css('background-color' , '#eeeeee');
						jQuery('#dokan-payment-bank textarea').css('cursor' , 'not-allowed');
						</script>
						<?php } ?> 
              </div>
              <!-- .tab-content -->
            </div>            
          </div>
          <!-- .form-group -->
      </section>
       
    </div>
</form>
<script type="text/javascript">
    (function($) {
        $(function() {
            <?php
            $locations = explode( ',', $map_location );
            $def_lat = isset( $locations[0] ) ? $locations[0] : 90.40714300000002;
            $def_long = isset( $locations[1] ) ? $locations[1] : 23.709921;
            ?>
            var def_zoomval = 12;
            var def_longval = '<?php echo $def_long; ?>';
            var def_latval = '<?php echo $def_lat; ?>';
            var curpoint = new google.maps.LatLng(def_latval, def_longval),
                geocoder   = new window.google.maps.Geocoder(),
                $map_area = $('#dokan-map'),
                $input_area = $( '#dokan-map-lat' ),
                $input_add = $( '#dokan-map-add' ),
                $find_btn = $( '#dokan-location-find-btn' );
            autoCompleteAddress();
            $find_btn.on('click', function(e) {
                e.preventDefault();
                geocodeAddress( $input_add.val() );
            });
            var gmap = new google.maps.Map( $map_area[0], {
                center: curpoint,
                zoom: def_zoomval,
                mapTypeId: window.google.maps.MapTypeId.ROADMAP
            });
            var marker = new window.google.maps.Marker({
                position: curpoint,
                map: gmap,
                draggable: true
            });
            window.google.maps.event.addListener( gmap, 'click', function ( event ) {
                marker.setPosition( event.latLng );
                updatePositionInput( event.latLng );
            } );
            window.google.maps.event.addListener( marker, 'drag', function ( event ) {
                updatePositionInput(event.latLng );
            } );
            function updatePositionInput( latLng ) {
                $input_area.val( latLng.lat() + ',' + latLng.lng() );
            }
            function updatePositionMarker() {
                var coord = $input_area.val(),
                    pos, zoom;
                if ( coord ) {
                    pos = coord.split( ',' );
                    marker.setPosition( new window.google.maps.LatLng( pos[0], pos[1] ) );
                    zoom = pos.length > 2 ? parseInt( pos[2], 10 ) : 12;
                    gmap.setCenter( marker.position );
                    gmap.setZoom( zoom );
                }
            }
            function geocodeAddress( address ) {
                geocoder.geocode( {'address': address}, function ( results, status ) {
                    if ( status == window.google.maps.GeocoderStatus.OK ) {
                        updatePositionInput( results[0].geometry.location );
                        marker.setPosition( results[0].geometry.location );
                        gmap.setCenter( marker.position );
                        gmap.setZoom( 15 );
                    }
                } );
            }
            function autoCompleteAddress(){
                if (!$input_add) return null;
                $input_add.autocomplete({
                    source: function(request, response) {
                        // TODO: add 'region' option, to help bias geocoder.
                        geocoder.geocode( {'address': request.term }, function(results, status) {
                            response(jQuery.map(results, function(item) {
                                return {
                                    label     : item.formatted_address,
                                    value     : item.formatted_address,
                                    latitude  : item.geometry.location.lat(),
                                    longitude : item.geometry.location.lng()
                                };
                            }));
                        });
                    },
                    select: function(event, ui) {
                        $input_area.val(ui.item.latitude + ',' + ui.item.longitude );
                        var location = new window.google.maps.LatLng(ui.item.latitude, ui.item.longitude);
                        gmap.setCenter(location);
                        // Drop the Marker
                        setTimeout( function(){
                            marker.setValues({
                                position    : location,
                                animation   : window.google.maps.Animation.DROP
                            });
                        }, 1500);
                    }
                });
            }
        });
    })(jQuery);
</script>
<script>
                    (function($){
        $(document).ready(function(){
            $('#payment_method_tab').easytabs();
        });
    })(jQuery)
</script>
    <?php
    }
    /**
     * Calculate Profile Completeness meta value
     *
     * @since 2.1
     *
     * @param  array  $dokan_settings
     *
     * @return array
     */
    function calculate_profile_completeness_value( $dokan_settings ) {

        $profile_val = 0;
        $next_add    = '';
        $track_val   = array();

        $progress_values = array(
           'banner_val'          => 15,
           'profile_picture_val' => 15,
           'store_name_val'      => 10,
           'social_val'          => array(
               'fb'       => 2,
               'gplus'    => 2,
               'twitter'  => 2,
               'youtube'  => 2,
               'linkedin' => 2,
           ),
           'payment_method_val'  => 15,
           'phone_val'           => 10,
           'address_val'         => 10,
           'map_val'             => 15,
        );

        // setting values for completion
        $progress_values = apply_filters('dokan_profile_completion_values', $progress_values);

        extract( $progress_values );

        //settings wise completeness section
        if( isset( $dokan_settings['gravatar'] ) ):
            if ( $dokan_settings['gravatar'] != 0 ) {
                $profile_val           = $profile_val + $profile_picture_val;
                $track_val['gravatar'] = $profile_picture_val;
            } else {
                if ( strlen( $next_add ) == 0 ) {
                    $next_add = sprintf(__( 'Add Profile Picture to gain %s%% progress', 'dokan' ), $profile_picture_val);
                }
            }
        endif;

        // Calculate Social profiles
        if( isset( $dokan_settings['social'] ) ):

            foreach ( $dokan_settings['social'] as $key => $value ) {

                if ( isset( $social_val[$key] ) && $value != false ) {
                    $profile_val     = $profile_val + $social_val[$key];
                    $track_val[$key] = $social_val[$key];
                }

                if ( isset( $social_val[$key] ) && $value == false ) {

                    if ( strlen( $next_add ) == 0 ) {
                        //replace keys to nice name
                        $nice_name = ( $key === 'fb' ) ? __( 'Facebook', 'dokan' ) : ( ( $key === 'gplus' ) ? __( 'Google+', 'dokan' ) : $key);
                        $next_add = sprintf( __( 'Add %s profile link to gain %s%% progress', 'dokan' ), $nice_name, $social_val[$key] );
                    }
                }
            }
        endif;

        //calculate completeness for phone
        if( isset( $dokan_settings['phone'] ) ):

            if ( strlen( trim( $dokan_settings['phone'] ) ) != 0 ) {
                $profile_val        = $profile_val + $phone_val;
                $track_val['phone'] = $phone_val;
            } else {
                if ( strlen( $next_add ) == 0 ) {
                    $next_add = sprintf( __( 'Add Phone to gain %s%% progress', 'dokan' ), $phone_val );
                }
            }

        endif;

        //calculate completeness for banner
        if( isset( $dokan_settings['banner'] ) ):

            if ( $dokan_settings['banner'] != 0 ) {
                $profile_val         = $profile_val + $banner_val;
                $track_val['banner'] = $banner_val;
            } else {
                $next_add = sprintf(__( 'Add Banner to gain %s%% progress', 'dokan' ), $banner_val);
            }

        endif;

        //calculate completeness for store name
        if( isset( $dokan_settings['store_name'] ) ):
            if ( isset( $dokan_settings['store_name'] ) ) {
                $profile_val             = $profile_val + $store_name_val;
                $track_val['store_name'] = $store_name_val;
            } else {
                if ( strlen( $next_add ) == 0 ) {
                    $next_add = sprintf( __( 'Add Store Name to gain %s%% progress', 'dokan' ), $store_name_val );
                }
            }
        endif;

        //calculate completeness for address
        if( isset( $dokan_settings['address'] ) ):
            if ( !empty($dokan_settings['address']['street_1']) ) {
                $profile_val          = $profile_val + $address_val;
                $track_val['address'] = $address_val;
            } else {
                if ( strlen( $next_add ) == 0 ) {
                    $next_add = sprintf(__( 'Add address to gain %s%% progress', 'dokan' ),$address_val);
                }
            }
        endif;

        // Calculate Payment method val for Bank
        if ( isset( $dokan_settings['payment']['bank'] ) ) {
            $count_bank = true;

            // if any of the values for bank details are blank, check_bank will be set as false
            foreach ( $dokan_settings['payment']['bank'] as $value ) {
                if ( strlen( trim( $value )) == 0)   {
                    $count_bank = false;
                }
            }

            if ( $count_bank ) {
                $profile_val        = $profile_val + $payment_method_val;
                $track_val['Bank']  = $payment_method_val;
                $payment_method_val = 0;
                $payment_added      = 'true';
            }
        }

        // Calculate Payment method val for Paypal
        if ( isset( $dokan_settings['payment']['paypal'] ) ) {
            if ( $dokan_settings['payment']['paypal']['email'] != false ) {

                $profile_val         = $profile_val + $payment_method_val;
                $track_val['paypal'] = $payment_method_val;
                $payment_method_val  = 0;
            }
        }

        // Calculate Payment method val for skrill
        if ( isset( $dokan_settings['payment']['skrill'] ) ) {
            if ( $dokan_settings['payment']['skrill']['email'] != false ) {

                $profile_val         = $profile_val + $payment_method_val;
                $track_val['skrill'] = $payment_method_val;
                $payment_method_val  = 0;
            }
        }

        // set message if no payment method found
        if ( strlen( $next_add ) == 0 && $payment_method_val !=0 ) {
            $next_add = sprintf( __( 'Add a Payment method to gain %s%% progress', 'dokan' ), $payment_method_val );
        }

        if ( isset( $dokan_settings['location'] ) && strlen(trim($dokan_settings['location'])) != 0 ) {
            $profile_val           = $profile_val + $map_val;
            $track_val['location'] = $map_val;
        } else {
            if ( strlen( $next_add ) == 0 ) {
                $next_add = sprintf( __( 'Add Map location to gain %s%% progress', 'dokan' ), $map_val );
            }
        }

        $track_val['next_todo'] = $next_add;
        $track_val['progress'] = $profile_val;

        return $track_val;
    }

    function get_dokan_categories() {
        $dokan_category = array(
            'book'       => __( 'Book', 'dokan' ),
            'dress'      => __( 'Dress', 'dokan' ),
            'electronic' => __( 'Electronic', 'dokan' ),
        );

        return apply_filters( 'dokan_category', $dokan_category );
    }
}
