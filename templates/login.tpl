<!DOCTYPE HTML>
<html lang="sv">
<head>
	<meta charset="utf-8"> 
	<title>Home - GameLend - A Gaming Solidarity Initiative</title>
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<!--[if lte IE 7]>
	<script src="js/IE8.js" type="text/javascript"></script><![endif]-->
	<!--[if lt IE 7]>
	<link rel="stylesheet" type="text/css" media="all" href="css/ie6.css"/><![endif]-->
	<script type="text/javascript" src="js/javascript.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
	<script type="text/JavaScript" src="js/sha512.js"></script> 
	<script type="text/JavaScript" src="js/forms.js"></script> 
	<script type="text/javascript">
	{literal}
	
		function addPlatform(select){
		  var $ul = $("#platforms").parent().next('ul');
		  if ($ul.find('input[value=' + $("#platforms").val() + ']').length == 0)
		    $ul.append('<li onclick="$(this).remove();">' +
		      '<input type="hidden" name="platforms[]" value="' + 
		      $("#platforms").val() + '" /> ' +
		      $("#platforms").find(':selected').text() + '</li>');
		}

	{/literal}	
	</script>
	
</head>
<body>
	
<script type="text/javascript">
{literal}
	window.onload = function() {
		$( document ).tooltip();

		$(".valimg").mousemove(function(e){
			$('#infoBox').fadeIn("fast");
			$('#infoBox').css({'top': e.clientY + 10, 'left': e.clientX + 10});

			if ($(this).prev().attr('name') && $(this).attr('src')=='art/cross-24.png') {
				if ($(this).prev().attr('name')=="username") {
					$('#infoBox').html('<p>Username already in use</p>');	
				} else if($(this).prev().attr('name')=="email") {
					$('#infoBox').html('<p>Email already in use</p>');	
				}
			} else {
				$('#infoBox').html('<p>Valid</p>');	
			}	
		});
		$('.valimg').mouseleave(function(){
			$('#infoBox').fadeOut("fast");
		});

		if ($('#username').val()=="" && $('#firstname').val()=="" && $('#lastname').val()=="" && $('#city').val()=="" && $('#platforms').val()=="" && $('#email').val()=="") {

			$("#registerform").hide();
		};
		
		$("#registerbutton").click(function() {
			$("#registerform").slideToggle(500, function () {
				console.log("fjksdjfddkls");
				
			});
		});

		$('#username').keyup(function(){
			if (validateUsername($(this).val())) {
				$(this).css({'background-color':'#85BF80'});
				checkFormValidate();
			} else {
				$(this).css({'background-color':'#CE888E'});
			}
		});
		$('#email').keyup(function(){
			if (validateEmail($(this).val())) {
				$(this).css({'background-color':'#85BF80'});
				checkFormValidate();
			} else {
				$(this).css({'background-color':'#CE888E'});
			}
		});
		$('#password').keyup(function(){
			if (validatePassword($(this).val(), ($("#confirmpwd").val()))) {
				$(this).css({'background-color':'#85BF80'});
				$('#confirmpwd').css({'background-color':'#85BF80'});
				checkFormValidate();
			} else {
				$(this).css({'background-color':'#CE888E'});
				$('#confirmpwd').css({'background-color':'#CE888E'});
			}
		});
		$('#confirmpwd').keyup(function(){
			if (validatePassword($(this).val(), ($("#password").val()))) {
				$(this).css({'background-color':'#85BF80'});
				$('#password').css({'background-color':'#85BF80'});
				checkFormValidate();
			} else {
				$(this).css({'background-color':'#CE888E'});
				$('#password').css({'background-color':'#CE888E'});
			}
		});

		function checkFormValidate(){
			if (validateEmail($('#email').val()) && validateUsername($('#username').val()) && validatePassword($('#password').val(), $('#confirmpwd').val())) {
				console.log("true");
				$('#registersubmitbutton').prop('disabled', false);
			} else {
				console.log("false");
				$('#registersubmitbutton').prop('disabled', true);
			}
			
		}



	}
{/literal}
</script>

	<header id="banner" class="body">
		<h1>GameLend <section>A Gaming Solidarity Initiative</section></h1>
		<h1>BETA <section>0.2</section></h1>
	</header>
	
	{if isset($loginerror)}
	<section id="errorbox" class="body">
		<div class="messageboxelement">
			<h3>Login error!</h3>
			<p>{$loginerror}</p>
		</div>
	</section>
	{/if}
	{if isset($registered)}
	<section id="messagebox" class="body">
		<div class="messageboxelement">
		{if $registered=="successvalidation"}
			<h3>Account validated!</h3>
			<p>You can now login!</p>
		{elseif $registered=="successregister"}
			<h3>Account registered!</h3>
			<p>You now only need to validate your account by following the instructions in the email sent to you!</p>
		{/if}
		</div>
	</section>
	{/if}
	{if $browsersupport==false}
	<section id="browserwarning" class="body">
		<div class="browserwarningelement">
			<p>GamaLend is not yet optimized for your browser, please install either <a href="http://www.mozilla.org/sv-SE/firefox/new/">Firefox</a> or <a href="http://www.google.se/intl/sv/chrome/browser/">Chrome</a></p>
		</div>
	</section>
	{/if}
	
	<section id="content" class="body">
		<section id="noticebox" class="body">
			<div class="noticeboxelement">
				<h3>About GameLend</h3>
				<p>GameLend is a web platform with the sole purpose of simplifying sharing of games between friends. The site enables listing of ones own game library and present them to other registered users. By centralizing your and your friends game libraries to one place the process of finding the game you want to play or did not know you wanted to play becomes much easier. All you have to do is browse your friends libraries on GameLend, find a game you want to borrow and then send a request to borrow that game.<br/>
				GameLend does not provide anything besides the presentation of libraries and borrow requests. The physical handling of the actual game, returning and responsibility of its condition is still in the hands of owners and borrowers. </p>
				<h3>BETA 0.2 notifications!</h3>
				<p><span>All registered users up to date have all been deleted</span> because of reconstruction and implementation of email verification. This forces everyone who have already registered to go through the registration process again.<span> I do apologize for this but it is due to the need for testing of the new registration procedure.</span> </p>
			</div>
		</section>
		
		<div id="loginelement">
			<div id="loginform">
			<form action="includes/process_login.php" method="post" name="login_form"> 			
				<input type="text" name="email" placeholder="email address" />
				<input type="password" 
				name="password" 
				id="passwordlogin" placeholder="password" />
				<input type="button" 
				value="Login" 
				onclick="formhash(this.form, this.form.passwordlogin);" /> 
			</form>
			</div>
			
			
			<img id="registerbutton" src="art/register-black.png" />
			<div id="registerform">
				<form method="post" name="registration_form" action='{$register}'>

					
					{if isset($error["username"])}
						<input type='text' name='username' id='username' placeholder="username (required)" title="The username must contain 8-20 characters" />
						<img class="valimg" src="art/cross-24.png" />
					{elseif isset($username)}
						<input type='text' name='username' id='username' placeholder="username (required)" value="{$username}" title="The username must contain 8-20 characters" />
						<img class="valimg" src="art/checkmark-24.png" />
					{else}
						<input type='text' name='username' id='username' placeholder="username (required)" title="The username must contain 8-20 characters" />
					{/if}
					<input type='text' name='firstname' id='firstname' placeholder="Firstname" value="{$firstname}" />
					<input type='text' name='lastname' id='lastname' placeholder="Lastname" value="{$lastname}" />
					<div class="styled-login-select">
						<select id="city" name="city">
							<option value="">City</option>
							{foreach from=$cities item=item}
								{if $item['id']==$cityInput}
									<option value='{$item["id"]}' selected>{$item["name"]}</option>
								{else}
									<option value='{$item["id"]}'>{$item["name"]}</option>
								{/if}
							{/foreach}
						</select>
					</div>
					<div class="styled-login-select">
						<select id="platforms" onchange="addPlatform(this);">
							<option value="">Consoles</option>
							{foreach from=$consoles item=item}
							<option value='{$item["id"]}'>{$item["console"]}</option>
							{/foreach}
						</select>
					</div>
					<ul id="platformselect">	
						
					</ul>

					
					{if isset($error["email"])}
						<input type="text" name="email" id="email" placeholder="email (required)" title="The email address must be valid (Example example@example.ex)" />
						<img class="valimg" src="art/cross-24.png" />
					{elseif isset($email)}
						<input type="text" name="email" id="email" placeholder="email (required)" value="{$email}" title="The email address must be valid (Example example@example.ex)" />
						<img class="valimg" src="art/checkmark-24.png" />
					{else}
						<input type="text" name="email" id="email" placeholder="email (required)" title="The email address must be valid (Example example@example.ex)" />
					{/if}
					<input type="password"
					name="password" 
					id="password" placeholder="password (required)" title="Passwords must be at least 6 characters long, contain one number, one lowercase and one uppercase letter" />
					{if isset($error["password"])}
						<img class="valimg" src="art/cross-24.png" />
					{/if}
					<input type="password" 
					name="confirmpwd" 
					id="confirmpwd" placeholder="confirm password" />
					<input type="button" 
					value="Register" 
					onclick="return regformhash(this.form,
					this.form.username,
					this.form.email,
					this.form.password,
					this.form.confirmpwd);" id="registersubmitbutton" disabled/> 
				</form>
			</div>
		</div>

</section><!-- /#content -->
<div id="infoBox"></div>
</body>
</html>


















