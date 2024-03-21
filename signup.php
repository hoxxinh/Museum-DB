<?php
    //require 'C:\wamp64\www\museum\vendor\autoload.php';
    include("classes/connect.php");
    include("classes/signup.php");
    
    $email = "";
    $first_name = "";
    $last_name = "";
    $password = "";
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $signup = new Signup();
        $result = $signup->evaluate($_POST);
        
        if ($result != ""){
            echo "<div style='text-align:center;font-size:12px;color:white;background-color:grey;'>";
            echo "The following error occured:<br><br>";
            echo $result;
            echo "</div>";
        } 
            
        $email = $_POST['email'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
    }
    
    
?>

<html>
    <head>
        <title>Museum of Fine Arts | Signup</title>
    </head>
    <style>

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
            margin-top:25px;
            padding: 4px;
            font-weight: bold;
            text-align: center;
            border-radius: 50px;
            transition: all 0.3s ease;
            transform: translateX(-100%); /* Start the element offscreen to the left */
            animation: flyInFromLeft 1s forwards; /* Apply flyInFromLeft animation */
        }
        @keyframes flyInFromLeft {
            from { transform: translateX(-100%); } /* Start offscreen to the left */
            to { transform: translateX(0); } /* Fly in to the center */
        }
        #Bar_Create {
            background-color:#eeede0;
            width:400px;
            margin:auto;
            margin-top:25px;
            padding:10px;
            padding-top:50px;
            text-align: center;
            border-radius: 50px;
            font-weight:bold;
            opacity: 0; 
            animation: fadeIn 2s forwards; /* Apply fadeIn animation */
        }
        @keyframes fadeIn {
            from { opacity: 0; } /* Start with opacity 0 */
            to { opacity: 1; } /* Fade to full opacity */
        }
        #Bar_Login {
            width:300px;
            margin:auto;
            margin-top:25px;
            text-align: center;
            font-weight:bold;
            transition: all 2s ease;
            transform: translateX(100%); /* Start the element offscreen to the right */
            animation: slideInFromRight 1s forwards; /* Apply slideInFromRight animation */
            background-color: transparent;
        }
        @keyframes slideInFromRight {
            from { transform: translateX(100%); } /* Start offscreen to the right */
            to { transform: translateX(0); } /* Slide in to the center */
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
    <body style="font-family: Papyrus;">       
        <div id="Bar"> 
            <div> 
            Museum of Fine Arts
            </div>
        </div>
        <div id="Bar_Create"> 
            Create a MoFA account<br><br>
            <form method="post" action="">
                <div class="input-group">
                    <input value="<?php echo $first_name ?>" name="first_name" type="text" id="text" placeholder="First Name">
                </div>
                <div class="input-group">
                    <input value="<?php echo $last_name ?>" name="last_name" type="text" id="text" placeholder="Last Name">
                </div>
                <div class="input-group">
                    <input value="<?php echo $email ?>" name="email" type="text" id="text" placeholder="Email">
                </div>           
                <div class="input-group">
                    <input name="password" type="password" id="text" placeholder="Password">
                </div>
                <div class="input-group">
                    <input name="re-password" type="password" id="text" placeholder="Re-enter Password">
                </div>
                <div id="button-group" class="input-group">
                    <input type="submit" id="button" value="Create a new account">
                </div>
            </form>
        </div> 
        <div id="Bar_Login">
            <div id="login-group" class="input-group">
                <a href="login.php" id="login">Already have an account? <span id="login-text">Log in</span></a>
            </div>
        </div>
    </body>
</html>