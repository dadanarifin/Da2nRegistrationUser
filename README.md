Da2nRegistrationUser
====================

Add user meta fields for registration new user

Example add field facebook and twitter

$extra_fields =  array( 
  		array( 'facebook', __('Facebook Username', 'dak'), true ),
			array( 'twitter', __('Twitter Username', 'dak'), true ),
			);
      

Example exclude field facebook and twitter in column user list

$exclude_fields = array('facebook','twitter');
