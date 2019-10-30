<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');

  $isFollowing = False;
  $verified = False;

  if(isset($_GET['username'])){
    //check if username exists
    if(DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))){
      $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['username'];
      $user_id = DB::query('SELECT id FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['id'];
      $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username'=>$_GET['username']))[0]['verified'];
      $follower_id = Login::isLoggedIn();


      if(isset($_POST['follow'])){

        //check if the user is same as follower
        if($user_id!=$follower_id){

          //check if the user is already followed
          if(!DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$user_id, ':followerid'=>$follower_id))){
            //check if the follower is 'verified' account
            if($follower_id == 5){
              DB::query('UPDATE users SET verified=1 WHERE id=:userid', array(':userid'=>$user_id));
            }
            //follow
            DB::query('INSERT INTO followers VALUES ( \'\', :userid, :followerid)', array(':userid'=>$user_id, ':followerid'=>$follower_id));
          }
          else{
            echo 'Already following!';
          }

          $isFollowing = True;
        }
      }


      if(isset($_POST['unfollow'])){

        //check if the user is same as follower
        if($user_id!=$follower_id){

          //check if the user is already followed
          if(DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$user_id, ':followerid'=>$follower_id))){
            //check if the unfollower is 'verified' account
            if($follower_id == 5){
              DB::query('UPDATE users SET verified=0 WHERE id=:userid', array(':userid'=>$user_id));
            }
            //unfollow
            DB::query('DELETE FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$user_id, ':followerid'=>$follower_id));
          }

          $isFollowing = False;
        }
      }


      if(DB::query('SELECT follower_id FROM followers WHERE user_id=:userid AND follower_id=:followerid', array(':userid'=>$user_id, ':followerid'=>$follower_id))){
        $isFollowing = True;
      }


      if(isset($_POST['post'])){
        Post::createPost($_POST['postbody'], Login::isLoggedIn(), $user_id);
        }

      }

      if(isset($_GET['postid'])){
        Post::likePost($_GET['postid'], $follower_id);
      }

      $posts = Post::displayPosts($user_id, $username, $follower_id);

    }
    else{
      die('user not found');
    }


 ?>


<h1><?php echo $username ?>'s profile  <?php if($verified){echo '-verified';} ?> </h1>
<form action="profile.php?username=<?php echo $username; ?>" method="post">
  <?php
  if($user_id!=$follower_id){
    if($isFollowing){
      echo '<input type="submit" name="unfollow" value="Unfollow">';
    }
    else{
      echo '<input type="submit" name="follow" value="Follow">';
    }
  }
   ?>
</form>


<form action="profile.php?username=<?php echo $username; ?>" method="post">
  <textarea name="postbody" rows="8" cols="80"></textarea>
  <input type="submit" name="post" value="post">
</form>

<div class="posts">
  <?php echo $posts; ?>
</div>
