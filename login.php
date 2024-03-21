<?php
//require 'C:\wamp64\www\museum\vendor\autoload.php';
session_start();

    include("classes/connect.php");
    include("classes/login.php");
    
    $email = "";
    $password = "";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $login = new Login();
        $result = $login->evaluate($_POST);
        
        if ($result != ""){
            echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
            echo "The following error occured:<br><br>";
            echo $result;
            echo "</div>";
        } else {
            header("Location: index.php");
            die;
        }
            
        $email = $_POST['email'];
    }
?>

<html>
    <head>
        <title>Museum of Fine Arts | Login</title>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <style>

            .toggle-password {
                position: absolute;
                right: 10px; /* Adjust the position as needed */
                top: 50%;
                transform: translateY(-50%);
                cursor: pointer;
            }
            #password-input {
                padding-right: 30px; /* Adjust the padding to leave space for the eye icon */
            }
            body{
                background-image: url('image/museum.jpg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;             
            }
            body::after {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.9); /* Start with a darker color */
                z-index: -1;
                animation: fadeIn 1s ease-out forwards; /* Apply fading animation */
            }
            @keyframes fadeIn {
                from { opacity: 0; } /* Start with opacity 0 */
                to { opacity: 0.9; } /* Fade to the desired opacity */
            }
            #Bar {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100px;
                width: 400px;
                background-color: #C5EBAA;
                color: black; 
                font-size: 40px;
                margin: auto;
                padding: 4px;
                font-weight: bold;
                text-align: center;
                border-radius: 50px;
                transition: all 0.3s ease;
            }
            #Bar:hover {
            transform: scale(1.1); /* Scale up on hover */
            }
            #Bar_Create{
                background-color:#eeede0;
                width:400px;
                margin:auto;
                margin-top:25px;
                padding:10px;
                padding-top:50px;
                text-align: center;
                border-radius: 50px;
                font-weight:bold;
                transition: all 2s ease;
                }
            #Bar_Login{
                margin:auto;
                margin-top:25px;
                padding-top:50px;
                text-align: center;
                border-radius: 50px;
                font-weight:bold;
            }
            #text{
                height:40px;
                width:300px;
                border-radius: 50px;
                border:solid 1px #888;
                padding: 2px;
                font-size: 14px;
                padding-left: 15px;
            }
            #button{
                width: 200px;
                height: 40px;
                border-radius: 50px;
                font-weight: bold;
                background-color:#A5DD9B;
                color:black;
            }
            #login{
                display: inline-block;
                padding: 10px 20px;
                color: white;
                text-decoration: none; /* Remove underline from the link */
            }
            #login:hover {
                background-color: transparent;
            }
            #login-text {
                font-weight: bold;
                text-decoration: underline;
                margin-left: 1px;
            }
            #button-group {
                background-color: transparent;
                color: black;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer; /* Change cursor to pointer on hover */
                transition: background-color 0.3s; /* Add transition for smooth hover effect */
                margin-top: 5px; /* Adjust the spacing between the submit button and the previous input group */
            }
            #button-group input[type="submit"]:hover {
                background-color: #8AC884;
            }
            #login-group {
                margin-top: -15px; /* Adjust the spacing between the login button and the previous input group */
            }
            .input-group {
                margin-bottom: 5px; /* Adjust the spacing between input groups */
            }
            .input-group input {
                margin-bottom: 5px; /* Adjust the spacing between inputs within a group */
            }
        </style>
    </head>
    <body style="font-family: Papyrus;">
        <div id="Bar"> 
            <div> 
            Museum of Fine Arts
            </div>
        </div>
        <div id="Bar_Create"> 
            Log in with MoFA account<br><br>
            <form method="post" action="">
                <div class="input-group">
                    <input value ="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email">
                </div>           
                <div class="input-group">
                    <input name="password" type="password" id="text" placeholder="Password">
                </div> 
                <div id="button-group" class="input-group">
                    <input type="submit" id="button" value="Log In">
                </div>
            </form>
        </div>
        <div id="Bar_Login">
            <div id="login-group" class="input-group">
                <a href="signup.php" id="login">Need an account? <span id="login-text">Create</span></a>
            </div>
        </div>

    </body>
</html>