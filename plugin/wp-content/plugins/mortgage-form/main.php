<?php
/*
Plugin Name: Mortgage Form
Plugin URI: simplefastmortgage.com
Description: Allow multiple form step on mortgage form
Author: AdminONE.
Author URI: simplefastmortgage.com
Version: 1.0
*/

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}
if ( ! defined('MG_PLUGIN_BASENAME') )
	define('MG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
if ( ! defined('MG_PLUGIN_NAME') )
	define( 'MG_PLUGIN_NAME', trim( dirname(MG_PLUGIN_BASENAME ), '/' ) );
if ( ! defined( 'MG_PLUGIN_DIR' ) )
	define( 'MG_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . MG_PLUGIN_NAME );
if ( ! defined( 'PP_PLUGIN_URL' ) )
	define( 'MG_PLUGIN_URL', WP_PLUGIN_URL . '/' . MG_PLUGIN_NAME );

register_activation_hook(__FILE__,'mortgage_activation_fn');

add_action('admin_head', 'dd_my_custom_fonts');

function dd_my_custom_fonts() {
  echo '<style>
    #TB_window, #TB_ajaxContent {
        height: auto !important;
    }
    .dd_table_style th {
        font-weight: bold;
    }
    .dd_table_style{
        margin-top: 10px;
    }
  </style>';
}

require_once('ajax-request.php');

function mortgage_activation_fn(){
	global $wpdb;
	$collate = 'DEFAULT CHARSET=utf8';
	
	$sql="CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."mortgage_form_record` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(250) NOT NULL,
		`email` varchar(250) NOT NULL,
		`phone` varchar(25) NOT NULL,
		`credit` varchar(50) NOT NULL,
		`incomplete_form` tinyint(2) NOT NULL,
		`property_type` varchar(250) NOT NULL,
		`property_purpose` varchar(250) NOT NULL,
		`property_city` varchar(250) NOT NULL,
		`property_state` varchar(250) NOT NULL,
		`purchase_price` varchar(250) NOT NULL,
		`down_payment` varchar(250) NOT NULL,
		`birthdate` varchar(250) NOT NULL,
		`bankruptcy_forecloser` varchar(50) NOT NULL,
		`street_address` varchar(50) NOT NULL,
		`city` varchar(50) NOT NULL,
		`state` varchar(50) NOT NULL,
		`zipcode` varchar(50) NOT NULL,
		`annual_income` varchar(50) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 
	";
	$wpdb->query($sql);
	
	$sql="CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."refinance_form_record` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(250) NOT NULL,
		`email` varchar(250) NOT NULL,
		`phone` varchar(25) NOT NULL,
		`credit` varchar(50) NOT NULL,
		`incomplete_form` tinyint(2) NOT NULL,
		`property_type` varchar(20) NOT NULL,
		`property_purpose` varchar(20) NOT NULL,
		`home_value` varchar(20) NOT NULL,
		`mortgage_balance` varchar(20) NOT NULL,
		`remain_balance` varchar(20) NOT NULL,
		`extra_cash` varchar(50) NOT NULL,
		`birthdate` varchar(50) NOT NULL,
		`bankruptcy_forecloser` varchar(50) NOT NULL,
		`street_address` varchar(50) NOT NULL,
		`city` varchar(50) NOT NULL,
		`state` varchar(50) NOT NULL,
		`zipcode` varchar(50) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 
	";
	$wpdb->query($sql);
	
	$sql="CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."reverse_mortgage_form_record` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`name` varchar(250) NOT NULL,
		`email` varchar(250) NOT NULL,
		`phone` varchar(25) NOT NULL,
		`credit` varchar(50) NOT NULL,
		`incomplete_form` tinyint(2) NOT NULL,
		`property_type` varchar(20) NOT NULL,
		`property_purpose` varchar(20) NOT NULL,
		`home_value` varchar(20) NOT NULL,
		`mortgage_balance` varchar(20) NOT NULL,
		`street_address` varchar(50) NOT NULL,
		`state` varchar(50) NOT NULL,
		`zipcode` varchar(50) NOT NULL,
		`person_age` varchar(50) NOT NULL,
		`date_added` datetime NOT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 
	";
	$wpdb->query($sql);
}

add_action( 'admin_menu', 'mortgage_form_admin_menu');

function mortgage_form_admin_menu() {
	
	add_menu_page(__( 'Mortgage Form', 'homemortgagebank' ), 'Mortgage Form', 'manage_options', 'mortgage_form', 'mortgage_form_init_fn');
	add_submenu_page("mortgage_form", 'Refinance Form', 'Refinance Form', 'manage_options', 'refinance_form','refinance_form_init_fn');
	add_submenu_page("mortgage_form", 'Reverse Mortgage Form', 'Reverse Mortgage Form', 'manage_options', 'reverse_mortgage_form','reverse_mortgage_form_init_fn');
	add_submenu_page("mortgage_form", 'Form Setting', 'Form Setting', 'manage_options', 'form_setting','form_setting_fn');
}

function mortgage_form_init_fn() {
	require_once 'mortgage_form.php';
}

function refinance_form_init_fn() {
	require_once 'refinance_form.php';
}

function reverse_mortgage_form_init_fn() {
	require_once 'reverse_mortgage_form.php';
}

function form_setting_fn() {
	require_once 'form_setting.php';
}


add_action("admin_init","mortgage_admin_enquque_scripts");
function mortgage_admin_enquque_scripts(){
	wp_enqueue_script('mg-common-js',MG_PLUGIN_URL.'/assets/js/common.js', array('jquery'));
	wp_localize_script( 'mg-common-js', 'mg_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action("init","mortgage_front_enquque_scripts");
function mortgage_front_enquque_scripts(){
	wp_enqueue_script('mg-app',MG_PLUGIN_URL.'/assets/js/app.js', array('jquery'));
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_register_style('jqueryUIcss', MG_PLUGIN_URL.'/assets/css/jquery-ui.css');
	wp_enqueue_style('jqueryUIcss');
	wp_register_style('mortgage-css', MG_PLUGIN_URL.'/assets/css/mortgage-form.css');
	wp_enqueue_style('mortgage-css');
	wp_localize_script( 'mg-app', 'mg_ajax', array( 'url' => admin_url( 'admin-ajax.php' ), 'loader_image' =>  MG_PLUGIN_URL.'/assets/img/ajax-loader.gif') );
}

add_shortcode('contact_form', 'refiance_form_html_fn');

function refiance_form_html_fn(){
	global $mortgage_thankyou_msg, $refinance_thankyou_msg, $rev_mortgage_thankyou_msg;
	ob_start();
	$msg = $_REQUEST['msg'];
	?>
	<form method="post" class="refinance_form_container contact_form_container">
		<?php if($msg != ''){
			echo '<div class="form-group">';
			if(isset($msg) && $msg == 'refinance'){
				echo '<div role="alert" class="alert alert-success alert-dismissible"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>'.$refinance_thankyou_msg.'</div>';
			}
			else if(isset($msg) && $msg == 'mortgage'){
				echo '<div role="alert" class="alert alert-success alert-dismissible"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>'.$mortgage_thankyou_msg.'</div>';
			}
			else if(isset($msg) && $msg == 'reverse'){
				echo '<div role="alert" class="alert alert-success alert-dismissible"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button>'.$rev_mortgage_thankyou_msg.'</div>';
			}
			echo '</div>';
		}
		?>
		<div class="form-group">
			<input type="text" placeholder="Name" name="Name" class="form-control">
		</div>
		<div class="form-group">
			<input type="email" placeholder="Email Address" name="Email-Address" class="form-control">
		</div>
		<div class="form-group">
			<input type="tel" onkeyup="ValidateUsPhoneNumber();" onblur="checkphonenumbervalidation(this);" id="Telephone" placeholder="Phone number" name="Telephone" class="form-control">
		</div>
		<div class="form-group">
			<select name="Credit-Score" id="select" class="form-control" style="color: rgb(153, 153, 153);">
				<option selected="" value="">Credit Score</option>
				<option value="Excellent (740 and above)">Excellent (740 and above)</option>
				<option value="Good (700 - 739)">Good (700 - 739)</option>
				<option value="Average (640 - 699)">Average (640 - 699)</option>
				<option value="Fair (600 - 639)">Fair (600 - 639)</option>
				<option value="Can still qualify (570 - 599)">Can still qualify (570 - 599)</option>
				<option value="Not sure of my Credit Score">Not sure of my Credit Score</option>
			</select>
		</div>
		<?php if(is_page('reverse-mortgage')){?>
			<input style="width:208px;" type="submit" value="REVERSE MORTGAGE" id="reverse_mortage_btn" class="left-submit btn btn-primary">
		<?php } else if(is_page('get-a-mortgage')){?>
			<input type="submit" value="GET A MORTGAGE" id="mortage_btn" class="left-submit btn btn-primary">
		<?php } else if(is_page('refinance')){?>
			<input type="submit" value="REFINANCE" id="refinance_btn" class="right-submit btn btn-primary">
		<?php } else if(is_front_page()){?>
			<input type="submit" value="GET A MORTGAGE" id="mortage_btn" class="left-submit btn btn-primary">
			<input type="submit" value="REFINANCE" id="refinance_btn" class="right-submit btn btn-primary">
		<?php }?> 
	</form>
	<?php
	return ob_get_clean();
}

function refiance_form_2_html_fn(){
	ob_start();
	?>
	<form method="post" class="refinance_form_container contact_form_container">
		<div class="form-group">
			<select name="property" class="form-control">
				<option value="">Type of Property</option>
				<option value="Single Family Home">Single Family Home</option>
				<option value="Townhouse / Condo">Townhouse / Condo</option>
				<option value="Multi-Family">Multi-Family</option>
			</select>
		</div>
		<div class="form-group">
			<select name="purpose" class="form-control">
				<option value="">Purpose of Property</option>
				<option value="Primary Home">Primary Home</option>
				<option value="Secondary Home">Secondary Home</option>
				<option value="Rental Property">Rental Property</option>
			</select>
		</div>
		<div class="form-group">
			<select name="value_home" class="form-control">
				<option value="">Value of Home</option>
				<option value="$0-$100,000">$0-$100,000</option>
				<option value="$100,000 - $250,000">$100,000 - $250,000</option>
				<option value="$250,000 - $300,000">$250,000 - $300,000</option>
				<option value="$300,000 - $350,000">$300,000 - $350,000</option>
				<option value="$350,000 &ndash; $400,000">$350,000 &ndash; $400,000</option>
				<option value="$400,000 - $450,000">$400,000 - $450,000</option>
				<option value="$450,000 - $500,000">$450,000 - $500,000</option>
				<option value="$550,000 - $600,000">$550,000 - $600,000</option>
				<option value="$600,000 - $650,000">$600,000 - $650,000</option>
				<option value="$700,000 - $800,000">$700,000 - $800,000</option>
				<option value="$800,000 - $900,000">$800,000 - $900,000</option>
				<option value="$900,000 &ndash; $1,000,000">$900,000 &ndash; $1,000,000</option>
				<option value="$1,000,000 - ABOVE">$1,000,000 - ABOVE</option>
			</select>
		</div>
		<div class="form-group">
			<select name="mortgage_balance" class="form-control">
				<option value="">Balance of Mortgage</option>
				<option value="$10,000 or less">$10,000 or less</option>
				<option value="$10,000 - $30,000">$10,000 - $30,000</option>
				<option value="$30,000 – $50,000">$30,000 – $50,000</option>
				<option value="$50,000 - $75,000">$50,000 - $75,000</option>
				<option value="$75,000 - $100,000">$75,000 - $100,000</option>
				<option value="$100,000 - $125,000">$100,000 - $125,000</option>
				<option value="$125,000 - $150,000">$125,000 - $150,000</option>
				<option value="$150,000 - $175,000">$150,000 - $175,000</option>
				<option value="$175,000 - $200,000">$175,000 - $200,000</option>
				<option value="$200,000 - $225,000">$200,000 - $225,000</option>
				<option value="$225,000 - $250,000">$225,000 - $250,000</option>
				<option value="$250,000 - $275,000">$250,000 - $275,000</option>
				<option value="$275,000 - $300,000">$275,000 - $300,000</option>
				<option value="$300,000 - Above">$300,000 - Above</option>
			</select>
		</div>
		<div class="form-group text-left" style="color: rgb(255, 255, 255);">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="if_second_mortgage" name="if_second_mortgage" style="width: auto; height: auto;"> Do you have a second mortgage? </label>
			</div> 
		</div>
		<div class="form-group">
			<select name="second_mortgage_section" id="second_mortgage_section" class="form-control" style="display:none;">
				<option value="">What is the remaining balance on the 2nd mortgage?</option>
				<option value="$10,000 or less">$10,000 or less</option>
				<option value="$10,000 - $30,000">$10,000 - $30,000</option>
				<option value="$30,000 – $50,000">$30,000 – $50,000</option>
				<option value="$50,000 - $75,000">$50,000 - $75,000</option>
				<option value="$75,000 - $100,000">$75,000 - $100,000</option>
				<option value="$100,000 - $125,000">$100,000 - $125,000</option>
				<option value="$125,000 - $150,000">$125,000 - $150,000</option>
				<option value="$150,000 - more">$150,000 - more</option>
			</select>
		</div>
		<div class="form-group">
			<select name="extra_cash" id="extra_cash" class="form-control">
				<option value="">Are you looking to borrow extra cash?</option>
				<option value="Not looking for extra cash">Not looking for extra cash</option>
				<option value="$1,000 to $5,000">$1,000 to $5,000</option>
				<option value="$5,000 to $10,000">$5,000 to $10,000</option>
				<option value="$10,000 to $15,000">$10,000 to $15,000</option>
				<option value="$15,000 to $20,000">$15,000 to $20,000</option>
				<option value="$20,000 to $25,000">$20,000 to $25,000</option>
				<option value="$25,000 to $35,000">$25,000 to $35,000</option>
				<option value="$35,000 to $45,000">$35,000 to $45,000</option>
				<option value="$45,000 to $55,000">$45,000 to $55,000</option>
				<option value="$55,000 to $65,000">$55,000 to $65,000</option>
				<option value="$65,000 to $75,000">$65,000 to $75,000</option>
				<option value="$75,000 to $85,000">$75,000 to $85,000</option>
				<option value="$85,000 to $95,000">$85,000 to $95,000</option>
			</select>
		</div>		
		<input type="submit" value="Next" id="refinance_step_2" class="right-submit btn btn-primary">
	</form>
	<?php
	return ob_get_clean();
}

function refiance_form_3_html_fn(){
	ob_start();
	?>
	<form method="post" class="refinance_form_container contact_form_container">
		<div class="form-group">
			<?php echo date_of_birth_field();?>
		</div>
		<div class="form-group text-left" style="color: rgb(255, 255, 255);">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="military_serve" name="military_serve" style="width: auto; height: auto;"> You or your spouse ever serve in the military? </label>
			</div> 
		</div>
		<div class="form-group text-left va_loan_section vc_active" style="color: rgb(255, 255, 255); display:none">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="va_loan" name="va_loan" style="width: auto; height: auto;"> Do you have a current VA loan? </label>
			</div> 
		</div>
		<div class="form-group text-left bankruptcy_forecloser va_loan_section bank_active" style="color: rgb(255, 255, 255); display:none">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="bankruptcy_forecloser" name="bankruptcy_forecloser" style="width: auto; height: auto;"> Have you filed a bankruptcy or foreclosure in the last 7 years? </label>
			</div> 
		</div>
		<div class="form-group text-left bankruptcy_forecloser bankruptcy_forecloser_value va_loan_section bank_sec_active" style="color: rgb(255, 255, 255); display:none;">
			<div class="radio"> 
				<label> <input type="radio" name="bankruptcy_forecloser_value" value="Bancruptcy" style="width: auto; height: auto;"> Bancruptcy </label>
				<label> <input type="radio" name="bankruptcy_forecloser_value" value="Foreclosure" style="width: auto; height: auto;"> Foreclosure </label>
				<label> <input type="radio" name="bankruptcy_forecloser_value" value="Both" style="width: auto; height: auto;"> Both </label>
			</div> 
		</div>
		<div class="form-group">
			<input type="text" placeholder="Street Address" name="street_address" class="form-control">
		</div>
		<div class="form-group">
			<input type="text" placeholder="City" name="city" id="city" class="form-control">
		</div>
		<div class="form-group">
			<select class="form-control" name="state"><?php echo StateDropdown(null, 'mixed'); ?></select>
		</div>
		<div class="form-group">
			<input type="text" placeholder="Zip code" name="zipcode" class="form-control">
		</div>
		<input type="hidden" value="refinance" name="msg" />
		<input type="submit" value="GET YOUR QUOTE" id="refinance_submit" class="right-submit btn btn-primary">
	</form>
	<?php
	return ob_get_clean();
}

function mortgage_form_2_html_fn(){
	ob_start();
	?>
	<form method="post" class="mortgage_form_container contact_form_container">
		<div class="form-group">
			<select name="property" class="form-control">
				<option value="">Type of Property</option>
				<option value="Single Family Home">Single Family Home</option>
				<option value="Townhouse / Condo">Townhouse / Condo</option>
				<option value="Multi-Family">Multi-Family</option>
			</select>
		</div>
		<div class="form-group">
			<select name="purpose" class="form-control">
				<option value="">Purpose of Property</option>
				<option value="Primary Home">Primary Home</option>
				<option value="Secondary Home">Secondary Home</option>
				<option value="Rental Property">Rental Property</option>
			</select>
		</div>
		<div class="form-group">
			<input type="text" placeholder="Property city" name="property_city" id="property_city" class="form-control">
		</div>
		<div class="form-group">
			<select class="form-control" name="property_state">
				<option value="">Propery State</option>
				<?php echo StateDropdown(null, 'name'); ?>
			</select>
		</div>
		<div class="form-group">
			<select name="purchase_price" class="form-control">
				<option value="">Purchase Price</option>
				<option value="$0-$100,000">$0-$100,000</option>
				<option value="$100,000 - $250,000">$100,000 - $250,000</option>
				<option value="$250,000 - $300,000">$250,000 - $300,000</option>
				<option value="$300,000 - $350,000">$300,000 - $350,000</option>
				<option value="$350,000 &ndash; $400,000">$350,000 &ndash; $400,000</option>
				<option value="$400,000 - $450,000">$400,000 - $450,000</option>
				<option value="$450,000 - $500,000">$450,000 - $500,000</option>
				<option value="$550,000 - $600,000">$550,000 - $600,000</option>
				<option value="$600,000 - $650,000">$600,000 - $650,000</option>
				<option value="$700,000 - $800,000">$700,000 - $800,000</option>
				<option value="$800,000 - $900,000">$800,000 - $900,000</option>
				<option value="$900,000 &ndash; $1,000,000">$900,000 &ndash; $1,000,000</option>
				<option value="$1,000,000 - ABOVE">$1,000,000 - ABOVE</option>
			</select>
		</div>
		<div class="form-group">
			<select name="down_payment" class="form-control">
				<option value="">Down Payment</option>
				<option value="3 to 6%">3 to 6%</option>
				<option value="6 to 10%">6 to 10%</option>
				<option value="11 to 15%">11 to 15%</option>
				<option value="16 to 20%">16 to 20%</option>
				<option value="21 to 30%">21 to 30%</option>
				<option value="31 to 40%">31 to 40%</option>
				<option value="41 to 50%">41 to 50%</option>
				<option value="ABOVE 50%">ABOVE 50%</option>
			</select>
		</div>
		<input type="submit" value="Next" id="mortgage_step_2" class="right-submit btn btn-primary">
	</form>
	<?php
	return ob_get_clean();
}

function mortgage_form_3_html_fn(){
	ob_start();
	?>
	<form method="post" class="mortgage_form_container contact_form_container">
		<div class="form-group">
			<?php echo date_of_birth_field();?>
		</div>
		<div class="form-group text-left" style="color: rgb(255, 255, 255);">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="military_serve" name="military_serve" style="width: auto; height: auto;"> You or your spouse ever serve in the military? </label>
			</div> 
		</div>
		<div class="form-group text-left va_loan_section vc_active" style="color: rgb(255, 255, 255); display:none">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="va_loan" name="va_loan" style="width: auto; height: auto;"> Do you have a current VA loan? </label>
			</div> 
		</div>
		<div class="form-group text-left bankruptcy_forecloser va_loan_section bank_active" style="color: rgb(255, 255, 255); display:none">
			<div class="checkbox"> 
				<label> <input type="checkbox" value="1" id="bankruptcy_forecloser" name="bankruptcy_forecloser" style="width: auto; height: auto;"> Have you filed a bankruptcy or foreclosure in the last 7 years? </label>
			</div> 
		</div>
		<div class="form-group text-left bankruptcy_forecloser bankruptcy_forecloser_value va_loan_section bank_sec_active" style="color: rgb(255, 255, 255); display:none;">
			<div class="radio"> 
				<label> <input type="radio" name="bankruptcy_forecloser_value" value="Bancruptcy" style="width: auto; height: auto;"> Bancruptcy </label>
				<label> <input type="radio" name="bankruptcy_forecloser_value" value="Foreclosure" style="width: auto; height: auto;"> Foreclosure </label>
				<label> <input type="radio" name="bankruptcy_forecloser_value" value="Both" style="width: auto; height: auto;"> Both </label>
			</div> 
		</div>
		<div class="form-group">
			<input type="text" placeholder="Street Address" name="street_address" class="form-control">
		</div>
		<div class="form-group">
			<input type="text" placeholder="City" id="city" name="city" class="form-control">
		</div>
		<div class="form-group">
			<select class="form-control" name="state">
				<option value="">State</option>
				<?php echo StateDropdown(null, 'name'); ?>
			</select>
		</div>
		<div class="form-group">
			<input type="text" placeholder="Zip code" name="zipcode" class="form-control">
		</div>
		<div class="form-group">
			<select name="annual_income" class="form-control">
				<option value="">What is current annual income?</option>
				<option value="Not employeed">Not employeed</option>
				<option value="Under $20,000">Under $20,000</option>
				<option value="$20,000- to $30,000">$20,000- to $30,000</option>
				<option value="$30,000 to $35,000">$30,000 to $35,000</option>
				<option value="$35,000 to $40,000">$35,000 to $40,000</option>
				<option value="$40,000 to $45,000">$40,000 to $45,000</option>
				<option value="$45,000 to $50,000">$45,000 to $50,000</option>
				<option value="$50,000 to $60,000">$50,000 to $60,000</option>
				<option value="$60,000 to $70,000">$60,000 to $70,000</option>
				<option value="$70,000 to $80,000">$70,000 to $80,000</option>
				<option value="$80,000 to $90,000">$80,000 to $90,000</option>
				<option value="$90,000 to $100,000">$90,000 to $100,000</option>
				<option value="$100,000 to $120,000">$100,000 to $120,000</option>
				<option value="$120,000 and over">$120,000 and over</option>
			</select>
		</div>
		<input type="hidden" value="mortgage" name="msg" />
		<input type="submit" value="GET YOUR QUOTE" id="mortgage_submit" class="right-submit btn btn-primary">
	</form>
	<?php
	return ob_get_clean();
}

function reverse_mortgage_form_2_html_fn(){
	ob_start();
	?>
	<form method="post" class="reverse_mortgage_form_container contact_form_container">
		<div class="form-group">
			<select name="property" class="form-control">
				<option value="">Type of Property</option>
				<option value="Single Family Home">Single Family Home</option>
				<option value="Townhouse / Condo">Townhouse / Condo</option>
				<option value="Multi-Family">Multi-Family</option>
			</select>
		</div>
		<div class="form-group">
			<select name="purpose" class="form-control">
				<option value="">Purpose of Property</option>
				<option value="Primary Home">Primary Home</option>
				<option value="Secondary Home">Secondary Home</option>
				<option value="Rental Property">Rental Property</option>
			</select>
		</div>
		<div class="form-group">
			<select name="value_home" class="form-control">
				<option value="">Value of Home</option>
				<option value="$0-$100,000">$0-$100,000</option>
				<option value="$100,000 - $250,000">$100,000 - $250,000</option>
				<option value="$250,000 - $300,000">$250,000 - $300,000</option>
				<option value="$300,000 - $350,000">$300,000 - $350,000</option>
				<option value="$350,000 &ndash; $400,000">$350,000 &ndash; $400,000</option>
				<option value="$400,000 - $450,000">$400,000 - $450,000</option>
				<option value="$450,000 - $500,000">$450,000 - $500,000</option>
				<option value="$550,000 - $600,000">$550,000 - $600,000</option>
				<option value="$600,000 - $650,000">$600,000 - $650,000</option>
				<option value="$700,000 - $800,000">$700,000 - $800,000</option>
				<option value="$800,000 - $900,000">$800,000 - $900,000</option>
				<option value="$900,000 &ndash; $1,000,000">$900,000 &ndash; $1,000,000</option>
				<option value="$1,000,000 - ABOVE">$1,000,000 - ABOVE</option>
			</select>
		</div>
		<div class="form-group">
			<select name="mortgage_balance" class="form-control">
				<option value="">Balance of Mortgage</option>
				<option value="$10,000 or less">$10,000 or less</option>
				<option value="$10,000 - $30,000">$10,000 - $30,000</option>
				<option value="$30,000 – $50,000">$30,000 – $50,000</option>
				<option value="$50,000 - $75,000">$50,000 - $75,000</option>
				<option value="$75,000 - $100,000">$75,000 - $100,000</option>
				<option value="$100,000 - $125,000">$100,000 - $125,000</option>
				<option value="$125,000 - $150,000">$125,000 - $150,000</option>
				<option value="$150,000 - $175,000">$150,000 - $175,000</option>
				<option value="$175,000 - $200,000">$175,000 - $200,000</option>
				<option value="$200,000 - $225,000">$200,000 - $225,000</option>
				<option value="$225,000 - $250,000">$225,000 - $250,000</option>
				<option value="$250,000 - $275,000">$250,000 - $275,000</option>
				<option value="$275,000 - $300,000">$275,000 - $300,000</option>
				<option value="$300,000 - Above">$300,000 - Above</option>
			</select>
		</div>
		<input type="submit" value="Next" id="reverse_mortgage_step_2" class="right-submit btn btn-primary">
	</form>
	<?php
	return ob_get_clean();
}

function reverse_mortgage_form_3_html_fn(){
	ob_start();
	?>
	<form method="post" class="reverse_mortgage_form_container contact_form_container">
		<div class="form-group">
			<input type="text" placeholder="Street Address" name="street_address" class="form-control">
		</div>
		<div class="form-group">
			<select class="form-control" name="state"><?php echo StateDropdown(null, 'mixed'); ?></select>
		</div>
		<div class="form-group">
			<input type="text" placeholder="Zip code" name="zipcode" class="form-control">
		</div>
		<div class="form-group">
			<select id="person_age" name="person_age" class="form-control">
				<option value="">What is the age of at least one person?</option>
				<?php for ($i = 62; $i <= 100; $i++) {?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php }?>
			</select>
		</div>
		<input type="hidden" value="reverse" name="msg" />
		<input type="submit" value="SUBMIT" id="reverse_mortgage_submit" class="right-submit btn btn-primary">
	</form>
	<?php
	return ob_get_clean();
}

function date_of_birth_field(){
	?>
	<div class="text-left" style="color:#fff;">
		<label>Date of Birth</label>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<select id="selectMonth" name="selectMonth" class="form-control">
				<option value="">Month</option>
				<?php for ($i = 1; $i <= 12; $i++){ ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-xs-4">
			<select id="selectDate" name="selectDate" class="form-control">
				<option value="">Date</option>
				<?php for ($i = 1; $i <= 31; $i++) {?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php }?>
			</select>
		</div>
		<div class="col-xs-4">
			<select id="selectYear" name="selectYear" class="form-control">
				<option value="">Year</option>
				<?php for ($i = 2016; $i >= 1900; $i--) { ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php }?>
			</select>
		</div>
	</div>
	<?php
}

function StateDropdown($post=null, $type='abbrev') {
	$states = array(
		array('AK', 'Alaska'),
		array('AL', 'Alabama'),
		array('AR', 'Arkansas'),
		array('AZ', 'Arizona'),
		array('CA', 'California'),
		array('CO', 'Colorado'),
		array('CT', 'Connecticut'),
		array('DC', 'District of Columbia'),
		array('DE', 'Delaware'),
		array('FL', 'Florida'),
		array('GA', 'Georgia'),
		array('HI', 'Hawaii'),
		array('IA', 'Iowa'),
		array('ID', 'Idaho'),
		array('IL', 'Illinois'),
		array('IN', 'Indiana'),
		array('KS', 'Kansas'),
		array('KY', 'Kentucky'),
		array('LA', 'Louisiana'),
		array('MA', 'Massachusetts'),
		array('MD', 'Maryland'),
		array('ME', 'Maine'),
		array('MI', 'Michigan'),
		array('MN', 'Minnesota'),
		array('MO', 'Missouri'),
		array('MS', 'Mississippi'),
		array('MT', 'Montana'),
		array('NC', 'North Carolina'),
		array('ND', 'North Dakota'),
		array('NE', 'Nebraska'),
		array('NH', 'New Hampshire'),
		array('NJ', 'New Jersey'),
		array('NM', 'New Mexico'),
		array('NV', 'Nevada'),
		array('NY', 'New York'),
		array('OH', 'Ohio'),
		array('OK', 'Oklahoma'),
		array('OR', 'Oregon'),
		array('PA', 'Pennsylvania'),
		array('PR', 'Puerto Rico'),
		array('RI', 'Rhode Island'),
		array('SC', 'South Carolina'),
		array('SD', 'South Dakota'),
		array('TN', 'Tennessee'),
		array('TX', 'Texas'),
		array('UT', 'Utah'),
		array('VA', 'Virginia'),
		array('VT', 'Vermont'),
		array('WA', 'Washington'),
		array('WI', 'Wisconsin'),
		array('WV', 'West Virginia'),
		array('WY', 'Wyoming')
	);
	
	$options = '';
	
	foreach ($states as $state) {
		if ($type == 'abbrev') {
    	$options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[0].'</option>'."\n";
    } elseif($type == 'name') {
    	$options .= '<option value="'.$state[1].'" '. check_select($post, $state[1], false) .' >'.$state[1].'</option>'."\n";
    } elseif($type == 'mixed') {
    	$options .= '<option value="'.$state[0].'" '. check_select($post, $state[0], false) .' >'.$state[1].'</option>'."\n";
    }
	}
		
	echo $options;
}

/**
 * Check Select Element 
 *
 * @param string $i, POST value
 * @param string $m, input element's value
 * @param string $e, return=false, echo=true 
 * @return string 
 */
function check_select($i,$m,$e=true) {
	if ($i != null) { 
		if ( $i == $m ) { 
			$var = ' selected="selected" '; 
		} else {
			$var = '';
		}
	} else {
		$var = '';	
	}
	if(!$e) {
		return $var;
	} else {
		echo $var;
	}
}

$to = get_option('form_to_email');
$to = explode(',', $to);

if(!$to){
	$admin_email = get_option('admin_email');
	$to = $admin_email;
}

$mortgage_thankyou_msg = get_option('mortgage_thankyou_msg');

if(!$mortgage_thankyou_msg){
	$mortgage_thankyou_msg = 'Thank you for completing our form. We will contact you shortly with the best rate and any other questions you may have to get your loan processed quickly';
}

$refinance_thankyou_msg = get_option('refinance_thankyou_msg');

if(!$refinance_thankyou_msg){
	$refinance_thankyou_msg = 'Thank you for completing our form. We will contact you shortly with the best rate and any other questions you may have to get your loan processed quickly';
}

$rev_mortgage_thankyou_msg = get_option('rev_mortgage_thankyou_msg');

if(!$rev_mortgage_thankyou_msg){
	$rev_mortgage_thankyou_msg = 'Thank you for completing our form. We will contact you shortly with the best rate and any other questions you may have to get your loan processed quickly';
}
