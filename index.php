<?php
    session_start();
    if (isset($_POST['logout'])) {
        header("Location: logout.php");
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
      background-image: url('image/collage.jpg');
      /* Remove the existing background image */
    }

    body::after {
      content: "";
      background-image: url('https://www.iconsdb.com/icons/preview/black/museum-xxl.png');
      /* Add a new background image */
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-size: cover;
      background-position: center;
      opacity: 0.5;
      /* Adjust opacity as needed */
    }

    /* Remaining styles remain unchanged */

    /* Header styles */
    header {
      background-color: #333;
      color: #fff;
      padding: 20px;
      text-align: center;
      position: relative; /* Added position relative */
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

    /* Profile picture and dropdown */
    .profile-picture {
      position: absolute;
      top: 20px;
      right: 100px; /* Adjusted right position */
      cursor: pointer;
      display: inline-block;
    }

    .profile-picture img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    .profile-dropdown {
      position: absolute;
      top: 60px; /* Adjusted top position */
      right: 0;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
      display: none;
    }

    .profile-dropdown.active {
      display: block;
    }

    .profile-dropdown-content {
      padding: 12px 16px;
      text-decoration: none;
      color: black;
      display: block;
    }

    .profile-dropdown-content a {
      display: block;
      margin-bottom: 10px;
      text-decoration: none;
      color: black;
    }

    .profile-dropdown-content a:hover {
      background-color: #f1f1f1;
    }

    /* Content styles */
    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.8);
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

    /* Employee login button */
    .employee-login {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      
      position: fixed; /* Fixed positioning relative to the viewport */
      bottom: 0;       /* Align to the bottom */
      left: 50%;       /* Center horizontally */
      transform: translateX(-50%); /* Center the element with respect to its width */
    }
    #profileDropdown {
    display: none;
    }
    #profileDropdown.active {
        display: block;
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
    <button class="button">Dining</button>
    <button class="button">Tickets</button>
    <button class="button">Data Reports</button>
  </div>
        <?php
        if ($result) {
                echo '  <div class="profile-picture" id="profilePicture">
                        <img src="image/avatar.jpg" alt="Profile Picture">
                        <div class="profile-dropdown" id="profileDropdown">
                          <div class="profile-dropdown-content">
                            <a href="profile.php">View Profile</a>
                            <a href="#">Settings</a>
                            <a href="logout.php">Logout</a>
                          </div>
                        </div>
                      </div>';
            //echo '<form action="" method="post">
            //      <button type="submit" name="logout" class="login-button">Logout</button>
            //      </form>';
        } else {
            echo '<button id="loginButton" class="login-button">Login</button>';
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
  <!-- Employee login button -->
  <a href="elogin.php" class="employee-login">Employee Login</a>

      <script>
      // Get the modal
    document.addEventListener('DOMContentLoaded', function () {
        var loginButton = document.getElementById("loginButton");
        var employeeLoginButton = document.querySelector(".employee-login");
        var profilePicture = document.getElementById("profilePicture");
        var profileDropdown = document.getElementById("profileDropdown");

        // When the user clicks on the profile picture, toggle the dropdown
        profilePicture.onclick = function () {
        profileDropdown.classList.toggle("active");
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
        }
    });
      </script>
</body>
</html>
