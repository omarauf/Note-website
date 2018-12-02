<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
    session_start();
    $error = "";

    if(isset($_SESSION['name'])){
		header("Location: Note.php");
	}
    
    if (isset($_POST['email']) && isset($_POST['password'])) {
    
        // receiving the post params
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if ($email == "" || $password == "") {
            // email or password is missing
            $error = '<p class="alert alert-danger" role="alert">Required parameters email or password is missing!</p>';
        } else {
            // get the user by email and password
            $user = $db->getUserByEmailAndPassword($email, $password);
            if ($user != false) {
                // user is found
                $_SESSION['email'] = $user["email"];
                $_SESSION['name'] = $user["name"];
                $_SESSION['id'] = $user["id"];
                
                //redierct to Note Page
                header("Location: Note.php");
                
            } else {
                // user is not found with the credentials
                $error = '<p class="alert alert-danger" role="alert">Login credentials are wrong. Please try again!</p>';
            }
        }

    }
?>

<!doctype html>
<html lang="en">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
			crossorigin="anonymous">
			
		<style type="text/css">
			.container{
				text-align: center;
				width: 400px;
                margin-top: 300px;
			}
            html{
                background: url(background.jpg) no-repeat center center fixed; 
                -webkit-background-size: cover;
                -moz-background-size: cover;
                -o-background-size: cover;
                background-size: cover;
            }
			body{
				background: none;
			}
			p {
				margin-top: 10px;
			}
            h1{
				color: white;
			}		
		</style>
		
		<title>Login</title>
	</head>

<body>

    <div class="container">

        <h1>Please Login</h1>
        <div id="error"><? if($error) echo $error;?></div>

        <form method="post">

            <!--email-->
			<fieldset class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Enter your email">
			</fieldset>
			
            <!--Password-->
            <fieldset class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Enter your password">
			</fieldset>
			
            <button type="submit" id="submit" class="btn btn-success">Submit</button>
			
        </form>
        <p><a href="register.php">Register</a></p>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</body>

</html>