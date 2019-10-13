<?php

  include('./classes/DB.php');



  if(Login::isLoggedIn()){
    echo 'Logged in';
    echo Login::isLoggedIn();
  }
  else{
    echo 'Not Logged in';
  }

 ?>
