<?php
	$folder = "./fotos";
?>

<!DOCTYPE html>
<html>
<head>
	<title>Super Best Diashow</title>
	<meta content="text/html;charset=UTF-8" http-equiv="Content-Type">
	<meta content="utf-8" http-equiv="encoding">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="./open-iconic.css" rel="stylesheet">

	<style>
		body {
			margin: 0;
			font-family: sans-serif;
			color: #212529;
			background-color: black;
		}

		#img-container {
			position: absolute;
			width: 100vw;
			height: 88vh;
			display: flex;
			justify-content: center;
			align-items: center;
			background: no-repeat scroll 0 0 transparent;
			background-size: contain;
			background-position: center center;
		}

		@media screen and (orientation: portrait) {
			#navbar {
				height: 12vw;
			}

			.nav-el {
				margin-left: 2vw;
				margin-right: 2vw;
				height: 12vw; /** 1:1 wie #navbar **/
				width: 12vw; /** 1:1 wie #navbar **/
			}

			#settings {
				bottom: 12vw;
				right: 2vw;
				width: 24vw;
			}

			#settings-delay {
				bottom: 12vw;
				right: 26vw;
				width: 32vw;
			}

			.oi {
				font-size: 6vw;
			}
		}
		@media screen and (orientation: landscape) {
			#navbar {
				height: 12vh;
			}

			.nav-el {
				margin-left: 2vh;
				margin-right: 2vh;
				height: 12vh; /** 1:1 wie #navbar **/
				width: 12vh; /** 1:1 wie #navbar **/
			}

			#settings {
				bottom: 12vh;
				right: 2vh;
				width: 24vh;
			}

			#settings-delay {
				bottom: 12vh;
				right: 26vh;
				width: 32vh;
			}

			.oi {
				font-size: 6vh;
			}
		}

		#navbar {
			position: absolute;
			width: 100vw;
			bottom: 0;
			background-color: #333;
			display: flex;
			justify-content: center;
			align-items: center; 
		}

		#nav-settings {
			position: absolute;
			right: 0;
		}

		.nav-el {
			background-color: #666;
			display: flex;
			justify-content: center;
			align-items: center;
			cursor: pointer;
		}

		.nav-el:hover {
			background-color: #ddd
		}

		#settings,
		#settings-delay {
			display: none;
			position: absolute;
			background-color: #666;
			text-align: center;
		}

		#settings-delay {
			border-right: 1px solid #333;
		}

		#settings > div,
		#settings-delay > div {
			margin: 0;
			cursor: pointer;
			padding: 6px;
		}

		#settings > div:hover,
		#settings-delay > div:hover,
		#set-sound.active {
			background-color: #ddd;
		}

		.oi-nav {
			font-size: 0.7rem;
		}

		#nav-show {
			position: absolute;
			left: -14vw;
			background-color: #444;
		}

		.oi[data-glyph]::before {
			line-height: 2;
		}
	</style>

	<script type="application/javascript">
		var folderPrefix = "./fotos/";
		
		var pics = [];
<?php
	$files = array_diff(scandir($folder), array('.', '..'));
	$arr = [];
	foreach ($files as $f) {
		array_push($arr, $f);
	}
	echo "pics = " . json_encode($arr) . ";";
?>
		/**
			pics[0] = "Bild013.jpg";
			pics[1] = "DSC_0017.JPG";
			pics[2] = "IMG_20150808_161424.jpg";
			pics[3] = "IMG_20160831_163658.jpg";
			pics[4] = "P1060553.JPG";
			pics[5] = "WP_20150411_001.jpg";
			pics[6] = "WP_20150412_008.jpg";
**/
		var currentID = -1;

		var defaultAnimationTime = 350;
		var defaultDiaChangeTime = 600;
		var defaultSleepTime = 2000;

		var animationTime = defaultAnimationTime;
		var diaChangeTime = defaultDiaChangeTime;
		var sleepTime = defaultSleepTime;
	</script>

</head>
<body>
	<img src="" id="img-prev" style="display:none;">
	<img src="" id="img-next" style="display:none;">

	<div id="img-container"></div>
	<audio id="audio" src="dia.mov"></audio>

	<div id="navbar">
		<div id="nav-left" class="nav-el"><span class="oi" data-glyph="arrow-thick-left"></span></div>
		<div id="nav-play" class="nav-el"><span class="oi" data-glyph="media-play"></span></div>
		<div id="nav-pause" class="nav-el" style="display: none;"><span class="oi" data-glyph="media-pause"></span></div>
		<div id="nav-right" class="nav-el"><span class="oi" data-glyph="arrow-thick-right"></span></div>
		<div id="nav-settings" class="nav-el"><span class="oi" data-glyph="cog"></span></div>
		<div id="nav-show" class="nav-el"><span class="oi" data-glyph="chevron-right"></span></div>
	</div>
	<div id="settings">
		<div id="set-delay"><span class="oi oi-nav" data-glyph="timer"></span> delay</div>
		<div id="set-sound" class="active"><span class="oi oi-nav" data-glyph="volume-high"></span> sound</div>
		<div id="set-hide"><span class="oi oi-nav" data-glyph="chevron-left"></span> hide</div>
	</div>
	<div id="settings-delay">
		<div id="set-animationTime"><span class="oi oi-nav" data-glyph="eye"></span> ani&shy;mation</div>
		<div id="set-diaChangeTime"><span class="oi oi-nav" data-glyph="layers"></span> switch</div>
		<div id="set-sleepTime"><span class="oi oi-nav" data-glyph="monitor"></span> sleep</div>
		<div id="set-reset"><span class="oi oi-nav" data-glyph="reload"></span> reset</div>
	</div>

<script src="jquery-3.2.1.min.js"></script>
<script type="application/javascript">
	var slide = function(id) {
		if (id < 0) id = pics.length-1;
		if (id == pics.length) id = 0;

		currentID = id;

		if ($("#set-sound.active").length == 1 &&
			animationTime == defaultAnimationTime &&
			diaChangeTime == defaultDiaChangeTime) {
			$("#audio")[0].play();
		}

		$("#img-container").animate({
			"left" : "-100vw"
		}, animationTime, function() {
			var prevID = (id-1 < 0) ? pics.length - 1 : id-1;
			var nextID = (id+1 == pics.length) ? 0 : id+1;

			$("#img-prev").attr("src", folderPrefix + pics[prevID]);
			$("#img-container").css("background-image", "url(" + folderPrefix + pics[id] + ")");
			$("#img-next").attr("src", folderPrefix + pics[nextID]);

			setTimeout( function() {
				$("#img-container").animate({
					"left" : 0
				}, animationTime);
			}, diaChangeTime);
		});
	};

	$("#img-container").click(function(){
		if ($("#nav-pause").is(":visible")) toggleLoop();
		slide(++currentID);
	});

	$("#nav-left").click(function(){
		if ($("#nav-pause").is(":visible")) toggleLoop();
		slide(--currentID);
	});
	$("#nav-right").click(function(){
		if ($("#nav-pause").is(":visible")) toggleLoop();
		slide(++currentID);
	});

	$("body").keypress(function(event) {
		if (event.keyCode == 37) { // links
			if ($("#nav-pause").is(":visible")) toggleLoop();
			slide(--currentID);
		}
		if (event.keyCode == 39) { // rechts
			if ($("#nav-pause").is(":visible")) toggleLoop();
			slide(++currentID);
		}
		if (event.charCode == 32) { // leertaste
			toggleLoop();
		}
	});

	var dauerschleife = function() {
		if ($("#nav-pause").is(":visible")) {
			slide(++currentID);
			setTimeout(dauerschleife, parseInt(sleepTime)+(2*parseInt(animationTime))+parseInt(diaChangeTime));
		}
	};

	var toggleLoop = function() {
		if ($("#nav-play").is(":visible")) {
			$("#nav-play").hide();
			$("#nav-pause").show();
			dauerschleife();
		} else {
			$("#nav-pause").hide();
			$("#nav-play").show();
		}
	};

	$("#nav-play").click(toggleLoop);
	$("#nav-pause").click(toggleLoop);

	$("#nav-settings").click(function() {
		if ($("#settings-delay").is(":visible")) {
			$("#settings-delay").slideUp();
			$("#settings").slideUp();
		} else {
			$("#settings").slideToggle();
		}
	});

	$("#set-delay").click(function() {
		$("#settings-delay").slideToggle();
	});

	$("#set-animationTime").click(function() {
		var timePrompt = prompt("Zeitintervall (100 - 10000 ms):", animationTime);
		if ($.isNumeric(timePrompt)) {
			if (timePrompt>= 100 && timePrompt<= 10000) {
				animationTime = timePrompt;
			}
		}

	});
	$("#set-diaChangeTime").click(function() {
		var timePrompt = prompt("Zeitintervall (100 - 10000 ms):", diaChangeTime);
		if ($.isNumeric(timePrompt)) {
			if (timePrompt>= 100 && timePrompt<= 10000) {
				diaChangeTime = timePrompt;
			}
		}

	});
	$("#set-sleepTime").click(function() {
		var timePrompt = prompt("Zeitintervall (100 - 10000 ms):", sleepTime);
		if ($.isNumeric(timePrompt)) {
			if (timePrompt>= 100 && timePrompt<= 10000) {
				sleepTime = timePrompt;
			}
		}

	});
	$("#set-reset").click(function() {
		animationTime = defaultAnimationTime;
		diaChangeTime = defaultDiaChangeTime;
		sleepTime = defaultSleepTime;
	});
	$("#set-sound").click(function() {
		if ($("#set-sound.active").length == 0) { // active Klasse fehlt
			$("#set-sound").addClass("active");
			$("#set-sound > span").attr("data-glyph", "volume-high");
		} else {
			$("#set-sound").removeClass("active");
			$("#set-sound > span").attr("data-glyph", "volume-off");
		}
	});
	$("#set-hide").click(function() {
		$("#settings, #settings-delay").slideUp(400, function() {
			$("#navbar").animate({"left" : "-100vw"}, 400, "swing", function() {
				$("#nav-show").animate({"left" : "100vw"}); // "bug": muss erst die navbar entlang zurueck... (deshalb 100vw statt 0px)
				$("#img-container").animate({"height":"100vh"});
			});
		});

	});
	$("#nav-show").click(function() {
		$("#nav-show").animate({"left" : "-14vw"}, 400, "swing", function() {
			$("#navbar").animate({"left" : "0"});
			$("#img-container").animate({"height":"88vh"});
		});
	});

	$(document).ready(function() {
		$("#img-prev").attr("src", folderPrefix + pics[0]);
		$("#img-next").attr("src", folderPrefix + pics[0]);
	});
</script>
</body>
</html>