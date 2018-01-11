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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- bootstrap -->
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' media="all" />

<!-- //bootstrap -->
<link href="css/dashboard.css" rel="stylesheet">
<link href="css/style.css" rel='stylesheet' type='text/css' media="all" />
<script src="js/jquery-1.11.1.min.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-89112354-2"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-89112354-2');
    </script>
    <script>
        function SearchVideos(text){
            if(text.length>0){
				$("title").text(text+" - VTuBE");
                $.ajax({
                    url: "https://www.googleapis.com/youtube/v3/search?part=id&maxResults=40&q="+text+"&type=video&key=<?php echo YOUTUBE_API?>",
                    success: function(data){
                        data['items'].forEach(function(item){
                            VideoInfo(item.id.videoId);
                        });
                    }
                });
            }
        }
        function VideoInfo(videoID){
            $.ajax({
                url: "https://www.googleapis.com/youtube/v3/videos?key=<?php echo YOUTUBE_API?>&part=snippet&id="+videoID,
                success: function(data){
                    data['items'].forEach(function(item){
                        $("#list").append("<div class='col-md-3 resent-grid recommended-grid'><div class='resent-grid-img recommended-grid-img'><a href='"+videoID+"'><img src='"+item.snippet.thumbnails.medium.url+"' alt='' /></a></div><div class='resent-grid-info recommended-grid-info'><h5><a href='"+videoID+"' class='title'>"+item.snippet.title+"</a></h5></div></div>");
                    });
                }
            });
        }
        function LastVideos(videoID){
            $.ajax({
                url: "https://www.googleapis.com/youtube/v3/videos?key=<?php echo YOUTUBE_API?>&part=snippet&id="+videoID,
                success: function(data){
                    data['items'].forEach(function(item){
                        $("#recommended").append("<div class='col-md-3 resent-grid recommended-grid'><div class='resent-grid-img recommended-grid-img'><a href='"+videoID+"'><img src='https://i.ytimg.com/vi/"+videoID+"/mqdefault.jpg' alt='' /></a></div><div class='resent-grid-info recommended-grid-info'><h5><a href='"+videoID+"' class='title'>"+item.snippet.title+"</a></h5></div></div>");
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
                <?php
                $issearch=true;
                if(isset($_GET['search'])){
                    echo "<script>SearchVideos('".str_replace("'","\'",$_GET['search'])."');</script>";
                    $issearch=false;
                } ?>
				<form method="get" class="navbar-form navbar-right">
					<input type="text" name="search" class="form-control" placeholder="Search...">
					<input type="submit" value=" ">
				</form>
			</div>
        </div>
      </div>
    </nav>
        <div class="col-sm-12 col-sm-offset-3 col-md-10 col-md-offset-1 main">
			<div class="main-grids">
                    <div class="recommended" id="recommended">
                    <?php if($issearch) :?>
                        <?php
                        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
                        $result = $conn->query("SELECT * FROM lastviews ORDER BY ID DESC LIMIT 8");
                        $conn->close();
                        ?>
                        <?php if(mysqli_fetch_row($result)) : ?>
                            <div class="recommended-grids">
                                <div class="recommended-info">
                                    <h3>LAST VIEWED</h3>
                                </div>
                                <div  id="top" class="callbacks_container">
                                    <?php foreach ($result as $row) : ?>
                                        <?php echo "<script>LastVideos('".$row['videoID']."');</script>" ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php else:?>
                        <div id="list"  class="recommended-grids">
                            <div  id='top' class='callbacks_container'>

                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
			</div>
		</div>
		<div class="clearfix"> </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
  </body>
</html>