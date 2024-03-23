<?php

    session_start();

    include("classes/connect.php");
    include("classes/login.php");
    include("classes/user.php");

    //check if user is logged in
    if(isset($_SESSION['museum_userid']) && is_numeric($_SESSION['museum_userid']))
    {
        $id = $_SESSION['museum_userid'];
        $login = new Login();

        $result = $login->check_login($id);
        if($result)
        {
            //retrieve user data
            $user = new User();
            $user_data = $user->get_data($id);

            if(!$user_data){
                header("Location: login.php"); /////
                die;
            }
        } 
        else {
            header("Location: login.php");
            die;
        }
    } else {
        header("Location: login.php");
        die;
    }

    //implementing functionality to update user info
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-profile"])) {
        // retrieve form data
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
    
        // validate form data
        if (!empty($first_name) && !empty($last_name) && !empty($email)) {
            // update user information in the database
            $user = new User();
            $update_result = $user->update_data($id, $first_name, $last_name, $email, $password);
    
            if ($update_result) {
                // redirect back to the profile page with a success message
                header("Location: profile.php?update=success");
                exit;
            } else {
                // redirect back to the profile page with an error message
                header("Location: profile.php?update=error");
                exit;
            }
        } else {
            // redirect back to the profile page with a validation error message
            header("Location: profile.php?update=validation_error");
            exit;
        }
    }

    //handle logout action
    if(isset($_GET['logout'])){
        session_start();
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit;
    }
    
?>


<!DOCTYPE html>
<html>
<head>
    <title>User Profile Settings</title>
  <link href="https://fonts.googleapis.com/css2?family=Franklin+Gothic:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Franklin-Gothic', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e3dbd8;
        }
        
        .header {
            background-color: #442b63;
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            display: flex;
            align-items: center;
        }
        
        .header-title {
            margin-right: 10px;
            font-size: 20px;
        }
        
        .logout-button {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }
        
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
            cursor: pointer;
        }
        
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .container {
            display: flex;
            padding: 20px;
        }
        
        .profile-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-right: 20px;
        }
        
        .profile-pic-big {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            overflow: hidden;
            margin-bottom: 20px;
            background-color: #ddd; /* Default avatar background color */
        }
        
        .profile-pic-big img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-details {
            margin-bottom: 20px;
            width: 100%;
        }
        
        .profile-details label {
            font-weight: bold;
        }
        
        .profile-details input {
            width: calc(50% - 10px);
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .profile-details .profile-pic-input {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .submit-button {
            background-color: #442b63;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 50%;
        }
        
        .submit-button:hover {
            background-color: #0056b3;
        }
        
        /* Dropdown styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #ddd;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

    </style>
</head>
<body>
    <!--header-->
    <div class="header">
        <div class="header-left">
            <div class="header-title">MFAH | Profile</div>
        </div>
        <div class="dropdown">
            <div class="profile-pic">
                <img src="image/avatar.jpg" alt="Profile Picture">
            </div>
            <div class="dropdown-content">
                <a class="logout-button" href="logout.php">Logout</a>
            </div>
        </div>
    </div>

    <!--profile section-->
    <div class="container">
        <div class="profile-section">
            <div class="profile-pic-big">
                <img src="image/avatar.jpg" alt="Profile Picture">
            </div>
            <div class="profile-details">
                <input type="file" class="profile-pic-input" accept="image/*" />
            </div>
        </div>
        <div class="profile-section">
            <div class="profile-details">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($user_data['first_name']); ?>">
            </div>
            <div class="profile-details">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($user_data['last_name']); ?>">
            </div>
            <div class="profile-details">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" readonly>
            </div>
            <div class="profile-details">
                <label for="password">Current Password</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($user_data['password']); ?>" readonly>
            </div>
            <div class="profile-details">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Enter your new password">
            </div>
            <div class="profile-details">
                <label for="newPassword">Confirm New Password</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Confirm your new password">
            </div>
            <button class="submit-button" type="submit">Save Changes</button>
        </div>
    </div>

    <!-- come back later to masking functions -->
    <!-- <script>

        function maskEmail(email){
            // mask characters between '@' and '.'
            var atIndex = email.indexOf('@');
            var dotIndex = email.lastIndexOf('.');
            var maskedPart = email.substring(atIndex + 1, dotIndex).replace(/./g, '*');
            return email.substring(0, atIndex + 1) + maskedPart + email.substring(dotIndex);
        }

        function maskPassword(password){
            // mask entire password
            return '*'.repeat(password.length);
        }

    </script> -->

    <!-- <script>
        document.querySelector('.submit-button').addEventListener('click', function() {
            var formData = new FormData(document.querySelector('form'));

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_profile.php');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // request was successful
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Profile updated successfully!');
                    } else {
                        alert('Error: ' + response.message);
                    }
                } else {
                    // error occurred
                    alert('Error: ' + xhr.statusText);
                }
            };
            xhr.send(formData);
        });
    </script> -->
    


</body>
</html>
