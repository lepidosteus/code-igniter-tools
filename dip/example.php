<?php
// change the path to point to wherever your application/tools/ci_core.php file is
require('/var/www/my_ci_app/application/tools/ci_core.php');

// that's it, you can request your $ci object
$ci = get_instance();

// use it the same way you're used to
$ci->load->model('user');
$user = $ci->user->get_by_id(123);
