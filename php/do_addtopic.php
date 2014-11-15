<?php
//reference sql_connector php file to connect to the db.
include './sql_connector.php';
doDB();

//check for required fields from the form
if (  (!$_POST['topic_owner'])  ||  (!$_POST['topic_title'])  ||  (!$_POST['post_text'])  ) 
  {//begin if
    header("Location: ../addtopic.html"); 
	exit; 
  }//end if

  
  //safe input values for database
  $sql_topic_owner = mysqli_real_escape_string($mysqli, $_POST['topic_owner']); 
  
  $sql_topic_title = mysqli_real_escape_string($mysqli, $_POST['topic_title']);
  
  $sql_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']); 
  
  //create first query.
  //now() function returns the time created (so cool!).
  
  //-- 
  $sql_add_topic = "INSERT INTO forum_topics (topic_title, topic_create_time, topic_owner) 
  VALUES ('" . $sql_topic_title . "', now(), '" . $sql_topic_owner ."')" ;
  //--
  
  //--
  $add_topic_res = mysqli_query($mysqli, $sql_add_topic) or 
                            die(mysqli_error($mysqli)); 
  //--
  
  
  //retrieve last query id.
  $topic_id = mysqli_insert_id($mysqli);
  
  //second query.
  
  //--
  $sql_add_post = "INSERT INTO forum_posts (topid_id, post_text, post_create_time, post_owner) 
  VALUES ('" . $topic_id . "', '" . $sql_post_text ."' , now(), '" . $sql_topic_owner . "')"; 
  //--
  
  $add_post_res = mysqli_query($mysqli, $sql_add_post) or 
							die(mysqli_error($mysqli)); 
							
	//closing the connection. (finally)
	mysqli_close($mysqli); 
	
	$display_block = "<p>The<strong>" . $_POST['topic_title'] ."</strong> 
		topic has been created.</p>"; 
	
?>

<!DOCTYPE html>
<head>
  <title>New Topic Added</title>
</head>
<body>
  <h1>New Topic Added</h1>
  <?php    
     echo $display_block; 
   ?>
</body>

</html>