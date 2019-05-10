<?php
/* Bruce Turner, Professor Ostrander, Spring 2019
 * IT 328 Full Stack Web Development
 * Dating IIb Assignment: with form validation & sticky forms
 * file: index.php  --> default landing page, defines various routes
 * date: Thursday, May 9 2019
*/
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Start a session
session_start();//Require autoloads
require_once('vendor/autoload.php');
require_once('model/validation-functions.php');

$f3 = Base::instance();
$f3->set('DEBUG',3);
$f3->set('genders', array('Male', 'Female'));
$f3->set('seekSexs', array('Male', 'Female'));
/* Establish our data structures */
$f3->set('states', array('Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
    'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho',
    'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine',
    'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri',
    'Montana', 'Nebraska', 'Nevada', 'New Hampshire','New Jersey', 'New Mexico',
    'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon',
    'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas',
    'Utah', 'Vermont', 'Virgina', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming' ));

$f3->set('states_ABBR', array('AL', 'AK', 'AZ', 'AR', 'CA', 'CO', 'CT', 'DE', 'FL', 'GA',
    'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MD', 'MA', 'MI', 'MN', 'MS',
    'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA',
    'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY' ));

$f3->set('outInt', array('hiking', 'walking', 'biking', 'climbing', 'swimming', 'collecting'));
$f3->set('inInt', array('tv', 'puzzles', 'movies', 'reading', 'cooking', 'playing cards', 'board games', 'video games'));

//Define a default route
$f3->route('GET /', function(){
    //dont want any lingering session information
    $_SESSION = array();

    //display landing page Template, which posts to personal information
    $view = new Template();
    echo $view->render('views/home.html');

});
//Define a personal information route
$f3->route('GET|POST /perinfo', function($f3) {
    //Display personal information, upon completion posts to profile
    //If form has been submitted, validate
    if(!empty($_POST)) {
        //Get data from form
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $age= $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];

        //Add data to hive
        $f3->set('fname', $fname);
        $f3->set('lname', $lname);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);

        if (validPerinfoForm()) {

            //Write data to Session
            $_SESSION['fname'] = $_POST['fname'];
            $_SESSION['lname'] = $_POST['lname'];
            $_SESSION['age'] = $_POST['age'];
            $_SESSION['gender'] = $_POST['gender'];
            $_SESSION['phone'] = $_POST['phone'];
            $view = new Template();
            echo $view->render('views/profile.html');
        }
    }
    $view = new Template();
    echo $view->render('views/perinfo.html');
});

//Define a profile route
$f3->route('GET|POST /profile', function($f3) {
    //Display profile information, upon completion posts to interests
    //If form has been submitted, validate
    if(!empty($_POST)) {
        //Get data from form
        $email = $_POST['email'];
        $resState = $_POST['resState'];
        $seekSex= $_POST['seekSex'];

        //Add data to hive
        $f3->set('email', $email);
        $f3->set('resState', $resState);
        $f3->set('seekSex', $seekSex);
        //echo "<br/>";
        //print_r($_POST);
        if (validProfileForm()) {

            //Write data to Session
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['resState'] = $_POST['resState'];
            $_SESSION['seekSex'] = $_POST['seekSex'];

            $view = new Template();
            //echo "<br/>";
            //print_r($_SESSION);

            echo $view->render('views/interests.html');
        }
    }
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