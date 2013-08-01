<?php
//custom meta users
global $extra_fields, $exclude_fields;
// Set fields
/*
example : array( 'facebook', __('Facebook Username', 'dak'), true ),
*/
$extra_fields =  array( 
  		array( 'facebook', __('Facebook Username', 'dak'), true ),
			array( 'twitter', __('Twitter Username', 'dak'), true ),
			array( 'company_name', __('Company Name', 'dak'), true ),
			array( 'mobile_number', __('Phone Number', 'dak'), true )
			);
			
//Exclude from column list users
$exclude_fields = array('facebook','twitter');
			
function dak_add_user_contactmethods( $user_contactmethods ) {

	// Get fields
    global $extra_fields;
	
	// Display each fields
	foreach( $extra_fields as $field ) {
		if ( !isset( $contactmethods[ $field[0] ] ) )
    		$user_contactmethods[ $field[0] ] = $field[1];
	}

    // Returns the contact methods
    return $user_contactmethods;
}
// Use the user_contactmethods to add new fields
add_filter( 'user_contactmethods', 'dak_add_user_contactmethods' );


function dak_register_form_display_extra_fields() {
    
    // Get fields
    global $extra_fields;

    // Display each field if 3th parameter set to "true"
    foreach( $extra_fields as $field ) {
    	if( $field[2] == true ) { 
    	if( isset( $_POST[ $field[0] ] ) ) { $field_value = $_POST[ $field[0] ]; } else { $field_value = ''; }
    	?>
	    <p>
            <label for="<?php echo $field[0]; ?>"><?php echo $field[1]; ?><br />
            <input type="text" name="<?php echo $field[0]; ?>" id="<?php echo $field[0]; ?>" class="input" value="<?php echo $field_value; ?>" size="20" /></label>
            </label>
	    </p>
	    <?php
    	} // endif
    } // end foreach
}
// Add our fields to the registration process
add_action( 'register_form', 'dak_register_form_display_extra_fields' );

function dak_user_register_save_extra_fields( $user_id, $password = '', $meta = array() )  {

	// Get fields
    global $extra_fields;
    
    $userdata       = array();
    $userdata['ID'] = $user_id;
    
    // Save each field
    foreach( $extra_fields as $field ) {
    	if( $field[2] == true ) { 
	    	$userdata[ $field[0] ] = $_POST[ $field[0] ];
	    } // endif
	} // end foreach

    $new_user_id = wp_update_user( $userdata );
}
add_action( 'user_register', 'dak_user_register_save_extra_fields', 100 );



///////column user
add_filter('manage_users_columns', 'dak_add_user_id_column');
function dak_add_user_id_column($columns) {
    
	// Get fields
    global $extra_fields, $exclude_fields;

    foreach( $extra_fields as $field ) {
		if ( !in_array($field[0], $exclude_fields) ) $columns[$field[0]] = $field[1];
    } // end foreach
	
    return $columns;
}
 
add_action('manage_users_custom_column',  'dak_show_user_id_column_content', 10, 3);
function dak_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	
	// Get fields
    global $extra_fields, $exclude_fields;
	
    foreach( $extra_fields as $field ) {
		if ( $field[0] == $column_name && !in_array($field[0], $exclude_fields) ) return get_user_meta($user_id, $field[0], true);
    } // end foreach
	
	
    return $value;
}
