<?php
require_once "config.php";

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
}


?>

<?php
//todo
//accept get request from the url & fetch all the information of the username from the database then show

// if($_SESSION['username'] != "Suprodip" && $_SESSION['username'] != "Supro"){
//     header("location: welcome.php");
// }
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
    <a class="navbar-brand" href="/login_cwh/welcome.php">My Business</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
    <li class="nav-item active">
        <a class="nav-link" href="/login_cwh/welcome.php">Home <span class="sr-only">(current)</span></a>
    </li>
    
    <?php
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
    {
        echo '
              <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
              </li>';
    }
    else{
        echo '
                <li class="nav-item ml-4">
                    <a class="nav-link" href="addNewCustomer.php">Add New Customer</a>
                </li>
        <li class="nav-item ml-4">
            <a class="nav-link" href="logout.php">Logout</a>
        </li>';
    }
    ?>
    
    </ul>

    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
            <a class="nav-link" href="/login_cwh/welcome.php"> <img src="https://img.icons8.com/metro/26/000000/guest-male.png"> <?php echo "Welcome ". $_SESSION['username']?></a>
        </li>
        </ul>
    </div>


    </div>
</nav>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
<?php
// echo $_GET['details'];
//OUTPUT: S/SS/SSS/SSSS/Suprodip/Supro

if(isset($_GET['details'])){
    $username = $_GET['details'];
    // echo "<br>Username: ". $username;
    //OUTPUT: S/SS/SSS/SSSS/Suprodip/Supro
    
    $html = '<div class="container my-4 text-center">
    <table class="table" id="myTable">
    <thead>
        <tr>
        <th scope="col">SL</th>
        <th scope="col">Username</th>
        <th scope="col">Debit/Credit</th>
        <th scope="col">Old Balance</th>
        <th scope="col">Amount</th>
        <th scope="col">Description</th>
        <th scope="col">New Balance</th>
        <th scope="col">Time</th>
        </tr>
    </thead>
    <tbody>';
    // echo $html;
    

    // $sql = "SELECT * FROM `debitlog` WHERE `debitlog`.`username` = $username";
    // $sql = "SELECT * FROM `debitlog` WHERE `debitlog`.`id` = 4";
    $sql = "SELECT * FROM `debitlog`";
    $result = mysqli_query($conn, $sql);

    $sno = 0;
    
    while($row = mysqli_fetch_assoc($result)){
        if($row['username'] == $username){
            $sno = $sno + 1;

            if($row['debit'] == 1) $debit = "Debit";
            else $debit = "Credit";
            $html .= "<tr>
            <th scope='row'>". $sno . "</th>
            <td>". $row['username'] . "</td>
            <td>". $debit . "</td>
            <td>". $row['oldBalance'] . "</td>
            <td>". $row['amount'] . "</td>
            <td>". $row['description'] . "</td>
            <td>". $row['newbalance'] . "</td>
            <td>". $row['date'] . "</td>
            </tr>";
        }
    } 
    $html .= "
    </tbody>
    </table>";
    echo $html;
    // session_start();
    $_SESSION['html'] = $html;
    // header ("location: welcome.php");


    // echo '<button type="button" class="btn btn-primary"><a href="download.php" class="">Download</a></button>';
    echo '<a class="btn btn-primary" href="download.php" role="button">Download</a>';
    // $sql = "SELECT * FROM `notes`";
    // $result = mysqli_query($conn, $sql);
    // $pm_key = 0;
    // while($row = mysqli_fetch_assoc($result)){
    //     if($row['customer_name'] == $username){
    //         $pm_key = $row['sno'];
    //     }
    // }
}




?>

<!-- <body>
            <form action="/login_cwh/download.php" method="post">
                <div class="form-group">
                    <label for="exampleInputEmail1">Username</label>
                    <input type="text" name="username" class="form-control col-md-3" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Username">
                </div>
                <button type="submit" class="btn btn-primary">Download</button>
            </form>
</body> -->










<div class="container mt-5 text-center">
    <br>
    <br>
    <hr>
    <h6>Developed By</h6>
    <h4>Suprodip Sarkar</h4>
</div>