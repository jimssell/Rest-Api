<?php 
    /*
    Plugin Name: Control Panel
    Plugin URI: GothamBullies
    Description: Plugin for displaying products from an OSCommerce shopping cart database
    Author: Jimssell Morales
    Version: 1.0
    Author URI: GothamBullies.com
    */


    
register_activation_hook(__FILE__, 'pb_account_activate');

function pb_account_activate() {
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    $sql = "CREATE TABLE IF NOT EXISTS pb_account_users (
                user_id int    NOT NULL AUTO_INCREMENT,
                user_primary_email varchar(50)    NOT NULL ,
                user_password varchar(50)    NOT NULL ,
                user_date_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                user_first_name varchar(50)    NOT NULL ,
                user_last_name varchar(50)    NOT NULL ,
                user_store_name varchar(50)    NULL ,
                user_unique_key varchar(50)    NOT NULL ,
                CONSTRAINT pb_account_users_pk PRIMARY KEY (user_id)
             ) ENGINE InnoDB;";
    dbDelta($sql);
    $sql = 'CREATE TABLE IF NOT EXISTS pb_account_license (
            license_user_id int NOT NULL AUTO_INCREMENT,
            license_user_email varchar(50)    NOT NULL ,
            license_key varchar(50)    NOT NULL ,
            license_date_generated timestamp    NOT NULL ,
            license_expiration_date timestamp    NULL ,
            license_plan_type varchar(50)    NOT NULL DEFAULT "{\'TYPE\': \'FREE\'}" ,
            license_payment_return_token varchar(50)    NULL ,
            license_product_id int    NOT NULL ,
            license_product_name varchar(50)    NOT NULL ,
            license_info_license_id int    NOT NULL ,
            posbang_user_user_id int    NOT NULL,
            CONSTRAINT pb_account_license_pk PRIMARY KEY (license_user_id)
         ) ENGINE InnoDB;';
    dbDelta($sql);
    $sql = "CREATE TABLE IF NOT EXISTS  pb_account_products (
                product_id int    NOT NULL AUTO_INCREMENT,
                product_name varchar(50)    NOT NULL ,
                product_description varchar(50)    NOT NULL ,
                product_url varchar(50)    NOT NULL ,
                CONSTRAINT pb_account_products_pk PRIMARY KEY (product_id)
             ) ENGINE InnoDB;";
    dbDelta($sql);
    $sql = "ALTER TABLE pb_account_license ADD CONSTRAINT license_user FOREIGN KEY license_user (posbang_user_user_id)   REFERENCES pb_account_users (user_id)   ON DELETE RESTRICT;";
    dbDelta($sql);
    $sql = "ALTER TABLE pb_account_products ADD CONSTRAINT product_license FOREIGN KEY product_license ()   REFERENCES pb_account_license ();";
    dbDelta($sql);
}

add_action( 'admin_menu', 'control_panel_admin_menu');
function control_panel_admin_menu() {


	
	add_menu_page(__( 'control Panel', 'mbee.tracktool' ), 'Control Panel', 'manage_options', 'control_panel', 'control_panel_form_init_from');

	add_submenu_page("control_panel", 'Reseller Server Allocation Request', 'Reseller Server Allocation Request', 'manage_options', 'reseller_server_ allocation_request','reseller_server_allocation_request');	

	add_submenu_page("control_panel", 'Server Overview', 'Server Overview', 'manage_options', 'server_overview','server_overview_form_init_form');

	add_submenu_page("control_panel", 'Server Overview', 'Reseller allocation', 'manage_options', 'reseller_allocation_form','reseller_allocation_form_init_form');	
}

function control_panel_form_init_from() {
	require_once 'control_panel.php';
}

function reseller_server_allocation_request() {
	require_once 'control_panel.php';
}


function server_overview_form_init_form() {
	require_once 'server_overview.php';
}

function reseller_allocation_form_init_form() {
	require_once 'control_panel.php';
}




?>