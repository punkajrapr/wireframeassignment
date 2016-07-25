<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<?php
ob_start();
session_start();
$sessionId = session_id();
error_reporting(2);
include 'database.php';

$viewstmt = $conn->prepare("INSERT INTO tbl_session (session_id) 
                                        VALUES (:session_id)");
                              $viewstmt->bindParam(':session_id', $sessionId);
$viewstmt->execute();

$stmtAjax = $conn->prepare("SELECT 1 FROM tbl_post where createdby='".$sessionId."'"); 
$stmtAjax->execute();
$viewstmtAjax = $conn->prepare("SELECT 1 FROM tbl_session"); 
$viewstmtAjax->execute();


	if($_SERVER['REQUEST_METHOD'] == 'POST') { ;
		$timeToCheck = $_POST['captcha'];
		if($timeToCheck == $_SESSION['checktime']) {
			$target_dir = "uploads/";
                        $filename = time()."_".$_FILES["my-file-selector"]["name"];
			$target_file = $target_dir . basename(time()."_".$_FILES["my-file-selector"]["name"]);
			//die($target_file);
			 if (move_uploaded_file($_FILES['my-file-selector'] ['tmp_name'], $target_file)) {
                             
                             $imagetitle = $_POST['image-title'];
                             
                              $stmt = $conn->prepare("INSERT INTO tbl_post (post_name, post_image, createdby) 
                                        VALUES (:post_name, :post_image,:createdby)");
                              $stmt->bindParam(':post_name', $imagetitle);
                              $stmt->bindParam(':post_image', $filename);
							  $stmt->bindParam(':createdby', $sessionId);
                              $stmt->execute();         
				$message =  "The file ". $filename. " has been uploaded.";
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
			
		
		
		
		}	
	}

$time = time();
$_SESSION['checktime'] = $time;


    $stmt = $conn->prepare("SELECT post_id, post_name, post_image FROM tbl_post where createdby='".$sessionId."'"); 
    $stmt->execute();

    // set the resulting array to associative



?>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
            .vcenter {
                display: inline-block;
                vertical-align: middle;
                float: none;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3-respond-1.4.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Wireframe</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
         <!-- <form class="navbar-form navbar-right" role="form">
            <div class="form-group">
              <input type="text" placeholder="Email" class="form-control">
            </div>
            <div class="form-group">
              <input type="password" placeholder="Password" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Sign in</button>
          </form>-->
        </div><!--/.navbar-collapse -->
      </div>
    </nav>

    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron">
      <div class="container panel-group">
<?php if($stmt->rowCount()>0) { ?>          
  <div class="panel panel-default">
      <div class="panel-body"><p><span style="padding-right:100px;" id="post_id">Posts: <?php echo $stmtAjax->rowCount();?> </span><a class="btn btn-primary btn-lg" role="button" href="download.php" target="_blank" >Export button</a>   <span style="padding-left:100px;" id="view_id">Views: <?php echo $viewstmtAjax->rowCount();?></span></p></div>
  </div>
<?php } ?>          
  <div class="panel panel-default">
    <div class="panel-body">
        <form role="form" id="reply-box" class="padding-10" action="index.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <input type="text" class="form-control" id="image-title"  name="image-title" placeholder="Image Title">
      <input type="hidden" name="captcha" id="captch" value="<?php echo $time;?>" >
    </div>
    <div class="form-group">
   <label class="btn btn-primary" for="my-file-selector">
    <input id="my-file-selector" name="my-file-selector" type="file" style="display:none;">
    Upload Image
</label>
<span class='label label-info' id="upload-file-info"></span>

<span class='label label-warning' id="upload-file-info-error"></span>
    </div>
        <button type="submit" class="btn btn-default">Submit</button>
  </form></div>
       </div>
          
<?php if($stmt->rowCount()>0) { ?>             
 <div class="panel panel-group ">
     <?php 
         $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    foreach(new RecursiveArrayIterator($stmt->fetchAll()) as $k=>$v) {        
    
     
     ?>
    <div class="row panel-warning vcenter">
        <div class="panel-body"><?php echo $v['post_name'];?></div>
        <div class="panel-body"><img src="./uploads/<?php echo $v['post_image']?>" border="0" /></div>
    </div>    
    <hr/>
    <?php } ?>
  </div>
          <script>
setInterval(function(){ 
     $.ajax({url: "updatecontent.php", dataType: "json",success: function(result){
        $("#post_id").html(result.Posts);
        $("#view_id").html(result.Views);
    }});
    
    }, 15000);
</script>
<?php } ?>      
       </div>
      </div>    
          
           
      
 
           

        

        

    
    <div class="container panel .panel-info" >
 
  
</div>

    <div class="container">


      <hr>

      <footer>
        <p>&copy; Company 2015</p>
      </footer>
    </div> <!-- /container -->        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.js"><\/script>')</script>
        <script>
           
            function displayPreview(files) {    
                var reader = new FileReader();
                var img = new Image();
                

                $('#upload-file-info-error').html('');
                reader.onload = function (e) {
                                var rtType = true;
                                
                                img.src = e.target.result;
                                var fileSize = Math.round(files.size / 1024);
                                console.log(fileSize);
                                if(fileSize > 20000) {
                                     $('#upload-file-info-error').html("File size("+fileSize+" KB ) not supported");
                                     
                                     rtType = false;

                                }
                                
                                img.onload = function () {
                                    //alert(this.width +'------------' + this.height);
                                    this.rtType;
                                     //console.log(rtType+'----------1--------------');  
                                    
                                                        if(this.width >= 1920 ) { 
                                                                $('#upload-file-info-error').html('Size is not supported');
                                                                rtType = false;
                                                                
                                                        }
                                                        if(this.height >= 1080) { 
                                                                $('#upload-file-info-error').html('Size is not supported');
                                                                rtType = false;
                                                               
                                                        }                                                       
                                                        //console.log(rtType+'------------------------');  
                                                        
                                                        if(rtType === true) {
                                                            $('#reply-box').submit();
                                                            }      
                                               };
                                    //alert(rtType);           
                                    var imageType = $('#my-file-selector').val().split('.').pop().toLowerCase();
                                    
                                  
                                    if($.inArray(imageType, ['gif','png','jpg','jpeg']) == -1) {
                                        $('#upload-file-info-error').html('Image type is not supported');
                                         rtType = false;
                                        }    
                               // console.log(rtType+'------------11------------');        
                                         
                            };
             reader.readAsDataURL(files);
            }
            $('#my-file-selector').change(function () {
                var file = this.files[0];
                 displayPreview(file);                
                
            });
            </script>
        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
