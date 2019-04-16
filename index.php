<?php
/*
Author: Osman Çakmak
Skype: oxcakmak
Email: oxcakmak@hotmail.com
Website: http://oxcakmak.com/
Country: Turkey [TR]
*/
//If you have post request
//strip_tags: clears html tags
//trim: removes blanks
require_once('config.php');
if(isset($_POST['actionLogin'])){
    $user_nickname = strip_tags(trim($_POST['user_nickname']));
    $user_password = sha1((strip_tags(trim($_POST['user_password'])));
    //if sent values are empty
    if(empty($user_nickname) || empty($user_password)){
        echo "space";
    }else{
        //We are capturing user information
        $loginCheckUserExists = $dbh->prepare("SELECT * FROM user WHERE user_nickname = :user_nickname");
        $loginCheckUserExists->execute(array(
            ":user_nickname" => $user_nickname
        ));
        $loginCheckUserExistsRow = $loginCheckUserExists->fetch(PDO::FETCH_ASSOC);
        //If the user is available
        if($loginCheckUserExists->rowCount() > 0){
            //If the username and password are correct
            if($user_nickname == $loginCheckUserExistsRow['user_nickname'] && $user_password == $loginCheckUserExistsRow['user_password']){
                //User status
                //If the user is inactive
                if($loginCheckUserExistsRow['user_status'] == "0"){
                    echo "banned_or_disabled";
                //If user status is active
                }else if($loginCheckUserExistsRow['user_status'] == "1"){
                    $_SESSION['user_nickname'] = $user_nickname;
                    $_SESSION['user_session'] = true;
                    echo "success";
                //If the user status is pending (Mail Activation...)
                }else if($loginCheckUserExistsRow['user_status'] == "2"){
                    echo "awaiting_mail_activation";
                }
            }else{
                //If the username and password are incorrect
                echo "username_or_password_false";
            }
        }else{
            //If there is no such user
            echo "not_exists";
        }
    }
}
?>
