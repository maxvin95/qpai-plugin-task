<?php
/*
Plugin Name: Qpai Form Plugin
Description: A simple plugin that allows you to perform Create form data and sqave it to wordpress database.
Version: 1.0.0
Author: M.Mathan Raj
*/
register_activation_hook( __FILE__, 'qpaiformTable');
function qpaiformTable() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'userstable';
  $sql = "CREATE TABLE `$table_name` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(220) DEFAULT NULL,
  `email` varchar(220) DEFAULT NULL,
  PRIMARY KEY(user_id)
  ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
  ";
  if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}
add_action('admin_menu', 'addAdminPageContent');
function addAdminPageContent() {
  add_menu_page('Qpai Form', 'Qpai Form', 'manage_options' ,__FILE__, 'qpaiAdminPage', 'dashicons-wordpress');
}
function qpaiAdminPage() {
  global $wpdb;
  $table_name = $wpdb->prefix . 'userstable';
  ?>
  <div class="wrap">
    <h2 style="text-align:center;">Qpai Plugin Form Data</h2>
    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th width="25%">User ID</th>
          <th width="25%">Name</th>
          <th width="25%">Email Address</th>
   
        </tr>
      </thead>
      <tbody>
 
        <?php
          $result = $wpdb->get_results("SELECT * FROM $table_name");
          foreach ($result as $print) {
            echo "
              <tr>
                <td width='25%'>$print->user_id</td>
                <td width='25%'>$print->name</td>
                <td width='25%'>$print->email</td>
              </tr>
            ";
          }
        ?>
      </tbody>  
    </table>
    <br>
    <br>
  </div>
  <?php
}

 
function qpaifrontpage() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'userstable';
	if (isset($_POST['newsubmit'])) {
	  $name = $_POST['newname'];
	  $email = $_POST['newemail'];
	  $wpdb->query("INSERT INTO $table_name(name,email) VALUES('$name','$email')");
    echo 'Data Inserted Successfully';
	}
  ?>
	<div class="wrap">
	  <h2 style="text-align:center;">Qpai Plugin Form</h2>
	  <table class="wp-list-table widefat striped">
		<thead>
		  <tr>
			<th width="25%">User ID</th>
			<th width="25%">Name</th>
			<th width="25%">Email Address</th>
			<th width="25%">Actions</th>
		  </tr>
		</thead>
		<tbody>
		  <form action="" method="post">
			<tr>
			  <td><input type="text" value="AUTO_GENERATED" disabled></td>
			  <td><input type="text" id="newname" name="newname"></td>
			  <td><input type="text" id="newemail" name="newemail"></td>
			  <td><button id="newsubmit" name="newsubmit" type="submit">INSERT</button></td>
			</tr>
		  </form>
		  <?php
			$result = $wpdb->get_results("SELECT * FROM $table_name");
			foreach ($result as $print) {
			  echo "
				<tr>
				  <td width='30%%'>$print->user_id</td>
				  <td width='30%'>$print->name</td>
				  <td width='40%'>$print->email</td>
				</tr>
			  ";
			}
		  ?>
		</tbody>  
	  </table>
	  <br>
	  <br>
	</div>
	<?php
  }
  
   
  
  add_shortcode('qpai_plugin_form', 'qpaifrontpage');


  add_action( 'rest_api_init', function () {
    register_rest_route( 'qpai/v1', '/all', array(
      'methods' => 'GET',
      'callback' => 'handle_get_all'
    
    ) );
  } );
  
  function handle_get_all( $data ) {
      global $wpdb;
      $query = "SELECT * FROM wp_userstable";
      $list = $wpdb->get_results($query);
      return $list;
  }
  