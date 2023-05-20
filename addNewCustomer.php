<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["customer_name"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT sno FROM notes WHERE customer_name = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['customer_name']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['customer_name']);
                    $balance = $_POST['balance'];
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    

    mysqli_stmt_close($stmt);
}

// Check for password
// if(empty(trim($_POST['password']))){
//     $password_err = "Password cannot be blank";
// }
// elseif(strlen(trim($_POST['password'])) < 5){
//     $password_err = "Password cannot be less than 5 characters";
// }
// else{
//     $password = trim($_POST['password']);
// }

// // Check for confirm password field
// if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
//     $password_err = "Passwords should match";
// }


// If there were no errors, go ahead and insert into the database
if(empty($username_err))
{
    $sql = "INSERT INTO notes (customer_name, balance) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_balance);

        // Set these parameters
        $param_username = $username;
        $param_balance = $balance;

        // Try to execute the query
        if (mysqli_stmt_execute($stmt)){
            header("location: welcome.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>My Business</title>
  </head>
  <body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="/login_cwh/login.php">My Business</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
  <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="/login_cwh/login.php">Home <span class="sr-only">(current)</span></a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link" href="register.php">Register</a>
      </li> -->
      <?php
        if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
        {
            echo '<li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
            </li>';
        }
        else{
            echo '<li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>';
        }
    ?>

      
     
    </ul>
  </div>
</nav>

<div class="container mt-4">
<h3>Add New Customer Here:</h3>
<hr>

<form action="" method="post">
  <div class="form-group">
    <label for="customer_name">Customer Name</label>
    <input type="text" name="customer_name" class="form-control col-md-3" id="customer_name" aria-describedby="emailHelp" placeholder="Enter Customer Name">
  </div>
  <div class="form-group">
    <label for="balance">Initial Balance</label>
    <input type="password" name="balance" class="form-control col-md-3" id="balance" placeholder="Enter Initial Balance">
  </div>
  <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary">Create</button>
</form>



</div>

<div class="container mt-5 text-center">
    <br>
    <br>
    <hr>
    <h6>Developed By</h6>
    <h4>Suprodip Sarkar</h4>
</div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
