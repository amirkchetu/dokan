<?php 
function price_table() {
?>
<div class="col-md-4" >
<div class="type">
  <p>Novice</p>
</div>
<div class="plan">
  <input id="plan1" name="plans" type="radio"  value="Novice">
  <label for="plan1">
  	<div class="header">
        <span>Rs.</span>0.00
        <p class="month">Monthly Fee</p>
    </div>
    <div class="content">
        <ul>
            <li><i class="fa fa-arrow-right"></i>Max. 20 Products Upload</li>
            <li><i class="fa fa-arrow-right"></i>Rs. 1000 Price Limit</li>
            <li><i class="fa fa-arrow-right"></i>Upto 30 Transactions/Month</li>
            <li><i class="fa fa-arrow-right"></i>15% Flat Service Fee~</li>
            <li><i class="fa fa-arrow-right"></i>Rs. 0.00 Shipping Charges#</li>
            <li><i class="fa fa-arrow-right"></i>Manager* (Optional: Availabale on Request)</li>
        </ul>
    </div>
    <div class="price">
      <p class="cart">Select</p>
    </div>
  </label>
</div>  
</div>  
<div class="col-md-4" >
<div class="type">
  <p>Professional</p>
</div>
<div class="plan">
  <input id="plan2" name="plans" type="radio"  value="Professional">
  <label for="plan2">
    <div class="header">
        <span>Rs.</span>1000.00
        <p class="month">Monthly Fee</p>
    </div>
    <div class="content">
        <ul>
            <li><i class="fa fa-arrow-right"></i>Max. 100 Products Upload</li>
            <li><i class="fa fa-arrow-right"></i>Rs. 1000 Price Limit</li>
            <li><i class="fa fa-arrow-right"></i>Unlimited Transactions/Month</li>
            <li><i class="fa fa-arrow-right"></i>Category Wise 7%-15% Service Fee~</li>
            <li><i class="fa fa-arrow-right"></i>Refer Annexure - 2</li>
            <li><i class="fa fa-arrow-right"></i>Manager* (Optional: Availabale on Request)</li>
        </ul>
    </div>
    <div class="price">
      <p class="cart">Select</p>
    </div>
  </label>
</div>  
</div>
<div class="col-md-4" >
<div class="type">
  <p>Expert</p>
</div>
<div class="plan">
  <input id="plan3" name="plans" type="radio" value="Expert">
  <label for="plan3">
    <div class="header">
        <span>Rs.</span>0.00
        <p class="month">Monthly</p>
    </div>
    <div class="content">
        <ul>
            <li><i class="fa fa-arrow-right"></i>Max. 200 Products Upload</li>
            <li><i class="fa fa-arrow-right"></i>No Price Limit</li>
            <li><i class="fa fa-arrow-right"></i>Unlimited Transactions/Month</li>
            <li><i class="fa fa-arrow-right"></i>12% Flat Service Fee~</li>
            <li><i class="fa fa-arrow-right"></i>Refer Annexure - 2</li>           
            <li><i class="fa fa-arrow-right"></i>Manager* (Optional: Availabale on Request)</li>
        </ul>
    </div>
    <div class="price">
      <p class="cart">Select</p>
    </div>
  </label>
</div>  
</div>
<?php } ?>