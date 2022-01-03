<?php

use Hashids\Hashids;
// It works only for ID(integer).
function id2hash($id){
    $hashids = new Hashids('magicpay123!@#',8);
    return $hashids->encode($id);
}

function hash2id($hash){
    $hashids = new Hashids('magicpay123!@#',8);
    return $hashids->decode($hash)[0];
}
?>
