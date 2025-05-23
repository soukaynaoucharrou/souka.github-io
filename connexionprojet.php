<?php 
function connect(){
    $base = mysqli_connect('localhost','root','','ecommerce','3307');
    return $base;
}
?>