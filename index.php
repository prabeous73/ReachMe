<?php

  include('./classes/DB.php');
  include('./classes/Login.php');
  include('./classes/Post.php');
  include('./classes/Comment.php');

  $showTimeline = False;

  if(Login::isLoggedIn()){
    $user_id = Login::isLoggedIn();
    $showTimeline = True;
  }
  else{
    echo 'Not Logged in';
  }

  if(isset($_GET['postid'])){
    Post::likePost($_GET['postid'], $user_id);
  }

  if(isset($_POST['comment'])){
    Comment::createComment($_POST['commentbody'], $_GET['postid'], $user_id);
  }

  $followingPosts = DB::query('SELECT posts.id, posts.body, posts.likes, users.username FROM users, posts, followers
    WHERE posts.user_id = followers.user_id
    AND users.id = posts.user_id
    AND follower_id=:userid
    ORDER BY posts.likes DESC;', array(':userid'=>$user_id));

foreach($followingPosts as $post){

  echo $post['body']." ~ ".$post['username'];
  echo "<form action='index.php?postid=".$post['id']."' method='post'>";

  if(!DB::query('SELECT post_id FROM post_likes WHERE post_id=:postid AND user_id=:userid', array(':postid'=>$post['id'], ':userid'=>$user_id))){
    echo "<input type='submit' name='like' value='Like'>";
  }
  else{
    echo "<input type='submit' name='unlike' value='Unlike'>";
  }

  echo "<span>".$post['likes']." likes</span>
 </form>
 <form action='index.php?postid=".$post['id']."' method='post'>
   <textarea name='commentbody' rows='3' cols='50'></textarea>
   <input type='submit' name='comment' value='Comment'>
 </form>";
 Comment::displayComments($post['id']);
 echo"
<hr> <br>";

}

 ?>
