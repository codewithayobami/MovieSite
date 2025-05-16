<?php 



if ($user['timeout'] < 0) {
    header('Location: index.php');
}

?>
<style>
    .navbarex{
    position: fixed; 
    bottom: 0; 
    width: 100%; 
    background: black;
    color: white;
    text-align: center;
    padding: 10px 0;
}
.ul{
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: space-around;
    color: lightgrey;
    height: 40px;
    font-size: 30px;
}
i{
    color: grey;
    font-size: 45px;
    height: 30px;
}
</style>

<div class="navbarex">
        <div class="ul">
            <a href="movie.php?section=all" id="tag">
                <i class="fa fa-home">
                </i>
            </a>
            <a href="profile.php" id="tag">
                <i class="fa fa-user">
                </i>
            </a>
            <a href="settings.php" id="tag">
                <i class="fa fa-cogs">
                </i>
            </a>
        </div>       
    </div>

<script src="./bootstrap/js/jquery.min.js"></script>
<script src="./bootstrap/js/bootstrap.bundle.js"></script>