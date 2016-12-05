<?php

/*
Plugin Name: GothamBullies
Description: My Sample
Author: Jimssell Morales
VersionL 1.000
*/
register_activation_hook(__FILE__, 'active_hook');

function active_hook(){
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
}

add_shortcode('active_hook','active_hook');


add_shortcode('insertuser','insertuser');
function insertuser(){

$string = '<form method="post" action="">';
$string .= '<div class="form-group"><label>Email address</label><input type="email" class="form-control" name="email" placeholder="Email"></input></div>';
$string .= '<div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" placeholder="password"></div>';
$string .= '<div class="form-group"><label>First name</label><input type="text" class="form-control" name="name" placeholder="fitst name"></input></div>';
$string .= '<div class="form-group"><label>Last name</label><input type="text" class="form-control" name="lname" placeholder="Last name"></input></div>';
$string .= '<div class="form-group"><label>Store name</label><input type="text" class="form-control" name="storename" placeholder="Your Store name"></input></div>';
$string .= '<input type="submit" class="btn btn-primary" value="Submit"></input>';
$string .= '</form>';
return $string;

	if(isset($_POST['email'])){
		$form = array();
		parse_str($_POST['form'], $form);
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$result = $wpdb->insert(
			'pb_account_users',array(
				'user_primary_email' => $_POST['email'],
				'user_password' => $_POST['password'],
				'user_first_name' => $_POST['name'],
				'user_last_name' => $_POST['lname'],
				'user_store_name' => $_POST['storename'],
				'user_unique_key' => md5(microtime() . rand())));
			    if($result){
echo '<script type="text/javascript">alert("hello");</script>';
			    }
			    $id = $wpdb->insert_id;
			    if (is_wp_error($id)) {
			        $errors = $id->get_error_messages();
			        foreach ($errors as $error) {
			            echo $error;
			        }
			    } else {
			        echo $id;
			    }
			    die();

	}
}

add_shortcode('active_hook','active_hook');
?>

