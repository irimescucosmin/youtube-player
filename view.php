<?php require_once ('inc/config.php');?>
<!DOCTYPE HTML>
<html>
<head>
<title>VTuBE</title>
<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon.png">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Sample youtube player">
<meta name="keywords" content="HTML,CSS,PHP,JavaScript">
<meta name="author" content="Cosmin Irimescu">
<!-- bootstrap -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' media="all" />
<!-- //bootstrap -->
<link href="css/dashboard.css" rel="stylesheet">
<!-- Custom Theme files -->
<link href="css/style.css" rel='stylesheet' type='text/css' media="all" />
<script src="js/jquery-1.11.1.min.js"></script>
<script>
// MONTHS ARRAY
var months = new Array(1,'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
function linkify(inputText) {
    var replacedText, replacePattern1, replacePattern2, replacePattern3;

    //URLs starting with http://, https://, or ftp://
    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

    //Change email addresses to mailto:: links.
    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
    replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

    return replacedText;
}
function SuggestVideos(videoID){
    $.ajax({
        url: "https://www.googleapis.com/youtube/v3/search?part=snippet&relatedToVideoId="+videoID+"&type=video&key=<?php echo YOUTUBE_API?>",
        success: function (data) {
            data['items'].forEach(function (item) {
                $("#nextv").append("<div class='single-right-grids'><div class='col-md-4 single-right-grid-left'><a href='"+item.id.videoId+"'><img src='"+item.snippet.thumbnails.medium.url+"' alt='' /></a></div><div class='col-md-8 single-right-grid-right'><a href='"+item.id.videoId+"' class='title'>"+item.snippet.title+"</a><p class='author'><a class='author'>"+item.snippet.channelTitle+"</a></p><p class='views'>Published on "+item.snippet.publishedAt.split("T")[0].split("-")[2].replace(/^0+/, '')+" "+months[item.snippet.publishedAt.split("T")[0].split("-")[1].replace(/^0+/, '')]+" "+item.snippet.publishedAt.split("T")[0].split("-")[0]+"</p></div><div class='clearfix'> </div></div>");
            });
        }
    });
}
function Video(videoID) {
    $.ajax({
        url: "https://www.googleapis.com/youtube/v3/videos?key=<?php echo YOUTUBE_API?>&part=snippet&id="+videoID,
        success: function (data) {
            data['items'].forEach(function (item) {
                SuggestVideos(videoID);
				$("title").text(item.snippet.title+" - VTuBE");
                $("#vframe").prepend("<div class='col-sm-8 single-left'><div class='song'><div class='video-grid'><iframe src='https://www.youtube-nocookie.com/embed/"+videoID+"?rel=0&amp;showinfo=0&amp;vq=hd720&autoplay=1' allowfullscreen></iframe></div><div class='song-info'><h3>"+item.snippet.title+"</h3></div></div><div class='clearfix'><a href='https://break.tv/widget/mp3/?link=https://www.youtube.com/watch?v="+videoID+"' target='_blank'><button type='button' class='btn btn-success'>Download MP3</button></a> <a href='https://break.tv/widget/mp4/?link=https://www.youtube.com/watch?v="+videoID+"' target='_blank'><button type='button' class='btn btn-info'>Download MP4</button></a> </div><div class='published'><div class='load_more'><h4>Published on "+item.snippet.publishedAt.split("T")[0].split("-")[2].replace(/^0+/, '')+" "+months[item.snippet.publishedAt.split("T")[0].split("-")[1].replace(/^0+/, '')]+" "+item.snippet.publishedAt.split("T")[0].split("-")[0]+"</h4><p>"+linkify(item.snippet.description)+"</p></div></div></div>");
            });
        }
    });
}
</script>
<!--start-smoth-scrolling-->
<!-- fonts -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
<!-- //fonts -->
</head>
  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="index.php"><h1><img src="images/vtube.png" alt="" /></h1></a>
        </div>
		<div id="navbar" class="navbar-collapse collapse">
			<div class="top-search">
				<form method="get" class="navbar-form navbar-right" action="index.php">
					<input type="text" name="search" class="form-control" placeholder="Search...">
					<input type="submit" value=" ">
				</form>
			</div>
        </div>
    </nav>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-1 main">
			<div class="show-top-grids" id="vframe">
                <?php function yt_exists($videoID) {
                    $theURL = "http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=$videoID&format=json";
                    $headers = get_headers($theURL);
                    return (substr($headers[0], 9, 3) !== "404");
                } ?>
                <?php if(isset($_GET['watch']) and yt_exists($_GET['watch'])==1): ?>
                    <?php
                    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                    // Check connection
                    if (!$conn->connect_error) {
                        date_default_timezone_set("Europe/Rome");
                        $result = $conn->query("SELECT * FROM lastviews WHERE videoID='".$_GET['watch']."';");
                        if (mysqli_fetch_row($result)) {
							$sql = "DELETE FROM lastviews WHERE videoID='".$_GET['watch']."'";
							$conn->query($sql);
                            $sql = "INSERT INTO lastviews (videoID) VALUES ('".$_GET['watch']."');";
                            $conn->query($sql);
                        }
						else{
							$sql = "INSERT INTO lastviews (videoID) VALUES ('".$_GET['watch']."');";
                            $conn->query($sql);
						}
                    }
                    ?>
				    <?php echo "<script>Video('".$_GET['watch']."');</script>" ?>
						<div class="col-md-4 single-right">
						<h3>SUGGESTED</h3>
						<div class="single-grid-right" id="nextv">
						</div>
					</div>
                 <?php else : ?>
				 <?php header("location: index.php"); ?>
                <?php endif;?>
				<div class="clearfix"> </div>
			</div>
		</div>
  </body>
</html>