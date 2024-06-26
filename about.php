<!DOCTYPE html>

<html>
	<?php
		$sTitle= "About US!";
	?>
	<head>
		<?php
			include 'headContents.php';
		?>
		<link rel="stylesheet" href="aboutCSS.css">
	</head>

	<body>
		<?php
			include 'navbar.php';
		?>
	    <h1 id="pageTitle"><?php echo $sTitle ?></h1>
		<div id="peopleGrid">
			<div class="person">
			    <h2> Isaac Curtis </h2>
			    <img src="./Photos/Isaac_Curtis_About_Me.jpeg" alt="Isaac Curtis standing infront of a a massive can of soup">
			    <p>
			        My name is Isaac Curtis. I am from Wyoming, but my family comes from around the world. My father comes from Washington and my mother comes from Wyoming. I am the Founder and President of Ramily Movie Night, which is CSU’s premiere film club on campus. I spend my free time doing very little. Feel free to reach out to me with any questions, comments, cares, concerns, statements, compliments, insults, jokes, or anything else! You know where to find me. 
			    </p>
			</div>
			<div class="person">
			    <h2> Jake Parra</h2>
				<img src="./Photos/JakeParraProfilePic.JPG" alt="Jake Parra posing for headshot photo">
				<p>
					My name is Jake Parra. I am a senior in computer science at CSU. I am from Lakewood, CO.
					I currently work in the Quality Control Department at Valimenta Labs located in Wellington,CO.
					I am working toward gaining an internship in the area of web development, system security, or 
					software engineering. In my free time, I enjoy going to the gym, spending time with friends, and playing video games.
				</p>
			</div>
			<div class="person">
			    <h2> Daniel Peaslee</h2>
			    <img src="Photos/peaslee_picture_short.jpg" alt="Daniel Peaslee standing infront a blank wall in one of several identical shirts">
				<p>
					I am a computer science major. I love everything to do with computers and aim to work with them in the future in just about any capacity. In my free time I love to play PC games and tinker with obsolete and/or uncommon PC technology.
				</p>
			</div>
			<div class="person">
				<h2>Gabriel Bertasius</h2>
				<img src="./Photos/gabrielbertasiusabout.jpg" alt="Portrait photo of Gabriel Bertasius">
				<p>
					I'm Gabriel Bertasius and I'm a CS student focusing on Artificial Intelligence and Machine Learning. I enjoy being a student, but I can't wait to work with real world systems.
					I started programming with C++ and OpenGL in my early teens and have been intrigued by AI systems in video games. I hope to gain a better understanding of how AI and ML systems work so I can apply them to agents inside virtual worlds.
					When I'm away from my computer I like to get a good workout at the gym and in the summers I spend alot of time biking.
				</p>
			</div>
		</div>
	</body>
</html>
