<?php 
require_once dirname(__FILE__) . '/../model/autoload.php';

session_start();

$db_conf = Config::getConfig('db_conf');
$db = new MySQL($db_conf);

$username = $_REQUEST['username'];
$password = $_REQUEST['password'];
if (!empty($username) && !empty($password))
{
	$sql = "set names gbk";
	$db->Query($sql);
	$sql = "select * from user where username='$username' and password='$password' and priority=2";
	$rs = $db->Query($sql);
	$row = $db->FetchArray($rs);
	if ( $row )
	{
		$_SESSION['client_id'] = $row['id'];
		$_SESSION['client_name'] = $row['name'];
		
		header('Location: stat.php');
	}
}

$company_name = Config::getConfig('company_name');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>

<head>

<meta charset="gbk" />

<title><?php echo $company_name;?>统计平台</title>

<meta name="description" content="<?php echo $company_name;?>" />

<meta name="keywords" content="<?php echo $company_name;?>" />



<!-- Favicons  

<link rel="shortcut icon" type="image/png" HREF="img/favicons/favicon.png"/>

<link rel="icon" type="image/png" HREF="img/favicons/favicon.png"/>

<link rel="apple-touch-icon" HREF="img/favicons/apple.png" />

-->



<!-- Main Stylesheet --> 

<link rel="stylesheet" href="css/style.css" type="text/css" />



<!-- Colour Schemes

Default colour scheme is blue. Uncomment prefered stylesheet to use it.

<link rel="stylesheet" href="css/brown.css" type="text/css" media="screen" />  

<link rel="stylesheet" href="css/gray.css" type="text/css" media="screen" />  

<link rel="stylesheet" href="css/green.css" type="text/css" media="screen" />

<link rel="stylesheet" href="css/pink.css" type="text/css" media="screen" />  

<link rel="stylesheet" href="css/red.css" type="text/css" media="screen" />

-->



<!-- Your Custom Stylesheet --> 

<link rel="stylesheet" href="css/custom.css" type="text/css" />



<!--swfobject - needed only if you require <video> tag support for older browsers -->

<script type="text/javascript" SRC="js/swfobject.js"></script>

<!-- jQuery with plugins -->

<script type="text/javascript" SRC="js/jquery-1.4.2.min.js"></script>

<!-- Could be loaded remotely from Google CDN : <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> -->

<script type="text/javascript" SRC="js/jquery.ui.core.min.js"></script>

<script type="text/javascript" SRC="js/jquery.ui.widget.min.js"></script>

<script type="text/javascript" SRC="js/jquery.ui.tabs.min.js"></script>

<!-- jQuery tooltips -->

<script type="text/javascript" SRC="js/jquery.tipTip.min.js"></script>

<!-- Superfish navigation -->

<script type="text/javascript" SRC="js/jquery.superfish.min.js"></script>

<script type="text/javascript" SRC="js/jquery.supersubs.min.js"></script>

<!-- jQuery form validation -->

<script type="text/javascript" SRC="js/jquery.validate_pack.js"></script>

<!-- jQuery popup box -->

<script type="text/javascript" SRC="js/jquery.nyroModal.pack.js"></script>

<!-- Internet Explorer Fixes --> 

<!--[if IE]>

<link rel="stylesheet" type="text/css" media="all" href="css/ie.css"/>

<script src="js/html5.js"></script>

<![endif]-->

<!--Upgrade MSIE5.5-7 to be compatible with MSIE8: http://ie7-js.googlecode.com/svn/version/2.1(beta3)/IE8.js -->

<!--[if lt IE 8]>

<script src="js/IE8.js"></script>

<![endif]-->

</head>



<body>

	

	<!-- the Main Content -->

		<!-- Header --> 
	<div id="headertop"> 
		<div class="wrapper-login"> 
			<!-- Title/Logo - can use text instead of image --> 
			<div id="title"><?php echo $company_name;?>统计平台<!--<span>Administry</span> demo--></div> 
			<!-- Aside links --> 
			<!--<aside><b>English</b> &middot; <a href="#">Spanish</a> &middot; <a href="#">German</a></aside>--> 
			<!-- End of Aside links --> 
		</div> 
	</div> 
	<!-- End of Header --> 
	<!-- Page title --> 
	<div id="pagetitle"> 
		<div class="wrapper-login"></div> 
	</div> 
	<!-- End of Page title --> 
	
	<!-- Page content --> 
	<div id="page"> 
		<!-- Wrapper --> 
		<div class="wrapper-login"> 
				<!-- Login form --> 
				<section class="full">					
					
					<h3>&nbsp;</h3> 
					
					<div class="box box-info">请输入用户名和密码登录系统</div> 
 
					<form id="loginform" method="post" action="index.php"> 
 
						<p> 
							<label for="username">用户名:</label><br/> 
							<input type="text" id="username" class="full" value="" name="username"/> 
						</p> 
						
						<p> 
							<label for="password">密码:</label><br/> 
							<input type="password" id="password" class="full" value="" name="password"/> 
						</p> 
						
						<!--
						<p>
							<input type="checkbox" id="remember" class="" value="1" name="remember"/>
							<label class="choice" for="remember">Remember me?</label>
						</p>
						--> 
						
						<p> 
							<input type="submit" class="btn btn-green big" value="登录"/> &nbsp; 
							<!--<a href="javascript: //;" onClick="$('#emailform').slideDown(); return false;">Forgot password?</a> or <a href="#">Need help?</a>--> 
						</p> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
						<div class="clear">&nbsp;</div> 
 
					</form> 
					
					<form id="emailform" style="display:none" method="post" action="#"> 
						<div class="box"> 
							<p id="emailinput"> 
								<label for="email">Email:</label><br/> 
								<input type="text" id="email" class="full" value="" name="email"/> 
							</p> 
							<p> 
								<input type="submit" class="btn" value="Send"/> 
							</p> 
						</div> 
					</form> 
					
				</section> 
				<!-- End of login form --> 
				
		</div> 
		<!-- End of Wrapper --> 
	</div> 
	<!-- End of Page content --> 
	
	<!-- Page footer --> 
	<footer id="bottom"> 
		<div class="wrapper-login"> 
			<p>Copyright &copy; 2011 <b><?php echo $company_name;?></b></p> 
		</div> 
	</footer> 
	<!-- End of Page footer --> 
 
<!-- User interface javascript load --> 
<!--<script type="text/javascript" SRC="js/administry.js"></script>-->	

</body>

</html>
