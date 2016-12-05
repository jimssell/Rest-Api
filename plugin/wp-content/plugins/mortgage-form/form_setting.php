<?php 
if(isset($_POST['submit_setting'])){
	$to_email = $_POST['to_email'];
	update_option('form_to_email', $to_email);
	$mortgage_thankyou = $_POST['mortgage_thankyou'];
	update_option('mortgage_thankyou_msg', $mortgage_thankyou);
	$refinance_thankyou = $_POST['refinance_thankyou'];
	update_option('refinance_thankyou_msg', $refinance_thankyou);
	$rev_mortgage_thankyou = $_POST['rev_mortgage_thankyou'];
	update_option('rev_mortgage_thankyou_msg', $rev_mortgage_thankyou);
	$msg = 'success';
}
$to_email = get_option('form_to_email');
$mortgage_thankyou = get_option('mortgage_thankyou_msg');
$refinance_thankyou = get_option('refinance_thankyou_msg');
$rev_mortgage_thankyou = get_option('rev_mortgage_thankyou_msg');
?>
<div class="wrap">
	<h2 id="setting_nav" class="nav-tab-wrapper"> <a class="nav-tab" href="admin.php?page=mortgage_form">Mortgage Form</a> <a class="nav-tab" href="admin.php?page=refinance_form">Refinance Form</a> <a class="nav-tab" href="admin.php?page=reverse_mortgage_form">Reverse Mortgage Form</a> <span class="nav-tab nav-tab-active">Form Setting</span></h2>
	<h1></h1>
	<?php if(isset($msg) && $msg == 'success') { ?>
		<div class="updated notice notice-success is-dismissible" id="message">
			<p>Form settings saved successfully.</p>
			<button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
	<?php } ?>
	<form method="post">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label>To Email Address</label></th>
                                        <td><input type="text" class="widefat" id="to_email" name="to_email" value="<?php echo @$to_email;?>" /><br/><p>Note: Add multiple email's with comma.</p></td>
				</tr>
				<tr>
					<th scope="row"><label>Mortgage Form Thank you Message</label></th>
					<td><textarea class="widefat" cols="10" rows="10" id="mortgage_thankyou" name="mortgage_thankyou"><?php echo @$mortgage_thankyou;?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label>Refinance Form Thank you Message</label></th>
					<td><textarea class="widefat" cols="10" rows="10" id="refinance_thankyou" name="refinance_thankyou"><?php echo @$refinance_thankyou;?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label>Reverse Mortgage Form Thank you Message</label></th>
					<td><textarea class="widefat" cols="10" rows="10" id="rev_mortgage_thankyou" name="rev_mortgage_thankyou"><?php echo @$rev_mortgage_thankyou;?></textarea></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" value="Save" class="button button-primary" id="submit_setting" name="submit_setting"></p>
	</form>
</div>
