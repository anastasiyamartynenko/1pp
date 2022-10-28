<?php
//usort($array, function($a,$b){
//    return ($a['num']-$b['num']);
//});
require_once 'vendor/autoload.php';
\AmoCRM\AmoAPI::oAuth2('jysaninvest');
$users = \AmoCRM\AmoAPI::getAccount('users')['_embedded']['users'];
$callCenter = [];
foreach ($users as $user) {
    if($user['group_id'] == 317869) {
        $callCenter[] = ['id'=>$user['id'], 'count'=>0];
    }
}
file_put_contents('users.json',json_encode($callCenter));
print_r($callCenter);