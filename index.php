<!DOCTYPE html>

<?php
    //require 'C:\wamp64\www\museum\vendor\autoload.php';
    header('Content-Type: application/json');
    session_start();
    if (isset($_POST['logout'])) {

        unset($_SESSION['museum_userid']);
        

        header("Location: index.php");
        exit;
    }
    $result = false;
    
    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");
    
    //check if user is logged in
    if(isset($_SESSION['museum_userid']) && is_numeric($_SESSION['museum_userid'])){
        
        $id = $_SESSION['museum_userid'];
        $login = new Login();
        
        $result = $login->check_login($id);
        
        if($result){
            //get userdata
            $user = new User();
            $user_data = $user->get_data($id);
            
            if(!$user_data){
                header("Location: login.php");
                die;
            }
           
        } else {
            header("Location: login.php");
            die;
        }
        
    }
?>

<html lang="en"> 

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Our Museum!</title>
  <style>
    /* General styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f7f7f7;
    }

    /* Header styles */
    header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
    }

    header h1 {
      margin: 0;
      font-size: 36px;
    }

    /* Top buttons styles */
    .top-buttons {
      text-align: center;
    }

    .top-buttons button {
      margin: 10px;
      padding: 10px 20px;
      color: #333;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: Arial, sans-serif;
      font-size: 20px;
    }

    /* Login button styles */
    .login-button {
      position: absolute;
      top: 20px;
      right: 20px;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-family: Arial, sans-serif;
      font-size: 16px;
    }

    /* Content styles */
    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .container p {
      font-size: 18px;
      line-height: 1.6;
    }

    .container ul {
      list-style: none;
      padding: 0;
    }

    .container ul li {
      padding-left: 20px;
      margin-bottom: 10px;
      background-image: url('https://www.iconsdb.com/icons/preview/black/museum-xxl.png');
      background-repeat: no-repeat;
      background-size: 16px;
      background-position: left center;
      line-height: 1.6;
    }

    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      border-radius: 5px;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .signup-link {
      margin-top: 10px;
      text-align: center;
    }

    .signup-link a {
      color: blue;
      text-decoration: underline;
    }

    .signup-link a:hover {
      text-decoration: none;
    }

    /* Style for form inputs */
    .modal-content form label {
      display: block;
      margin-bottom: 10px;
    }

    .modal-content form input[type="text"],
    .modal-content form input[type="password"] {
      width: calc(100% - 22px);
      padding: 8px;
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    .modal-content form input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    /* Continue as Guest button */
    .continue-as-guest {
      display: block;
      margin: 10px auto;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <header>
    <h1>Welcome to Our Museum!</h1>
    <div class="top-buttons">
      <button class="button">Exhibits</button>
      <button class="button">Events</button>
      <button class="button">Current Events</button>
      <button class="button">Tickets</button>
    </div>
    <?php
        if ($result) {
            echo '<form action="" method="post">
                  <button type="submit" name="logout" class="login-button">Logout</button>
                  </form>';
        } else {
            //echo '<button id="loginButton" class="login-button">Login</button>';
            echo '<a href="login.php" class="login-button">Login</a>';
        }
    ?>
    <?php
        // Display welcome message only if user data exists
        if ($result && isset($user_data)) {
            echo '<div>Welcome, ' . $user_data['first_name'] . ' ' . $user_data['last_name'] . '</div>';
        }
    ?>
  </header>

  <!-- Main Content -->
  <div class="container">
    <p>Step into a world of wonder and discovery as you explore our museum's vast collection of artifacts and
      exhibitions.</p>
    <p>Our museum features:</p>
    <ul>
      <li>An extensive collection of ancient artifacts spanning various civilizations.</li>
      <li>Fascinating exhibits showcasing the natural world, from artwork to sculptures.</li>
      <li>Interactive displays and immersive experiences that bring art to life.</li>
      <li>Educational programs and events for visitors of all ages, including guided tours and workshops.</li>
    </ul>
  </div>

  <!-- Modal -->
  <div id="myModal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Login to myMuseum</h2>
      <form action="#" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <input type="submit" value="Login">
      </form>
      <div class="signup-link">
        <p>Don't have an account? <a href="#">Sign up!</a></p>
      </div>
    </div>
  </div>
  <!--
  <script>
    // Get the modal
    var modal = document.getElementById("myModal");
    var loginButton = document.getElementById("loginButton");

    // When the login button is clicked, show the modal
    loginButton.onclick = function () {
      modal.style.display = "block";
    };

    // Get the close button
    var closeButton = document.getElementsByClassName("close")[0];

    // When the user clicks on the close button, close the modal
    closeButton.onclick = function () {
      modal.style.display = "none";
    };

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function (event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };
  </script>-->
</body>

</html>