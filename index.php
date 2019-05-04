<?php
/* Bruce Turner, Professor Ostrander, Spring 2019
   IT 328 Full Stack Web Development
   Dating II Assignment
   file: index.php  --> default landing page, defines various routes
   date: Sunday, April 21 2019
*/
// Set up error reporting
// Flag variable for site status:

//define('LIVE', FALSE);
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Use my error handler:
//define('LIVE', FALSE);
//require_once("my_error_handler.php");
//set_error_handler('my_error_handler');
//Start a session
session_start();
require_once('vendor/autoload.php');
$f3 = Base::instance();
$f3->set('DEBUG',3);
//Define a default route
$f3->route('GET /', function(){
    //I dont want any lingering session information
    $_SESSION = array();

    //display landing page Template, which posts to personal information
    $view = new Template();
    echo $view->render('views/home.html');

});
//Define a personal information route
$f3->route('GET|POST /perinfo', function() {

    //Display personal information, which posts to profile
    $view = new Template();
    echo $view->render('views/perinfo.html');
});

//Define a profile route
$f3->route('GET|POST /profile', function() {
    //save the data gathered in personal information
    $_SESSION['fname'] = $_POST['fname'];
    $_SESSION['lname'] = $_POST['lname'];
    $_SESSION['age'] = $_POST['age'];
    $_SESSION['gender'] = $_POST['gender'];
    $_SESSION['phone'] = $_POST['phone'];

    //Display profile, which posts to interests
    $view = new Template();
    echo $view->render('views/profile.html');
});

//Define a interests route
$f3->route('GET|POST /interests', function() {

    //save the data gathered in profile
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['resState'] = $_POST['resState'];
    $_SESSION['biography'] = $_POST['bio'];
    $_SESSION['seekSex'] = $_POST['seekSex'];
    //print_r($_SESSION);

    //Display interests, which posts to summary
    $view = new Template();
    echo $view->render('views/interests.html');
});

//Define a summary route
$f3->route('GET|POST /summary', function() {

    //save the data gathered in interests
    $indoor = $_POST['indoor'];
    $_SESSION['indoor_interests'] = implode(", ", $indoor);

    $outdoor = $_POST['outdoor'];
    $_SESSION['outdoor_interests'] = implode(", ", $outdoor);
    //print_r($_SESSION);

    //Display summary, which concludes Dating II
    $view = new Template();
    echo $view->render('views/summary.html');
});
//Run fat free
$f3->run();