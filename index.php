<?php

	/*
	CC BY SA 3.0 https://creativecommons.org/licenses/by-sa/3.0/
	Contributors:
	 * Svante Svenson: https://stackoverflow.com/a/6298066/3161534
	 * Nicolas Form
	*/

	// Count the visitors
	
	$host_name = '';
	$database = '';
	$user_name = '';
	$password = '';

	$dbConnection = new mysqli($host_name, $user_name, $password, $database)
		or die('{ "error":"1", "message": "Impossible to connect : ' . $dbConnection->connect_error . '" }');

	$stmt = $dbConnection->prepare("INSERT INTO `htmltable_visitors`(`date`) VALUES (CURRENT_TIMESTAMP)")
		or die('Failed PREPARE request : ' . $dbConnection->error);
	$stmt->execute()
		or die('Failed EXECUTE request : ' . $stmt->error);
	$stmt->close();
	
?><!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<title>HTML table swapper</title>
	       
	<style>
		body {
			font-family: "Source Code Pro";
			text-align: center;
			background-color: #d6eeee;
			color: #ffffffcf;
		}
		#container {
			background-color: #3c4057;
			border-radius: 30px;
			padding: 20px;
			max-width: 700px;
			margin: 10px auto;
		}
		#mainInput {
			width: 90%;
			height: 300px;
			padding: 10px;
		}
		#result {
			width: 90%;
			height: 300px;
			padding: 10px;
			margin: auto;
			text-align: left;
			white-space: break-spaces;
			font-weight: bold;
		}
		.preview {
			width: 90%;
			padding: 10px;
			overflow: auto;
		}
		.preview td, .preview th {
			border: solid 1px;
		}
		.error {
			color: red;
		}
		#footer {
			color: #444444;
			font-size: 10px;
			margin: auto;
			padding: 10px;
			max-width: 700px;
		}
	</style>
</head>

<body>
	
	<div id="container">
		
		<h1>HTML table swapper</h1>
		
		<div>
			<p>Copy/paste your HTML table below to instantly get its equivalent but with the row and columns swapped.</p>
			<p><textarea type="text" id="mainInput" onkeyup="checkTable()" placeholder="Copy/paste your <table> here"></textarea></p>
			<p id="mainInputPreview" class="preview"></p>
			<p id="resultPreview" class="preview"></p>
			<p><textarea type="text" id="result" placeholder="The inverted <table> will be here"></textarea></p>
		</div>
		
	</div>
	
	<div id="container">
		<h2>Why this website?</h2>
		<p>When you write a table in HTML, sometimes you would like to try it with the row and columns inverted, like with the transpose of a matrix. There is various techniques to do it in CSS, but what if you want to do it in pure HTML? Well you have to go cell by cell and swap them manually. Pretty fastidious if you have a big table...</p>
		<p>That's why I created this website! In a few clicks you can get an HTML table identical to your original table, but with the row and columns inverted.</p>
	</div>

	<p id="footer">Made by <a href="https://www.feelouttheform.net/" target="_blank">Nicolas Form</a> under <a href="https://creativecommons.org/licenses/by-sa/3.0/" target="_blank">CC BY SA 3.0</a> (<a href="https://github.com/nicolasform/htmltableswapper/" target="_blank">see source here</a>). This website does not put any tracker or cookie in your browser and does not collect what you put in the text box.</p>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
		
		function prettyTable(tableCode) {
			return tableCode
				.replace(/[ \t\n\r]*</gi,"<")  // Remove all spaces and carriage return
				.replace(/<tr>/gi,"\n   <tr>")
				.replace(/<\/tr>/gi,"\n   </tr>")
				.replace(/<th>/gi,"\n      <th>")
				.replace(/<td>/gi,"\n      <td>")
			;
		}
		
		// Built on: https://stackoverflow.com/a/6298066/3161534		
		function checkTable() {
			
			if($("#mainInput").val() === "") {
				$("#result").val("").removeClass("error");
				$("#mainInputPreview").html("");
				$("#resultPreview").html("");
			} else {
				
				try {
					let table = $($("#mainInput").val());
					if(table.prop("tagName") == "TABLE") {
						
						table.each(function() {
							var $this = $(this);
							var newrows = [];
							$this.find("tr").each(function(){
								var i = 0;
								$(this).find("td,th").each(function(){
									i++;
									if(newrows[i] === undefined) { newrows[i] = $("<tr></tr>"); }
									newrows[i].append($(this));
								});
							});
							$this.find("tr").remove();
							$.each(newrows, function(){
								$this.append(this);
							});
						});
			
						// Display result
						$("#mainInputPreview").html($("#mainInput").val());
						$("#result").val(prettyTable(table.prop('outerHTML'))).removeClass("error");
						$("#resultPreview").html($("#result").val());
						
					} else {
						$("#result").val("Your table should be a <table> element.").addClass("error");
						$("#mainInputPreview").html("");
						$("#resultPreview").html("");
					}
				
				} catch(e) {
						$("#result").val("Your <table> is malformed.").addClass("error");
						$("#mainInputPreview").html("");
						$("#resultPreview").html("");
				}
				
			}
			
		}
		
		// Run once in case the input is pre-filled
		checkTable();
		
	</script>
	
</body>

</html>
