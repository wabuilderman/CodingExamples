<?php
session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ace Fur</title>
		<link rel="stylesheet" href="tabStyle.css">
		<link rel="stylesheet" href="feedStyle.css">
		<link rel="stylesheet" href="accountButtonsStyle.css">
		<link rel="shortcut icon" type="image/ico" href="favicon.ico">
		<style>
		:root {
		    font-size: 1.5em;
		}
		</style>
	</head>
	<body onclick="handleClicks(event);" style="overflow-y: scroll;">
		<script>
			var last_known_scroll_position = 0;
			var ticking = false;

			function scrollElements(scroll_pos)
			{
				var element = document.getElementById("gradient");
				element.style.background = 'linear-gradient(rgb(' + scroll_pos +','+scroll_pos +','+scroll_pos +')' + ', #fff)';
			}

			window.addEventListener('scroll', function(e)
			{

				last_known_scroll_position = window.scrollY;

				if (!ticking)
				{

					window.requestAnimationFrame(function()
					{
						scrollElements(last_known_scroll_position);
						ticking = false;
					});

					ticking = true;

				}

			});
		</script>
		<script src="tabContent.js"></script>

	<?php if($_SESSION['username'] == "") : ?>

		<div class="accountButton" id="LoginButton"
			style="
					--boxColor: #77f;
					--boxHighlight: #aaf;
					--boxWidth:4em;
					--boxX: calc(2em);
					">
			<a style="color: #fff">
				Login
			</a>
			<form id="LoginArea"
			 			action="AccountManagement.php"
						method="post"
						accept-charset="utf-8"
						onsubmit="this.setAttribute('hidden','true');"
						hidden>
				<table>
					<tr>
						<td>Username:</td>
						<td><input type="text" value="" name="Username"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="text" value="" name="Password"></td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="Submit" id="LoginSubmit"></td>
						<td></td>
					</tr>
				</table>

			</form>
		</div>

		<div class="accountButton" id="AccountCreateButton"
			style="
					--boxColor: #7f7;
					--boxHighlight: #afa;
					--boxWidth:12em;
					--boxX: 8em;
					">
			<a style="color: #fff" onclick="openTab(event, 'AccountCreate')">
				Create Account
			</a>
		</div>

	<?php else : ?>

		<div class="accountButton"
			style="
					--boxColor: #f77;
					--boxHighlight: #faa;
					--boxWidth:5em;
					--boxX: calc(2em);
					">
			<a style="color: #fff" onclick="window.location.href='AccountManagement.php'">
				Logout
			</a>
		</div>

		<div class="accountName"
			style=""
					onclick="document.getElementById('NameOptions').style.display=block;">
			<?php echo $_SESSION['username'] . "<br>"; ?>
			<a id="NameOptions" style="display: none;">
				Profile <br>
				Account
			</a>
		</div>

	<?php endif; ?>

		<div class="tab" style="position:fixed;z-index:1;">
			<button 	class="tablinks"
						onclick="openTab(event, 'Home')"
						id="defaultTab">
				Home
			</button>

			<button 	class="tablinks"
						onclick="openTab(event, 'Feed')">
				Feed
			</button>

			<button 	class="tablinks"
						onclick="openTab(event, 'Support')">
				Support
			</button>
		</div>
		<br>
		<br>
		<div id="Home" class="tabcontent">
			<p>Welcome to the site!</p>
			<p>Notice: This site is in development. Stay tuned for updates!</p>
			<style>
		#developmentNotice
		{
			border-style: solid;
			border-width: 0.2em;
			border-color: #f00;
			background-color: #fcc;
		}
			</style>
			<ul id="developmentNotice">
				<h3>Planned Features:</h3>
				<li>Account Management</li>
				<li>Adding / Removing contacts</li>
				<li>Posting broad messages</li>
				<li>Sending private messsages</li>
				<br>
			</ul>
		</div>

		<div id="Feed" style="position:relative;width:calc(100% - 12em);word-wrap:break-word;" class="tabcontent">
			<div id="posts"></div>
			<script>
				var myText = document.getElementById("posts");
				function getAllPosts()
				{
					var request = new XMLHttpRequest();
					var url = "PostManagement.php";
					var params = "method=getAllPosts";
					request.open("POST", url, true);
					request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

					request.onreadystatechange = function()
					{
						if(request.readyState == 4 && request.status == 200)
						{
							showPosts(request.responseText);
						}
					}
					request.send(params);
				}
				function parseUser(element)
				{
					element = element.substring(12);
					var i = 0;
					while (element[i++] != "\"");
					element = element.substring(0,i - 1);
					return element;
				}
				function showPosts(response)
				{
					var posts = JSON.parse(response);
					myText.innerHTML = "";
					posts.forEach(function(element)
					{
						var post = document.createElement('DIV');
						post.insertAdjacentText('beforeend', "User: " + parseUser(element));
						post.insertAdjacentHTML('beforeend', element);
						post.lastChild.remove();
						myText.appendChild(post);
					});
				}
				getAllPosts();
			</script>
			<p id="feedInfo"></p>
			<svg width="0" height="0">
			  <defs>
			    <clipPath id="pawMask">
			      <circle cx="0.5em" cy="1.0em" r="0.5em"/>
						<circle cx="1.7em" cy="0.5em" r="0.5em"/>
						<circle cx="2.9em" cy="1.0em" r="0.5em"/>
						<circle cx="1.7em" cy="2.2em" r="1.0em"/>
			    </clipPath>
			  </defs>
			</svg>
			<div id="loadingPawContainer">
				<div id="loadingPawFill">
					<div id="innerMostPaw"></div>
				</div>
			</div>
			<p class="feedLoading">Loading...</p>
		</div>

		<div id="Support" class="tabcontent">
			<p>Contact us!</p>

			<form action="sendSupportTicket.php" method="post">
				Message: <br>
				<textarea
				type="text"
				style="
				height: 10em;
				width: 100%
				"
				name="Data"></textarea><br>
				<input type="submit" value="Submit">
			</form>

		</div>
		<div id="AccountCreate" class="tabcontent">
			<h3>Create an account!</h3>
			<form id="accountCreationForm"
			 			action="AccountManagement.php"
						method="post"
						accept-charset="utf-8">
				<table>
					<tr>
						<td>Username:</td>
						<td><input type="text" value="" name="Username"></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><input type="text" value="" name="Password"></td>
					</tr>
					<tr>
						<td>Re-enter Password:</td>
						<td><input type="text" value="" name="Password2"></td>
					</tr>
				</table>
				<input type="submit" name="submit" value="Submit">
			</form>

		</div>
		<script>
			document.getElementById("defaultTab").click();

			function hasParentID(element1, id)
			{
				var testElement = element1;

				if(testElement.id == id)
				{
					return true;
				}
				while(testElement.parentNode != null)
				{
					if(testElement.parentNode.id == id)
					{
						return true;
					}
					testElement = testElement.parentNode;
				}
				return false;
			}

			function handleClicks(event)
			{
				var loginArea = document.getElementById('LoginArea');
				if (hasParentID(event.target, "LoginArea") || hasParentID(event.target, "LoginButton"))
				{
					loginArea.hidden = false;
				} else {
					loginArea.hidden = true;
				}
  		}
		</script>
	</body>
</html>
