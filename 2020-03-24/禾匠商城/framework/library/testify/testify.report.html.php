<?php
require 'helpers.php';
?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>(<?php echo $suiteResults['pass'], '|', $suiteResults['fail']?>) <?php echo $title?> · Testify Suite</title>

		<style>
			* {
				margin: 0;
				padding: 0;
			}

			html {
				background-color: #fafafa;
				overflow-x: hidden;
				height: 100%;
			}

			body {
				font: 13px/1.5 Tahoma, Arial, sans-serif;
				text-align: center;
				color: #5a5a5a;
				height: 100%;
			}

			#wrapper {
				min-height: 100%;
			}

			h1, h2 {
				font-family: 'PT Sans Narrow', sans-serif;
				font-weight: normal;
			}

			h1:before,
			h2:before {
				display: inline-block;
				font: normal 0.6em/1.1 sans-serif;
				text-align: center;
				color: #fff;
				content: '\2714';

				position: relative;
				top:  -0.2em;
				width: 1em;
				height: 1em;
				padding: 0.32em;
				margin-right: 1.2em;

				background-color: #a8d474;
				border-radius: 50%;
			}

			h1.fail:before,
			h2.fail:before {
				background-color: #ee3c4f;
				content: '\2718';
				line-height: 1.05;
			}

			h1 {
				font-size: 46px;
				padding: 50px 40px;
				position: relative;
				border-bottom: 1px solid #ddd;
				background-color: #fff;
				box-shadow: 0 0 3px #ddd;
				margin-bottom: 40px;
				color: #777;
			}

			h2 {
				font-size: 28px;
			}

			span.result {
				color: #DCDCDC;
				display: block;
				padding: 0.6em;
			}

			.green { color: #94c25d; }
			.red { color: #fb4357; }

			ul {
				list-style: none;
				font-size: 19px;
				width: 800px;
				margin: 10px auto 80px;
			}

			li span.pass {color: #94c25d;}
			li span.fail {color: #fb4357;}

			li span.file,
			li span.line {
				float: right;
				font-size: 14px;
				padding-left: 10px;
				line-height: 19px;
				position: relative;
				bottom: -4px;
			}

			li span.file {
				color: #b0b0b0;
			}

			li{
				text-align: left;
				border-bottom: 1px dotted #d4d4d4;
				padding: 3px 0;
				overflow: hidden;
			}

			li span.type {
				display: inline-block;
				width: 600px;
			}

			div.message {
				font-size: 22px;
				font-family: 'PT Sans Narrow', sans-serif;
				padding: 0 40px 50px;
			}

			div.message.pass .red,
			div.message.fail .green {
				display: none;
			}

			footer {
				background-color: #fff;
				border-top: 1px solid #ddd;
				box-shadow: 0 0 3px #ddd;
				color: #888;
				font-size: 10px;
				padding: 15px;
				display: block;
				height: 15px;
				position: relative;
			}

			a, a:visited {
				color: #3ba2cd;
				text-decoration: none;
			}

			a:hover {
				text-decoration: underline;
			}

			div.source {
				font-size: 11px;
				color: #ccc;

				-moz-transition: 0.25s;
				-webkit-transition: 0.25s;
				transition: 0.25s;
			}

			li:hover div.source {
				color: #444;
			}

		</style>

		

		<!--[if lt IE 9]>
			<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans+Narrow" />
		  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>

		<div id="wrapper">

			<div id="content">
				<h1 class="<?php echo $result = ($suiteResults['fail'] == 0 ? 'pass' : 'fail') ?>">
					<?php echo $title?>
				</h1>

				<div class="message <?php echo $result?>">
					<span class="green">Far out! Everything passed!</span>
					<span class="red">Bummer! You have failing tests! [pass <?php echo percent($suiteResults)?>%]</span>
				</div>

				<?php
				foreach($cases as $caseTitle => $case) { ?>

					<h2 class="<?php echo $case['fail'] == 0 ? 'pass' : 'fail' ?>">
						<?php echo $caseTitle?>
						<span class="result">
							<span class="green"><?php echo $case['pass']?></span>/<span class="red"><?php echo $case['fail']?></span>
						</span>
					</h2>

					<ul class="tests">
						<?php
						foreach($case['tests'] as $test) { ?>

							<li>
								<span class="type <?php echo $test['result']?>">
									<?php
										echo $test['name'] === '' ? $test['type'].'()' : $test['name'];
									?>
								</span>
								<span class="line">line <?php echo $test['line']?></span>
								<span class="file"><?php echo $test['file']?></span>
								<div class="source"><?php echo htmlspecialchars($test['source'])?></div>
							</li>

						<?php } ?>

					</ul>

				<?php } ?>

			</div>

		</div>

		<footer> Powered by <a href="http://tutorialzine.com/projects/testify/" target="_blank">Testify</a> framework</footer>

	</body>
</html>