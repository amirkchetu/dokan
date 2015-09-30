<?php $bankInfo = get_user_meta( $user->ID, 'dokan_profile_settings', true ); ?>
    <h3>Bank Details</h3>
<table class="form-table">
    <tbody>
        <tr>
            <th><?php _e( 'Account Holder Name', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_account_name" value="<?php echo $bankInfo['payment']['bank']['ac_name']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'Account Number', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_account_number" value="<?php echo $bankInfo['payment']['bank']['ac_number']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'Bank Name', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_bank_name" value="<?php echo $bankInfo['payment']['bank']['bank_name']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'Bank Address', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_bank_address" value="<?php echo $bankInfo['payment']['bank']['bank_addr']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'Bank IFSC', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_bank_ifsc" value="<?php echo $bankInfo['payment']['bank']['swift']; ?>">
            </td>
        </tr>
    </tbody>
</table>

<h3>Seller Information</h3>
<table class="form-table">
    <tbody>
        <tr>
            <th><?php _e( 'Legal Status', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_legal_status" value="<?php echo $bankInfo['legal_stat']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'PAN No', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_pan" value="<?php echo $bankInfo['pan_no']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'VAT No', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_vat" value="<?php echo $bankInfo['vat_no']; ?>">
            </td>
        </tr>
        <tr>
            <th><?php _e( 'Business Category', 'dokan' ); ?></th>
            <td>
                <input type="text" class="regular-text" name="dokan_bus_cat" value="<?php echo $bankInfo['bus_cat']; ?>">
            </td>
        </tr>
    </tbody>
</table>
<p class="submit"><input type="button" onclick="window.open('<?php bloginfo('template_url'); ?>/print-profile.php?uid=<?php echo $user->ID; ?>')" value="Export Profile" class="button button-primary" id="print" name="print"></p>