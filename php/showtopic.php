<?php
include 'sql_connector.php';
doDB(); 

//check for required info from the query string. 
if (!isset($_GET['id'])){
	header("Location: show_topics.php"); 
	exit;
}
//create safe values for use. 
$safe_topic_id = mysqli_real_escape_string($mysqli, $_GET['id']);

//verify the topic exists. 
$verify_exists_sql = "select topic_title from forum_topics where id ='".$safe_topic_id."'";
$verify_exists_res = mysqli_query($mysqli, $verify_exists_sql) or die(mysqli_error($mysqli)); 

if(mysqli_num_rows($verify_exists_res) < 1){
	//this topic does not exist.
	$display_block = "<p><em>You have selected an invalid topic.<br /> Please <a href=\ 'show_topics.php\'>try again</a>.</em></p>";	
} else {
	//get the topic title. 
	while ($topic_info = mysqli_fetch_array($verify_exists_res)){
		$topic_title = stripslashes($topic_info['topic_title']);
	}	

	//gather the posts.
	$get_posts_sql = "select topic_id, post_text, date_format(post_create_time, '%b %e %Y<br /> %r') as fmt_post_create_time, post_owner 
	from forum_posts where topic_id = '".$safe_topic_id."'
	order by post_create_time asc";

	$get_posts_res = mysqli_query($mysqli, $get_posts_sql) or die(mysqli_error($mysqli)); 

	//create display string. 
	$display_block = 
	"<p>Showing posts for the <strong>$topic_title</strong> topic:</p>
	 <table>
	 <tr>
	 <th>AUTHOR</th>
	 <th>POST</th>
	 </tr>";

	 while ($posts_info = mysqli_fetch_array($get_posts_res)){
	 	$post_id = $posts_info['topic_id'];
	 	$post_text = nl2br(stripslashes($posts_info['post_text'])); 
	 	$post_create_time = $posts_info['fmt_post_create_time'];
	 	$post_owner = stripslashes($posts_info['post_owner']);

	 	//add to display.
	 	$display_block .= 
	 	"<tr>
	 	 <td>$post_owner<br /><br />
	 	 <a href= 'replytopost.php?post_id=$post_id'>
	 	 <strong>reply to post</strong></a></td>
	 	 <td>$post_id<br /><br /></td>
	 	 </tr>"; 												
	 }
	 //free results 
	 mysqli_free_result($get_posts_res); 
	 mysqli_free_result($verify_exists_res);

	 //close mysql connecetion.
	 mysqli_close($mysqli);

	 //close up the table.
	 $display_block .= "</table>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Posts in Topic</title>
<style type='text/css'>
  table{
  	border: 1px solid black;
  	border-collapse: collapse;
  }
  th{
  	border: 1px solid black;
  	padding: 6px;
  	font-weight: bold;
  	background: #ccc;
  }
  td{
  	border: 1px solid black;
  	padding: 6px;
  	vertical-align: top;
  }
  .num_posts_col { text-align: center; }

</style>
</head>
<body>
	<h1>Posts in Topic</h1>
	<?php echo $display_block; ?>
</body>
</html>