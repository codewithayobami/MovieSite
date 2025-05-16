<?php 

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}


?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Flix</title>
      
      <!-- jQuery -->
      <script src='cdn/jquery-3.1.1.min.js' type='text/javascript'></script>
    <script src='cdn/jquery-ui.min.js' type='text/javascript'></script>
    <link href='cdn/jquery-ui.min.css' rel='stylesheet' type='text/css'>
    
   <link rel="stylesheet" href="cdn/external.css" type="text/css">
    <!--Bootstrap-->
    <script src="cdn/popper.min.js"></script>
    <link href='cdn/font-awesome.min.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="cdn/bootstrap.min.css">
     <script src='cdn/bootstrap.bundle.min.js' type='text/javascript'></script> 
  <link rel="stylesheet" href="cdn/bootstrap1.min.css"> 
  <script src="cdn/bootstrap.min.js"></script>
<link rel="stylesheet" href="./font-awesome-4.7.0/css/font-awesome.css">

<style>
   @media (min-width: 768px) {
      #done{
        display: none;
      }
    }
    
     .hero {
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 3em;
            margin: 0;
            color: rgb(134, 131, 131);
        }

        .hero p {
            font-size: 1.2em;
            margin-top: 10px;
        }

        .button {
            padding: 15px 30px;
            background-color: #fff;
            border: 2px solid #fff;
            color: #000;
            font-size: 1.1em;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
            text-decoration: none;
        }

        .button:hover {
            background-color: #000;
            color: #fff;
            text-decoration: none;
        }

    body{
      background: black;
      color: white;
    }
    form{
      padding: 20px;
    }
    /* Services Section */
    .services {
            padding: 20 10px;
            text-align: center;
        }

        .services h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #fff;
        }

        .service-cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #000;
            border: 2px solid #fff;
            border-radius: 8px;
            padding: 20px;
            width: 400px;
            box-shadow: none;
            color: #fff;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card h3 {
            margin: 10px 0;
            color: grey;
        }

        .card p {
            font-size: 0.9em;
            color: lightgrey;
        }

</style>
</head>

<body class='bg-secodary'>
<?php echo "<br>" ?>
<?php echo "<br>" ?>
<?php echo "<br>" ?>
<?php echo "<br>" ?>
<nav class="navbar navbar-expand-lg fixed-top" style="background: black; color: white;">
        <!-- THIS IS FOR THE LOGO OF THE COMPANY -->
        <a class="navbar-brand" href="index.php">
          <h1 style="color:  rgb(5, 209, 245); font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;">Movie Flix  </h1>
        </a>
   
        <!-- COLLAPSIBLE ICON FOR MOBILE VIEW -->
        <i class="fa fa-bars" type="button"  data-toggle="collapse" class="navbar-toggler navbar-toggler-icon" data-target="#navbarcollapse"  style=" font-size: 45px;" id="done"></i>
        <!-- THIS IS THE MAIN MENU -->
        <div class="collapse navbar-collapse" id="navbarcollapse">
           <div class="navbar-nav">
               <a href="movie.php?section=all" class="nav-item nav-link" style="color: lightgray;">Home</a>
               <a href="user_dashboard.php" class="nav-item nav-link" style="color: lightgray;">Profile</a>
               <a href="messages.php" class="nav-item nav-link" style="color: lightgray;">Notifications</a>
           </div>
           <div class="navbar-nav ml-auto">
               <a href="login.php" class="nav-item nav-link btn btn-ig btn-info text-light">Logout</a>
           </div>
        </div>
</nav>
<!--This will be in the header-->
<link rel="stylesheet" href="aos/aos.css">