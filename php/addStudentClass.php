<?php
    session_start();
    if(isset($_SESSION['user']))
    {
        if($_SESSION['isTeacher'] == 0)
            header("location: parentHome.php");
    }
    else
    {
        header("location: ../view/loginPage.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassMinder - Create Student</title>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/teacherHome.js"></script>
    <link rel="stylesheet" href="../css/studentList.css">
    <link rel="stylesheet" href="http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <?php
        require_once('../common/connection.php');
        include_once('../model/User.php');
        include_once('../model/Student.php');
        include_once('../model/Classroom.php');
        // Initialize the session
        session_start();
        // If session variable is not set it will redirect to login page
        if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
            header("location: ../view/loginPage.php");
            exit;
        }
        $nConn = new Connection();
        // Create a $user and store it for session 
        if(!isset($_SESSION['user']) || empty($_SESSION['user']))
        {
            $email = $_SESSION['username'];
            $nQuery = "SELECT userID FROM USER WHERE email='$email'";
            $records = $nConn->getQuery($nQuery);
            $row = $records->fetch_array();
            $user = new User("", "", "", "", "");
            $id = $row["userID"];
            $user->loadByID($id);
            $_SESSION['user'] = $user;
            $_SESSION['userID'] = $_SESSION['user']->getUserID();
        }
        ?>
    <?php

        $user = "";
        $msgs = [];
        $success = false;
        
        if(isset($_POST["studentID"]))
        {
            if(empty($msgs))
            {
                
            }
        }
        
    ?>
</head>
<body>
    <?php
        if($success)
        {   
            header("location:./classroom.php");
            die;
        }  
    ?>
    <div class="leftMenu">
        <ul>
            
            <li><span class="topItem">
                <br>
                <div class="logoMid"><img src="../resources/images/templogoWhiteTransparent-box.png" height="30px"></div>
                <span>ClassMinder</span>
                </span>
            </li>
            <li class="logout"><span class="menuItem">
                <a href="logout.php" class="underlined">
                    <span><i class="ion-log-out"></i></span>
                    <span class="iconText">Logout</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="teacherHome.php" class="underlined">
                    <span><i class="ion-ios-home-outline"></i></span>
                    <span class="iconText">Home</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="studentList.php" class="underlined">
                    <span><i class="ion-ios-people"></i></span>
                    <span class="iconText">Students</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="classList.php" class="underlined">
                    <span><i class="ion-university"></i></span>
                    <span class="iconText">Classes</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="resources.php" class="underlined">
                    <span><i class="ion-ios-bookmarks-outline"></i></span>
                    <span class="iconText">Resources</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="preferences.php" class="underlined">
                    <span><i class="ion-ios-settings"></i></span>
                    <span class="iconText">Preferences</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="settings.php" class="underlined">
                    <span><i class="ion-ios-gear-outline"></i></span>
                    <span class="iconText">Account Settings</span>
                </a>
                </span></li>
            <li><span class="menuItem">
                <a href="help.php" class="underlined">
                    <span><i class="ion-help"></i></span>
                    <span class="iconText">Help</span>
                </a>
                </span></li>
        </ul>
    </div>
    <div class="div1">
        <div class="midContainer">
        <table>
            <?php
                if(!empty($msgs))
                {   
                    foreach($msgs as $msg)
                        echo "<tr><td class='msgs' colspan='2'>*". $msg ."</td></tr>";
                }
            ?>
            <tr>
                <td colspan="2"><h4>Please select student</h4></td>
            </tr>
                <form action="addStudent.php" method="post">
                <tr>
                    <td class="leftAlign">
                        <label for "studentID" class="require"> Student: </label>
                    </td>
                    <td>
                        <select size="20" name="studentClass" value="<?php if (isset($_POST['studentID'])) echo $_POST['studentID']?>" required/>
                            <?php
                                $userID = $_SESSION["userID"];
                                $classroomID = $_POST['classroomID'];
                                $nQuery =
                                "SELECT firstName, lastName, studentID, classroomID FROM
                                (SELECT firstName, lastName, studentID, classroomID FROM
                                (SELECT DISTINCT STUDENT.firstName, STUDENT.lastName, STUDENT.studentID, CLASSROOM.classroomID
                                FROM STUDENT
                                JOIN STUDENT_CLASS ON STUDENT_CLASS.studentID = STUDENT.studentID
                                JOIN CLASSROOM ON CLASSROOM.classroomID = STUDENT_CLASS.classroomID
                                WHERE CLASSROOM.userID = $userID ORDER BY CLASSROOM.classroomID DESC) a
                                GROUP BY studentID) b
                                WHERE classroomID<>$classroomID";
                                echo $nQuery;
                                $records = $nConn->getQuery($nQuery);
                                $title = $_POST['title'];
                                while($row = $records->fetch_array())
                                {
                                    $fName = $row["firstName"];
                                    $lName = $row["lastName"];
                                    $studentID = $row['studentID'];
                                    echo '<option name="studentClass" value=\'{"fName":"' . $fName . '","lName":"' . $lName . '","studentID":"' . $studentID . '","classroomID":"' . $classroomID . '","title":"' . $title . '"}\'>';
                                    echo $row["firstName"] . " " . $row["lastName"]." [ID: ".$row['studentID']."]";
                                    echo "</option>";
                                }
                                ?>
                    </td>
                </tr>
                <tr>
                    <td class="btnCell" colspan="1">
                        <input type="submit" class="submitBtn" value="Add New Student"/>
                    </td>
                </form>
                <form action="classroom.php" method='post'>
                    <td class="btnCell" colspan="1">
                        <?php
                            echo "<input type='hidden' name='title' value='$title'>";
                            echo "<input type='hidden' name='classroomID' value='$classroomID'>";
                        ?>
                        <input type="submit" class="button" id="cancelBtn" value="Cancel"/>
                    </td>
                </tr>
                </form>
            </table>
        </div>
    </div>
</body>
</html>