<?php
include('server/dbc.php');
session_start();
date_default_timezone_set('UTC');
if(isset($_SESSION['uid'])){
header('location:index.php');
}

if(isset($_POST['submit'])){
    $message='';
    $query="select * from login WHERE username=:uname";
    $statement=$dbc->prepare($query);
    $statement->execute([
        ':uname' => $_POST['username']
    ]);
    
    if($statement->rowCount()>0){
        
        $row=$statement->fetch();
        
        if($row['password']==$_POST['password']){
            $_SESSION['uid']=$row['id'];
            $_SESSION['uname']=$row['username'];
            $_SESSION['uimg']=$row['img'];
            $c_time=date("Y-m-d H:i:s");
            $subquery="INSERT INTO login_users (user_id, last_activity) VALUES (:id,:c_time)";
            $stm=$dbc->prepare($subquery);
            $stm->execute([
            ':id' => $row['id'],
            ':c_time' => $c_time
            ]);
            $_SESSION['last_activity']=$dbc->lastInsertId();
            header('location:index.php');
        }
        else{
           $message='<label class="text-danger">Password is incorrect</label>'; 
        }
    }
    else{
        $message='<label class="text-danger">Username is incorrect</label>';
    }
}
?>

<!decotype html>
<html>
<head>
    <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1 user-scalable=no" >
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
 <title>pCHAT: Realtime Chat App</title>
 <link rel="icon" type="image/gif/png" href="https://image.flaticon.com/icons/png/512/16/16808.png">
 <style type="text/css">
    body{
        background-color: #2c3e50;
    }

    .theme-card{
    border-radius: 5px;
    background-color: #f5f5f5;
    margin-bottom: 20px;
    box-shadow: 2px 6px 15px 0 rgba(69,65,78,.1);
    border: 0;
}

.theme-card .theme-card-header{
    border-bottom: 1px solid #ebecec;
    padding: 1rem;
    text-align:center;
    font-weight: 500;
    font-size: 20px;

}


.theme-card .theme-card-body {
    padding: 1rem;
}

.theme-btn{
    background-color:#435f7a;
    color:#fff; 
}

.navbar{
background-color: #232226;
}
</style>

    </head>
    <body>

<nav class="navbar navbar-expand-sm justify-content-center">
<h4 style="color: #fff">pCHAT: Realtime Chat App in PHP</h4>
</nav>
<br />

<div class="container-fluid body-content">
<div class="row justify-content-center">

 <div class="col-md-8">

   
        

            <div class="theme-card">
                <div class="theme-card-header">Login on pChat</div>

                <div class="theme-card-body">
                    <div align="center"> <?php  if(isset($message)){ echo $message; } ?> </div>
                    <form method="POST">
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                            <input type="submit" value="Login" name="submit" class="btn btn-sm theme-btn" />
                            </div>
                        </div>
                    </form>
                    <p style="font-weight:bold;" class="text-success">Login Using Following Accounts for testing this App</p>
                    <p>Username: Habib, Password: test</p>
                    <p>Username: Talha, Password: test</p>
                    <p>Username: Abdullah, Password: test</p>
                </div>
            </div>
        </div>

</div>
</div>
        
        
    </body>
</html>
    