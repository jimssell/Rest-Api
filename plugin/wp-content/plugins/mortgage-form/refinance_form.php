<?php
global $wpdb;
$table = $wpdb->prefix.'refinance_form_record';

if($_REQUEST['action']=='delete' || $_REQUEST['action2']=='delete')
{
	
	if(isset($_REQUEST['ids']) && $_REQUEST['_wpnonce']!='')
	{
		foreach($_REQUEST['ids'] as $item)
		{	
			$wpdb->delete($table, array('id'=>$item));
			$_GET['msg']="delete";
		}
	}
	else if($_REQUEST['id'])
	{	
		$wpdb->delete($table, array('id'=>$_REQUEST['id']));
		$_GET['msg']="delete";
	}
}
if( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Refinance_form_Table extends WP_List_Table {
	
	function __construct(){
		global $status, $page;
			parent::__construct( array(
				'singular'  => "Refinance Form",
				'plural'    => "Refinance Form",
				'ajax'      => false
	
		) );	
	}
	
	function get_columns(){
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'id' => 'Id',
			'name' => 'Name',
			'email' => 'Email',
			'phone'  =>'Phone',
			'credit' => 'Credit Score',
			'incomplete_form' => 'Form Status',
			'date_added' => 'Date Added',
		);
		return $columns;
	}
	function get_sortable_columns() {
		$sortable_columns = array(
			'id'  => array('id',true),
			'name'  => array('name',false),
			'email'  => array('email',false),
			'phone'  => array('phone',false),
			'credit'  => array('credit',false),
			'date_added'  => array('date_added',false),
		);
		return $sortable_columns;
	}
	function get_bulk_actions() {
		  $actions = array(
			'delete' => 'Delete'
		  );
		  return $actions;
	}
	function column_cb($item) {
		return sprintf(
			'<input type="checkbox" name="ids[]" value="%s" />', $item->id
		);    
	}
	
	function column_id($item){
		
		$class = '<span class="add_ui_class"></span>';
		//Build row actions
		$actions = array(
			//'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item->id),
			'view'    => sprintf('<a href="javascript:void(0);" onclick="view_this_item(%s)">View</a>',$item->id, 'view',$item->id),
			'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_REQUEST['page'],'delete',$item->id),
		);
		
		//Return the title contents
		return sprintf('%1$s %2$s %3$s',
			/*$2%s*/ $item->id,
			/*$3%s*/ $this->row_actions($actions),
			$class                
		);
	}
	function prepare_items() {
		global $wpdb,$_wp_column_headers;
		$prefix=$wpdb->prefix;
		$screen = get_current_screen();
		
		$search_text=!empty($_POST['s'])?$_POST['s']:"";
		
		$where="";
		
		if(!empty($search_text))
			$where.="and name LIKE '%".$search_text."%' ";
			
		$form_filter=!empty($_POST['form_filter'])?$_POST['form_filter']:"";
		
		if($form_filter == 'full'){
			$where.="and incomplete_form = '1'";
		}
		if($form_filter == 'half'){
			$where.="and incomplete_form = '0'";
		}
		
		/* -- Preparing your query -- */
			$query = "SELECT * FROM ".$prefix."refinance_form_record where 1=1 ".$where;
			
		/* -- Ordering parameters -- */
			//Parameters that are going to be used to order the result
			$orderby = !empty($_GET["orderby"]) ? ($_GET["orderby"]) : 'id';
			$order = !empty($_GET["order"]) ? ($_GET["order"]) : 'DESC';
			if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
	
		/* -- Pagination parameters -- */
			//Number of elements in your table?
			$totalitems = $wpdb->query($query); //return the total number of affected rows
			//How many to display per page?
			$perpage = 20;
			//Which page is this?
			$paged = !empty($_GET["paged"]) ? ($_GET["paged"]) : '';
			//Page Number
			if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; }
			//How many pages do we have in total?
			$totalpages = ceil($totalitems/$perpage);
			//adjust the query to take pagination into account
			if(!empty($paged) && !empty($perpage)){
				$offset=($paged-1)*$perpage;
				$query.=' LIMIT '.(int)$offset.','.(int)$perpage;
			}
	
		/* -- Register the pagination -- */
			$this->set_pagination_args( array(
				"total_items" => $totalitems,
				"total_pages" => $totalpages,
				"per_page" => $perpage,
			) );
			//The pagination links are automatically built according to those parameters
	
		/* -- Register the Columns -- */
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			
			$this->_column_headers = array($columns, $hidden, $sortable);
			
			$_wp_column_headers[$screen->id]=$columns;
			
			
		/* -- Fetch the items -- */
			$this->items = $wpdb->get_results($query);
			
		}

	function column_default( $item, $column_name ) {
		global $wpdb;
		switch( $column_name ) { 
			case 'id':
			case 'name':
			case 'email':
			case 'phone':                    
			case 'credit':
				return _e($item->$column_name,'homemortgagebank');	
			case 'incomplete_form':
				if($item->incomplete_form == 0){
					$status = 'Form Incomplete';
				}
				else{
					$status = 'Form complete';
				}
				return $status;
			case 'date_added':
				/*return date('F j, Y, g:i a');*/
                                return date("F j, Y, g:i a", strtotime($item->date_added));
				//return _e($item->$column_name,'homemortgagebank');	
			default:
				return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
		}
	 }
}
$wp_list_table = new Refinance_form_Table();
$wp_list_table->prepare_items();
?>

<div class="wrap">
<h2 id="setting_nav" class="nav-tab-wrapper"> <a class="nav-tab" href="admin.php?page=mortgage_form">Mortgage Form</a> <span class="nav-tab nav-tab-active">Refinance Form</span> <a class="nav-tab" href="admin.php?page=reverse_mortgage_form">Reverse Mortgage Form</a> <a class="nav-tab" href="admin.php?page=form_setting">Form Setting</a></h2>
<h1></h1>
  <div id="response-message">
   <?php if($_GET['msg']){ ?>
   <div class="notice notice-success">
	<?php if($_GET['msg'] == 'delete'){ ?>
		<p>Record successfully deleted.</p><?php 
	}?>
	</div>
	<?php }?>
   </div>
  <?php
   $form_filter = $_POST['form_filter'];
   ?>
  <form method="post">
	  <select name="form_filter" id="form_filter">
		   <option <?php if(@$form_filter == ''){ echo 'selected'; }?> value="">All Entries</option>
		   <option <?php if(@$form_filter == 'half'){ echo 'selected'; }?> value="half">Half Entries</option>
		   <option <?php if(@$form_filter == 'full'){ echo 'selected'; }?> value="full">Full Entries</option>
	   </select>
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
	<?php $wp_list_table->search_box( 'search', 'search_id' );?>
	<?php $wp_list_table->display() ?>
  </form>
</div>

<?php add_thickbox(); ?>
<div id="view_result_block" style="display:none;">
  
</div>
