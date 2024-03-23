<?php
    session_start();
    if (isset($_POST['logout'])) {
        unset($_SESSION['museum_employeeid']);
        header("Location: elogin.php");
        exit;
    }
    $result = false;
    
    include("classes/connect.php");
    include("classes/elogin.php");
    include("classes/employee.php");
    
    //check if employee is logged in
    if(isset($_SESSION['museum_employeeid']) && is_numeric($_SESSION['museum_employeeid'])){
        
        $eid = $_SESSION['museum_employeeid'];
        $elogin = new Elogin();
        
        $result = $elogin->check_elogin($eid);
        if($result){
            //get employee data
            $employee = new Employee();
            $employee_data = $employee->get_data($eid);
            
            if(!$employee_data){
                header("Location: elogin.php");
                die;
            }
        } else {
            header("Location: elogin.php");
            die;
        }
        
    } else {
            header("Location: elogin.php");
            die;
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Museum Database - Employee Portal</title>
    <style>
        /* Basic styling for the page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            background-image: url('image/picture.jpg')
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        h1 {
            margin-top: 0;
        }
        /* Profile section styles */
        .profile-section {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
        }
        .profile-picture {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 10px;
            cursor: pointer; /* Add cursor pointer */
        }
        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-dropdown {
            position: relative;
        }
        .profile-dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 120px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1;
            right: 0;
        }
        .profile-dropdown-content a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: #333;
        }
        .profile-dropdown-content a:hover {
            background-color: #f2f2f2;
        }
        .profile-dropdown.active .profile-dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Profile section -->
        <div class="profile-section">
            <div class="profile-picture" onclick="toggleDropdown()">
                <img src='image/no-profile-picture-icon.webp' alt="Profile Picture">
            </div>
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-dropdown-content">
                    <form action="" method="post">
                    <button type="submit" name="viewprofile" class="login-button">View Profile</button>
                    </form>
                    <form action="" method="post">
                    <button type="submit" name="setting" class="login-button">Setting</button>
                    </form>
                    <form action="" method="post">
                    <button type="submit" name="logout" class="login-button">Logout</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End of Profile section -->

        <h1>Employee Portal</h1>
        <p>Welcome to the employee portal of the museum database.</p>
        <p>Here, employees can access and manage various museum-related tasks.</p>
        <p>Access the <a href="datareport_employee.php">Employee Data Report</a>.</p>
        
        <?php
        //Display welcome message only if user data exists
        if ($result && $employee_data) {
            echo '<div>Welcome, ' . $employee_data['firstName'] . ' ' . $employee_data['lastName'] . '</div>';
        }
        ?>
    </div>


    <script>
        function toggleDropdown() {
            var dropdown = document.getElementById('profileDropdown');
            dropdown.classList.toggle('active');
        }
    </script>
</body>
</html>
