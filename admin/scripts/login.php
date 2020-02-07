<?php 
function login($username, $password, $ip){
    $pdo = Database::getInstance()->getConnection();
    //check existance

    $check_exist_query = 'SELECT COUNT(*) FROM tbl_user WHERE user_name= :username';
    $user_set = $pdo->prepare($check_exist_query);
    $user_set->execute(
        array(
            ':username' => $username,
        
        )
    );

    if($user_set->fetchColumn()>0){
        //User exists
        $get_user_query = 'SELECT * FROM tbl_user WHERE user_name = :username';
        $get_user_query .= ' AND user_pass = :password';
        $user_match = $pdo->prepare($get_user_query);
        $user_match->execute(
            array(
                ':username'=>$username,
                ':password'=>$password
            )
        );

        while($founduser = $user_match->fetch(PDO::FETCH_ASSOC)){
            $id = $founduser['user_id'];

            //TODO: update the user table and set the user_ip column to be $ip
            $update = 'UPDATE tbl_user SET user_ip=:ip WHERE user_id = :id';
            $user_update = $pdo->prepare($update);
            $user_update->execute(
                array(
                    ':ip'=>$ip,
                    ':id'=>$id
                )
            );
        }

        if(isset($id)){
            redirect_to('index.php');
        }else{

            return 'Wrong pass';
        }
    }else{

        return 'User does not exist!';
    }
}