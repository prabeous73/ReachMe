<?php

  include('classes/DB.php');

  if(isset($_POST['createaccount'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if(!DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$username))){

      if(strlen($username) >= 3 && strlen($username <= 32)){

        if(preg_match('/[a-zA-Z0-9]+/', $username)){

          if(strlen($password)>=6 && strlen($password) <= 60){

            if(filter_var($email, FILTER_VALIDATE_EMAIL)){

              if(!DB::query('SELECT email FROM users WHERE email=:email', array(':email'=>$email))){

                DB::query('INSERT INTO users VALUES (\'\', :username, :password, :email, \'0\')', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                echo "Success!";
              }
              else{
                echo 'Email in use!';
              }

            }
            else{
              echo 'Invalid email!';
            }
          }
          else{
            echo('Invalid password!');
          }
        }
        else{
          echo 'Invalid username!';
        }
      }


    }
  }


 ?>


<h1> Register </h1>

<!-- Sign up form -->
<form action="create-account.php" method="POST">
  <input type="text" name="username" placeholder="USERNAME . . ."><p />
  <input type="password" name="password" placeholder="PASSWORD . . ."><p />
  <input type="email" name="email" placeholder="someone@somesite.com"><p />
  <input type="submit" name="createaccount" value="Create Account">
</form>
