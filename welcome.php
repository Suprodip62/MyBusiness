<?php
require_once "config.php";

session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: login.php");
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
            // <li class="nav-item ml-4">
            //     <a class="nav-link" href="register.php">Register</a>
            //   </li>

              <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
              </li>';
    }
    else{
        
            if($_SESSION['username'] == "Suprodip" || $_SESSION['username'] == 'Supro'){
                echo '<li class="nav-item ml-4">
                    <a class="nav-link" href="addNewCustomer.php">Add New Customer</a>
                    </li>';
            }
        
        echo '<li class="nav-item ml-4">
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

<div class="container mt-4">
    <h3><?php echo "Welcome ". $_SESSION['username']?>! You can now use this website</h3>
    <hr>
</div>
<!-- <div class="container mt-4">
    <h4><?php echo "Your Balance: ". $_SESSION['balance']?> tk</h4>
    <hr>
</div> -->
<!-- <?php
    $id1 = $_SESSION['id'];
    $sql = "SELECT * FROM `users` WHERE id = $id1";
    $result = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_assoc($result)){
        echo "<div class='container mx4'><h4> Your Balance: ". $row['balance']." tk.</h4></div>";
    }
?> -->















<div class="container">
<?php  
// INSERT INTO `notes` (`sno`, `title`, `description`, `tstamp`) VALUES (NULL, 'But Books', 'Please buy books from Store', current_timestamp());
$insert = false;
$update = false;
$delete = false;
// Connect to the Database 
// $servername = "localhost";
// $username = "root";
// $password = "";
// $database = "notes";

// // Create a connection
// $conn = mysqli_connect($servername, $username, $password, $database);

// // Die if connection was not successful
// if (!$conn){
//     die("Sorry we failed to connect: ". mysqli_connect_error());
// }

if(isset($_GET['delete'])){
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
    $result = mysqli_query($conn, $sql);
    $delete = true;
    header ("location: welcome.php");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    // if (isset( $_POST['snoEdit'])){
    // // Update the record
    //     $sno = $_POST["snoEdit"];
    //     $title = $_POST["titleEdit"];
    //     $description = $_POST["descriptionEdit"];

    //     // Sql query to be executed
    //     $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
    //     $result = mysqli_query($conn, $sql);
    //     if($result){
    //         $update = true;
    //     }
    //     else{
    //         echo "We could not update the record successfully";
    //     }
    // }
    if(isset($_POST['debit'])){
        $debit = $_POST['debit'];
        $amount = $_POST['amount'];
        if($debit == "Debit"){
            // $_SESSION['balance'] += $amount;

            // $id = $_SESSION['id'];
            // $username = $_SESSION['username'];
            $username = $_POST['customer'];
            $description = $_POST['description'];
            $newbalance = $amount;

            // $sql1 = "SELECT * FROM `users` WHERE `users`.`id` = $id";
            $sql1 = "SELECT * FROM `notes`";
            $res = mysqli_query($conn, $sql1);
            while($row = mysqli_fetch_assoc($res)){
                if($row['customer_name'] == $username){
                    $newbalance = $newbalance + (intval($row['balance']));
                    $oldBalance = intval($row['balance']);
                    $id = $row['sno'];
                    // echo "new balance added successfully";
                }
            }

            
            echo "New balance: ". $newbalance . "<br>";

            $sql = "UPDATE `notes` SET `balance` = $newbalance WHERE `notes`.`sno` = $id";
            $result = mysqli_query($conn, $sql);
            if($result){
                $update = true;
                echo "Balance updated successfull";
            }
            else{
                echo "We could not update the record successfully";
            }
            //Insert into debitlog table
            $sql = "INSERT INTO `debitlog` (`username`, `debit`, `oldBalance`, `amount`, `description`, `newbalance`) VALUES ('$username', 1, $oldBalance, $amount, '$description', $newbalance)";
            $result = mysqli_query($conn, $sql);
        }
        else if($debit == "Credit"){
            // $id = $_SESSION['id'];
            // $username = $_SESSION['username'];
            $username = $_POST['customer'];
            $description = $_POST['description'];
            $newbalance = $amount;

            // $sql1 = "SELECT * FROM `users` WHERE `users`.`id` = $id";
            $sql1 = "SELECT * FROM `notes`";
            $res = mysqli_query($conn, $sql1);
            while($row = mysqli_fetch_assoc($res)){
                if($row['customer_name'] == $username){
                    $newbalance = (intval($row['balance'])) - $newbalance;
                    $oldBalance = (intval($row['balance']));
                    // echo "new balance added successfully";
                    $id = $row['sno'];
                }
            }
            if($newbalance >= 0){
                echo "New balance: ". $newbalance . "<br>";

                $sql = "UPDATE `notes` SET `balance` = $newbalance WHERE `notes`.`sno` = $id";
                $result = mysqli_query($conn, $sql);
                if($result){
                    $update = true;
                    echo "Balance updated successfully";
                }
                else{
                    echo "We could not update the record successfully";
                }
                $sql = "INSERT INTO `debitlog` (`username`, `debit`, `oldBalance`, `amount`, `description`, `newbalance`) VALUES ('$username', 0, $oldBalance, $amount, '$description', $newbalance)";
                $result = mysqli_query($conn, $sql);
            }
            else{
                echo "<br> Low balance!  Not possible to credit this amount<br>";
            }
        }
        else{
            echo "choose debit or credit";
        }
    }
    // else{
    //     $title = $_POST["title"];
    //     $description = $_POST["description"];

    //     // Sql query to be executed
    //     $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
    //     $result = mysqli_query($conn, $sql);


    //     if($result){ 
    //         $insert = true;
    //     }
    //     else{
    //         echo "The record was not inserted successfully because of this error ---> ". mysqli_error($conn);
    //     } 
    // }
}
?>

<!-- <!doctype html>
<html lang="en">

<head> -->
<!-- Required meta tags -->
<!-- <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"> -->

<!-- Bootstrap CSS -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">


<title>iNotes - Notes taking made easy</title>

</head>

<body> -->


<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        </div>
        <form action="/login_cwh/welcome.php" method="POST">
        <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            
            <div class="form-group">
            <label for="title">Note Title</label>
            <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
            </div>

            <div class="form-group">
            <label for="desc">Note Description</label>
            <textarea class="form-control" id="descriptionEdit" name="descriptionEdit" rows="3"></textarea>
            </div> 
        </div>
        <div class="modal-footer d-block mr-auto">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
    </div>
    </div>
</div>

<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#"><img src="/crud/logo.svg" height="28px" alt=""></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
        <a class="nav-link" href="#">Contact Us</a>
        </li>

    </ul>
    <form class="form-inline my-2 my-lg-0">
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    </div>
</nav> -->
<!-- 
<?php
    if($insert){
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been inserted successfully
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
        </button>
        </div>";
    }
?>
<?php
    if($delete){
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been deleted successfully
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
        </button>
        </div>";
    }
?>
<?php
    if($update){
        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
        <strong>Success!</strong> Your note has been updated successfully
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
        <span aria-hidden='true'>×</span>
        </button>
        </div>";
    }
?> -->
<!-- <div class="container my-4">
    <h2>Add a Note to iNotes</h2>
    <form action="/login_cwh/welcome.php" method="POST">
    <div class="form-group">
        <label for="title">Note Title</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
    </div>

    <div class="form-group">
        <label for="desc">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Add Note</button>
    </form>
</div> -->




<?php
    if($_SESSION['username'] == "Suprodip" || $_SESSION['username'] == 'Supro'){ echo'
<div class="container my-4">
    <h2>Debit / Credit</h2>
    <form action="/login_cwh/welcome.php" method="POST">

    <!-- // new div -->
    <div class="form-group col-md-4">
        <label for="customer">Select Customer</label>
        <select id="customer" name="customer" class="form-control">
            <option selected>Choose...</option>';
            
                $sql = "SELECT * FROM `notes`";
                $result = mysqli_query($conn, $sql);

                while($row = mysqli_fetch_assoc($result)){
                        echo "<option>". $row["customer_name"] . "</option>";
                }
            echo '
        </select>
    </div>
    <!-- // new div -->

    <div class="form-group col-md-4">
        <label for="debit">Debit or Credit</label>
        <select id="debit" name="debit" class="form-control">
            <option selected>Choose...</option>
            <option>Debit</option>
            <option>Credit</option>
        </select>
    </div>

    <div class="form-group col-md-4">
        <label for="amount">Enter Amount</label>
        <input type="text" class="form-control" id="amount" name="amount">
    </div>
    <div class="form-group col-md-4">
        <label for="description">Note Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary ml-3">Submit</button>
    </form>
</div>
';}
?>

<?php
    // if($_SESSION['username'] == "Suprodip" || $_SESSION['username'] == 'Supro'){
        echo '<div class="container my-4">
            <table class="table text-center" id="myTable">
            <thead>
                <tr>
                <th scope="col">S.No</th>
                <th scope="col">Customer name</th>
                <th scope="col">Balance</th>';
                if($_SESSION['username'] == "Suprodip" || $_SESSION['username'] == "Supro"){
                    echo '<th scope="col">Actions</th>';
                }
                echo '
                </tr>
            </thead>
            <tbody>';
         
            $sql = "SELECT * FROM `notes`";
            $result = mysqli_query($conn, $sql);
            $sno = 0;
            while($row = mysqli_fetch_assoc($result)){
                $sno = $sno + 1;
                echo "<tr>
                <th scope='row'>". $sno . "</th>
                <td><a href='details.php?details={$row['customer_name']}'>". $row['customer_name'] . "</a></td>
                <td>". $row['balance'] . "</td>";
                if($_SESSION['username'] == "Suprodip" || $_SESSION['username'] == "Supro"){
                echo "<td> <!-- <button class='edit btn btn-sm btn-primary' id=".$row['sno'].">Edit</button> --> <button class='delete btn btn-sm btn-primary' id=d".$row['sno'].">Delete</button>  </td>";
                }
                else{

                }
                echo "</tr>";
            } 
    // }
    // /login_cwh/welcome.php?delete=${sno}    
    // else{
    //     $username = $_SESSION['username'];
    //     echo "<br>Username: ". $username;
        
    //     echo '<div class="container my-4">
    //     <table class="table text-center" id="myTable">
    //     <thead>
    //         <tr>
    //         <th scope="col">SL</th>
    //         <th scope="col">Username</th>
    //         <th scope="col">Debit/Credit</th>
    //         <th scope="col">Old Balance</th>
    //         <th scope="col">Amount</th>
    //         <th scope="col">New Balance</th>
    //         <th scope="col">Time</th>
    //         </tr>
    //     </thead>
    //     <tbody>';

    //     $sql = "SELECT * FROM `debitlog`";
    //     $result = mysqli_query($conn, $sql);
    
    //     $sno = 0;
    //     while($row = mysqli_fetch_assoc($result)){
    //         if($row['username'] == $username){
    //             $sno = $sno + 1;
    //             if($row['debit'] == 1) $debit = "Debit";
    //             else $debit = "Credit";
    //             echo "<tr>
    //             <th scope='row'>". $sno . "</th>
    //             <td>". $row['username'] . "</td>
    //             <td>". $debit . "</td>
    //             <td>". $row['oldBalance'] . "</td>
    //             <td>". $row['amount'] . "</td>
    //             <td>". $row['newbalance'] . "</td>
    //             <td>". $row['date'] . "</td>
    //             </tr>";
    //         }
    //     } 
    //     echo "
    //     </tbody>
    //     </table>";
    // }
?>


    </tbody>
    </table>
</div>
<hr>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
    $('#myTable').DataTable();

    });
</script>
<script>
    edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
        element.addEventListener("click", (e) => {
            console.log("edit ");
            tr = e.target.parentNode.parentNode;
            title = tr.getElementsByTagName("td")[0].innerText;
            description = tr.getElementsByTagName("td")[1].innerText;
            console.log(title, description);
            titleEdit.value = title;
            descriptionEdit.value = description;
            snoEdit.value = e.target.id;
            console.log(e.target.id)
            $('#editModal').modal('toggle');
        })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
    element.addEventListener("click", (e) => {
        console.log("delete");
        sno = e.target.id.substr(1);
        console.log(e.target.id);

        if (confirm("Are you sure you want to delete this note!")) {
            console.log("yes");
            window.location = `/login_cwh/welcome.php?delete=${sno}`;
            // TODO: Create a form and use post request to submit a form
        }
        else {
            console.log("no");
        }
    })
    })
</script>





















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
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
