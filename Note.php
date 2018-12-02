<?php
	require_once 'include/DB_Functions.php';
	$db = new DB_Functions();
	session_start();

	//redierct to login page if user is not login 
	if(!isset($_SESSION['name'])){
		header("Location: login.php");
	}
	
	$email = $_SESSION['email'];
	$id = $_SESSION['id'];
	$name = $_SESSION['name'];

	$welcomText = $name." note's";
?>

<!doctype html>
<html lang="en">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<style type="text/css">
			.container{
				text-align: center;
				width: 400px;
			}
			.navbar {
				background-color:transparent;
			}
			html { 
				background: url(background.jpg) no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
			}
			body{
				background: none;
			}
			p{
				margin-top: 20px;
			}
			#note{
				width: 100%;
				height: 800px;
				
			}
			h3{
				color: white;
			}
			button{
				margin: 5px;
			}

		</style>
		<title>Login</title>
	</head>

<body onload="onloadFunction()">

	<nav class="navbar">
		<h3><? echo $welcomText; ?></h3>
		<form class="form-inline">
			<a href="logout.php">Logout</a>
		</form>
	</nav>

	<div class="container-fluid">
		<h1></h1>
		<div class="row">

			<div class="col-2">
				<!--Note List View-->
				<div class="list-group" id="notes-list"></div>
				<!--Buttons Div two button-->
				<div class="d-flex justify-content-center">
					<button type="button" class="btn btn-success" id="addNote">Add Note</button>
					<button type="button" class="btn btn-danger" id="deletNote">Delete Note</button>
				</div>
			</div>

			<div class="col-10">
				<textarea id="note" class="form-control" value="note"></textarea>
			</div>

		</div>
	</div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript">

		//small tip all note content saved in name attribute

		var selectedNoteID;

		//this function update notes-list view and execute when page is fully loaded
		//get Notes with code -1 which indicate to activate first note and set fisrt note content to text area
		//1 mean update text area beased on activate note which is first note
		function onloadFunction() {
			updateNoteListView(-1, 1)
		}
		
		//select note which activate selected note and disactivate rest notes and update selectedNoteID var
		$('.list-group').on('click', '.list-group-item', function() {
			var $this = $(this);
			var $alias = $this.data('alias');

			$('.active').removeClass('active');
			$this.toggleClass('active');
			
			selectedNoteID = $this.attr('id');		

			var noteValue = $this.attr('name');
			$('#note').val(noteValue);
			
		});

		//update note in database when any key is pressed in textarea
		$('#note').bind('input propertychange', function() {
			const xhr = new XMLHttpRequest();
			xhr.open("POST", "updatedatabase.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send("newNote=" +  $('#note').val() + "&noteId=" +  selectedNoteID);
			//update list view note after save note and activate the note which has been updated
			//0 mean do no thing after update list view note
			updateNoteListView(selectedNoteID, 0);
		});
		

		//add note button add new note and activate it
		$( "#addNote" ).click(function() {
			const xhr = new XMLHttpRequest();
			xhr.onload = function () {
				//this.responseText is new note id
				var newNoteID = this.responseText;
				//update notes-list view by new note id so it will be active 
				//1 mean update text area beased on activate note which is the new note
				updateNoteListView(newNoteID, 1);
			};
			xhr.open("POST", "updatedatabase.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send("addNote");
		});
			
		//delete selected note button and activate first note
		$( "#deletNote" ).click(function() {
			const xhr = new XMLHttpRequest();
			xhr.onload = function () {
				//update notes-list view and active first note 
				//1 mean update text area beased on activate note which is first note
				updateNoteListView(-1 , 1);
			};
			xhr.open("POST", "updatedatabase.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.send("deleteNote&noteId=" + selectedNoteID);
		});
			
		//update list view note 
		function updateNoteListView(noteID, code) {
			const xhr = new XMLHttpRequest();
			xhr.onload = function () {
				$("#notes-list").html(this.responseText);
				if(code === 1){
					//update text area for selected note
					var noteValue = $('#notes-list').find('.active').attr('name');
					$('#note').val(noteValue);
					selectedNoteID = $('#notes-list').find('.active').attr('id');
				}
			};
			xhr.open("POST", "updatedatabase.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			if(noteID == -1){
				xhr.send("updateNoteListView=-1");
			}else {
				xhr.send("updateNoteListView=" + noteID);
			}
		}


    </script>

</body>

</html>