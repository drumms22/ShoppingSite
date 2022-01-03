
<?php 

    if(!isset($_SESSION['current_user'])){

?>

    <form method="POST" action='index.php' class="col d-flex justify-content-end pt-3"  id="loginForm">
        <p class="text-white mr-5 mt-1">No account? Register <a class="text-danger ml-1" href='register'>here!</a></p>
        <div class="row">
            <div class="col-lg-4">
                <input type="hidden" name="request" value="user/login" class="form-control" disabled>
                <input type="text" name="email" class="form-control" placeholder="Email">
            </div>
            <div class="col-lg-4">
                <input type="password" name="password" class="form-control" placeholder="Password">
            </div>
            <input type="hidden" name="request" value="user/login" class="form-control">
            <div class="col pt-1">
            <input class="btn-dark" type="submit" value="submit">
            </div>
        </div>
    </form>
    
<?php }?>
