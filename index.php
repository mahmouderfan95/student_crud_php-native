<?php 
    include "init.php";
    $do = isset($_GET['do']) ? $_GET['do'] : 'Mange';
    if($do == 'Mange'){
        $stmt = $conect->prepare("SELECT * FROM student");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>
       <div class="container">
        <a href="?do=add" class="btn btn-primary mb-2 mt-2">Add New Student</a>
        <table class="table table-striped">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Age</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($rows as $row){ ?>
                        <tr>
                        <th scope="row"><?php echo $row['ID'] ?></th>
                        <td><?php echo $row['Name'] ?></td>
                        <td><?php echo $row['Age'] ?></td>
                        <td><?php echo $row['Email'] ?></td>
                        <td>
                            <a href="?do=edit&userid=<?php echo $row['ID'] ?>" class="btn btn-primary">Edit</a>
                            <a href="?do=delete&userid=<?php echo $row['ID'] ?>" class="confirm btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php }  
                ?>
            </tbody>
        </table>
        </div>
    <?php }elseif($do == 'add'){ ?>
        <div class="container">
            <h1 class="text-center mt-3">Add New Student</h1>
            <form action="?do=insert" method="POST">
                <div class="form-group">
                    <label for="">Name</label>
                    <input required="required" type="text" name="name" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input required="required" type="email" name="email" class="form-control" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="">Age</label>
                    <input required="required" type="text" name="age" class="form-control" autocomplete="off">
                </div>
                <input type="submit" value="Add" class="btn btn-primary">
            </form>
        </div>
    <?php }elseif($do == 'insert'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = $_POST['name'];
            $email = $_POST['email'];
            $age = $_POST['age'];

            // generate errors in array 
            $form_errors = [];
            if(empty($name)){
                $form_errors[] = 'Name cant be <b>Empty</b>';
            }if(empty($email)){
                $form_errors[] = 'Email cant be <b>Empty</b>';
            }if(empty($age)){
                $form_errors[] = 'Age cant be <b>Empty</b>';
            }

            // loop errors
            foreach($form_errors as $error){
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }

            if(empty($form_errors)){
                $stmt = $conect->prepare("INSERT INTO student(Name,Email,Age)
                                        VALUES(:zname,:zemail,:zage)");
                $stmt->execute(array(
                    'zname' => $name,
                    'zemail' => $email,
                    'zage'   => $age
                ));

                echo '<div class="alert alert-success">Student added sucessfuly</div>';
                header('location:index.php');

            }
        }else{
            echo '<div class="alert alert-danger">Sory You Cant Browse this page directly</div>';
        }
    }elseif($do == 'edit'){
        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
        $stmt = $conect->prepare("SELECT * FROM student WHERE ID = ?");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if($count > 0){ ?>
            <div class="container">
                <h1 class="text-center mt-3">Edit Student</h1>
                <form action="?do=update" method="POST">
                    <input type="hidden" name="userid" value="<?php echo $userid ?>">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input value="<?php echo $row['Name'] ?>" required="required" type="text" name="name" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input value="<?php echo $row['Email'] ?>" required="required" type="email" name="email" class="form-control" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="">Age</label>
                        <input value="<?php echo $row['Age'] ?>" required="required" type="text" name="age" class="form-control" autocomplete="off">
                    </div>
                    <input type="submit" value="Update" class="btn btn-primary">
                </form>
            </div>
        <?php }else{
            echo '<div class="alert alert-danger">ID Not Exist !</div>';
        }
        ?>
   <?php }elseif($do == 'update'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['userid'];
            $name = $_POST['name'];
            $email = $_POST['email'];
            $age = $_POST['age'];

            // generate errors in array 
            $form_errors = [];
            if(empty($name)){
                $form_errors[] = 'Name cant be <b>Empty</b>';
            }if(empty($email)){
                $form_errors[] = 'Email cant be <b>Empty</b>';
            }if(empty($age)){
                $form_errors[] = 'Age cant be <b>Empty</b>';
            }

            // loop errors
            foreach($form_errors as $error){
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
            if(empty($form_errors)){
                $stmt = $conect->prepare("UPDATE student SET Name = ?, Email = ? , Age = ? WHERE ID = ?");
                $stmt->execute(array($name,$email,$age,$id));
                echo '<div class="container">';
                    echo '<div class="alert alert-success">Student updated data success</div>';
                    echo '<a class="btn btn-info" href="index.php">Go To Back</a>';
                echo '</div>';
                // header('refresh:5;url:index.php');
            }
        }else{
            echo '<div class="alert alert-danger">Sory You Cant Browse this page directly</div>';
        }
        }elseif($do=='delete'){
            $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;
            $stmt = $conect->prepare("SELECT * FROM student WHERE ID = ?");
            $stmt->execute(array($userid));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if($count > 0){
                $stmt = $conect->prepare("DELETE FROM student WHERE ID = ?");
                $stmt->execute(array($userid));
                echo '<div class="alert alert-success">Student Deleted sucessfuly</div>';
                header('location:index.php');
            }else{
                echo '<div class="alert alert-danger">ID Not Exist !</div>';
            }
        }
    include $temp . "header.php";
?>


<?php 
    include $temp . "footer.php";
?>