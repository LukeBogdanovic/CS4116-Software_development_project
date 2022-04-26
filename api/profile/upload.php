<?php
session_start();
if(isset($_POST['submit'])){
    $file = $_FILES['photo'];

    $fileName = $_FILES['photo']['name'];
    $fileTmpName = $_FILES['photo']['tmp_name'];
    $fileSize = $_FILES['photo']['size'];
    $fileError = $_FILES['photo']['error'];
    $fileType = $_FILES['photo']['type'];

    $fileExplode = explode('.',$fileName);
    $fileExtension = strtolower(end($fileExplode));
    
    if($fileError === 0){
        if($fileSize<4000000){
            $fileNameNew = uniqid("",true).".".$fileExtension; //create unique id based on the current microseconds 
            $fileDestination = '../../assets/images/'.$fileNameNew;
            move_uploaded_file($fileTmpName,$fileDestination);
            update_photo($fileNameNew);
            header("Location: ../../profile.php?profile={$_SESSION['id']}");
        } else {
            header("Location: ../../profileSetup.php?profile={$_SESSION['id']}");
            //File too large to upload. Apologise, limited space you know!
        }
    } else{
        //unknown error uploading file
        header("Location: ../../profileSetup.php?profile={$_SESSION['id']}");
    }
}

function update_photo($photoID)
{
    $id=$_SESSION['id'];
    require "../../includes/database.php";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $stmt = "SELECT PhotoID FROM photos WHERE UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "i", $id)) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $PhotoID);
                    if(mysqli_stmt_fetch($stmt)){
                        if(unlink('../../assets/images/'.$PhotoID)){
                            echo "it worked";
                        }
                    }
                } 
            }
        }else {
        }

        $stmt = "DELETE FROM photos WHERE UserID = ?";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "i", $id)) {
                if (mysqli_stmt_execute($stmt)) {
                    $result = array('status' => 200, 'message' => "User photo successfully deleted");
                } else {
                    $result = array('status' => 403, 'message' => "User photo unable to be deleted");
                    header("Location: ../../profileSetup.php?profile={$_SESSION['id']}");
                }
            }
            mysqli_stmt_close($stmt);
        }
        $stmt = "INSERT INTO photos (UserID, PhotoID) VALUES (?,?);";
        if ($stmt = mysqli_prepare($con, $stmt)) {
            if (mysqli_stmt_bind_param($stmt, "is", $id, $photoID)) {
                if (mysqli_stmt_execute($stmt)) {
                } else {
                    header("Location: ../../profileSetup.php?profile={$_SESSION['id']}");
                }
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }
    return;
}