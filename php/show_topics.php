<?php 
include 'sql_connector.php';
doDB(); 

//gather the topis. 
$get_topics_sql = "select id, topic_title, 
				   DATE_FORMAT(topic_create_time, '%b %e %Y at %r')	AS 
				   fmt_topic_create_time, topic_owner from forum_topics 
				   order by topic_create_time desc";
//result of sql query string.				   
$get_topics_res = mysqli_query($mysqli, $get_topics_sql) or die(mysqli_error($mysqli));;
$display_block = "";
if(mysqli_num_rows($get_topics_res) < 1){
	//there are no topics to display. 
	$display_block = "<p><em>No Topics Exist</em><p>";

} else{
	//create a display string for topics.
    $display_block .=
    "<table> 
    <tr>
    <th>Topic Title</th>
    <th># of Posts</th>
    </tr>";

   
    while($topic_info= mysqli_fetch_array($get_topics_res)) {
        $topic_id = $topic_info['id']; 
        $topic_title = stripslashes($topic_info['topic_title']);
        $topic_create_time = $topic_info['fmt_topic_create_time']; 
        $topic_owner = stripslashes($topic_info['topic_owner']); 

        //get number of posts.
        $get_num_posts_sql = "select count(topic_id) AS post_count from 
        forum_posts where topic_id = '".$topic_id."'"; 

        $get_num_posts_res = mysqli_query($mysqli, $get_num_posts_sql)
        or die(mysqli_error($mysqli)); 

        while ($posts_info = mysqli_fetch_array($get_num_posts_res)) {
            $num_posts = $posts_info['post_count'];
        }

        //add to display

        $display_block .= 
        "<tr>
        <td><a href='showtopic.php?id=$topic_id'>
        <strong>$topic_title</strong></a><br />
        Created on $topic_create_time by $topic_owner</td>
        <td class='num_posts_col'>$num_posts</td>
        </tr>";
    } //end of topic_res while loop.
    //free results.

    mysqli_free_result($get_topics_res);
    mysqli_free_result($get_num_posts_res);

    //close the connection with mysql. 
    mysqli_close($mysqli); 

    //close up the table.
    $display_block .= "</table>";    

}

?>
<!DOCTYPE html>
<html>
<head>
<title>Topics Forum</title>
<style type="text/css">
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
    }
    .num_posts_col { text-align: center; }

</style>
</head>
<body>
<h1>Topics Forum</h1>
<?php echo $display_block; ?>
<p>Would you like to <a href="../addtopic.html">add a topic?</a></p>
</body>
</html>