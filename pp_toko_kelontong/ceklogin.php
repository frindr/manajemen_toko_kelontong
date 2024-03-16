<?php
    if(isset($_SESSION['log_in'])){

    } else{
        header('location:login.php');
    }
?>