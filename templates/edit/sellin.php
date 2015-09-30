<script>var stt = new Array();</script>
<?php
global $wpdb;
$mycity = $wpdb->prefix . "wc_delivery_codes";
$states=$wpdb->get_results("select distinct(state) from `$mycity` order by state asc");
foreach($states as $s):  
	$citiess='';
	$cities=$wpdb->get_results("select distinct(city) from `$mycity` where state='$s->state' order by city asc");
	$statess.='"'.$s->state.'",';
	foreach($cities as $c):  
		$citiess.=$c->city.',';
	endforeach;
	$citiess=rtrim($citiess,',');
	?>
	<script>
	stt['<?php echo $s->state?>']='<?php echo $citiess?>';
	</script>
    <?php
endforeach;
$statess=rtrim($statess,',');
$sell_in = get_post_meta( $post_id, 'sell_in', true );
if(!$sell_in['sell_in']) $sell_in= array('sell_in'=>'','state'=>'','city'=>'');
//var_dump($sell_in['sell_in']);
?>
<div class="dokan-form-horizontal">
    <div class="dokan-form-group">
    	<label class="dokan-w4 dokan-control-label" for="enable_cod"><?php _e( 'COD Available', 'dokan' ); ?></label>
        <div class="dokan-w6 dokan-text-left">
            <input <?php if($sell_in['cod']=='Y') echo 'checked="checked"';?> type="checkbox" id="enable_cod" name="enable_cod" value="Y" />
        </div>
    </div>
    <div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_sell_in"><?php _e( 'Sell In', 'dokan' ); ?></label>
        <div class="dokan-w6 dokan-text-left">
            <input <?php if($sell_in['sell_in']=='all' || $sell_in['sell_in']=='') echo 'checked="checked"';?> type="radio" id="all_india" name="sell_in" value="all" /> All India
            <input <?php echo $sell_in['sell_in']=='state'?'checked="checked"':''; ?> type="radio" id="in_states" name="sell_in" value="state" /> In States
            <input <?php echo $sell_in['sell_in']=='city'?'checked="checked"':''; ?>type="radio" id="in_cities" name="sell_in" value="city" /> In Cities
        </div>
    </div>
    
    <div class="dokan-form-group">
        <label class="dokan-w4 dokan-control-label" for="_sell_in_options">&nbsp;</label>
        <div class="dokan-w6 dokan-text-left">
    <select <?php echo $sell_in['sell_in']!='state'?'style="display:none"':''; ?> id="in_states_state" class="form-control input-md" name="states_state[]" multiple="multiple">
		<script language="javascript">
        var states = new Array(<?php echo $statess; ?>);
        var cur_state = new Array(<?php echo $sell_in['state']; ?>);
        for(var hi=0; hi<states.length; hi++) {
            if(jQuery.inArray(states[hi],cur_state) != -1 ){ document.write("<option selected='selected' value=\""+states[hi]+"\">"+states[hi]+"</option>"); } else {document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>"); } }
        </script>
    </select>
    <select <?php echo $sell_in['sell_in']!='city'?'style="display:none"':''; ?> id="in_cities_state" class="form-control input-md" name="cities_state">
        <option value="">Select State:</option>
        <script language="javascript">
        var states = new Array(<?php echo $statess; ?>);
        var cur_state = new Array(<?php echo $sell_in['state']; ?>);
        for(var hi=0; hi<states.length; hi++)
            if(states[hi]==cur_state) document.write("<option selected='selected' value=\""+states[hi]+"\">"+states[hi]+"</option>");
            else document.write("<option value=\""+states[hi]+"\">"+states[hi]+"</option>");
        </script>
    </select>
    <select <?php echo $sell_in['sell_in']!='city'?'style="display:none"':''; ?> id="in_cities_city" class="form-control input-md" name="cities_city[]" multiple="multiple">
    </select>
     </div>
    </div>
<div class="dokan-form-horizontal">
    <div class="dokan-form-group">
    <p>You Can Select here Location in which you want to sell your Product.<br />eg. If you want to Sell All Over India the Select All India or If Just want to Sell in Selected States and Cities Select them as You Wish.</p>
    </div>
</div>
    
<script>
jQuery(document).ready(function(){
    jQuery('#all_india').click(function(){
        jQuery('#in_states_state').hide();
        jQuery('#in_cities_state').hide();
        jQuery('#in_cities_city').hide();
    });
    jQuery('#in_states').click(function(){
        jQuery('#in_cities_state').hide();
        jQuery('#in_cities_city').hide();
        jQuery('#in_states_state').show();
    });
    jQuery('#in_cities').click(function(){
        jQuery('#in_cities_state').show();
        jQuery('#in_states_state').hide();
        jQuery('#in_cities_city').show();
    });
	
	
	var cur_city = new Array(<?php echo $sell_in['city']; ?>);
	
	var st = jQuery('#in_cities_state').val();
	if(st==''){
		jQuery('#in_cities_city').html('<option val="">Select City</option>');
	} else {
		var s = stt[st].split(",");
		for (var i=0; i<s.length; i++) {
			if(jQuery.inArray(s[i],cur_city) != -1 ){ jQuery('#in_cities_city').append('<option selected="selected" val="'+s[i]+'">'+s[i]+'</option>'); } else { jQuery('#in_cities_city').append('<option val="'+s[i]+'">'+s[i]+'</option>'); }
		}
	}
	jQuery('#in_cities_state').change(function(){
		jQuery('#in_cities_city').html('');
		var st = jQuery(this).val();
		if(st==''){
			jQuery('#in_cities_city').html('<option val="">Select City</option>');
		} else {
			var s = stt[st].split(",");
			for (var i=0; i<s.length; i++) {
				if(jQuery.inArray(s[i],cur_city) != -1 ){ jQuery('#in_cities_city').append('<option selected="selected" val="'+s[i]+'">'+s[i]+'</option>'); } else { jQuery('#in_cities_city').append('<option val="'+s[i]+'">'+s[i]+'</option>'); }
			}
		}
	});
});
</script>
<style>
	option[selected="selected"]{
		background:#3399FF;
		color:#fff;
	}
	input[type="radio"]:checked {
	  box-shadow: 0 0 0 1px #000 inset;
	}
</style>
</div> <!-- .form-horizontal -->