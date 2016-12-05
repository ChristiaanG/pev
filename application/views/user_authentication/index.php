<?php
echo "<meta charset=\"utf-8\" /> ";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' >";
if(!empty($authUrl)) {
    echo '<h2 style="text-align: center;">Login met uw Facebook account en gebaseerd op wat u heeft geliked bevelen wij games aan u.</h2>';
    echo '<a href="'.$authUrl.'"><img style=" margin: 0 auto;" class="img-responsive" alt="Responsive image" src="http://www.cinesamen.nl/_assets/img/facebook-login.png" alt=""/></a>';
}else{
    

    ?>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="http://xiaumayeysink.nl/PEV/index.php/user_authentication/">Game Recommendations</a>
            </div>
            <p class="navbar-text">Welkom <?php echo '<b>' . $userData['first_name'] . '</b>'?></p>
            <p class="navbar-text"><a class="btn btn-default"  href="http://xiaumayeysink.nl/PEV/index.php/user_authentication/logout">Uitloggen</a></p>
<!--            <div id="navbar" class="collapse navbar-collapse">-->
<!--                <ul class="nav navbar-nav">-->
<!--                    <li class="active"><a href="#">Home</a></li>-->
<!--                    <li><a href="#about">About</a></li>-->
<!--                    <li><a href="#contact">Contact</a></li>-->
<!--                </ul>-->
<!--            </div><!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container-fluid">
        <h1>Facebook informatie (profiel) </h1>
        <?php
        echo '<div class="fb_box">';
        echo '<div class="col-md-4">';
        echo '<p class="image"><img src="'.$userData['picture_url'].'" alt="" width="300" height="300"/></p>';
        echo '</div>';
        echo '<div class="col-md-6">';
        echo '<p><b>Facebook ID : </b>' . $userData['oauth_uid'].'</p>';
        echo '<p><b>Naam : </b>' . $userData['first_name'].' '.$userData['last_name'].'</p>';
//        echo '<p><b>Email : </b>' . $userData['email'].'</p>';
//        echo '<p><b>Geslacht : </b>' . $userData['gender'].'</p>';
//        echo '<p><b>Locatie : </b>' . $userData['locale'].'</p>';
//        echo '<p><b>FB Profiel Link : </b>' . $userData['profile_url'].'</p>';
        echo '</div>';
        echo '<div class="col-md-8"><p><b>Games : </b>';
       $games = explode(",",$userData['games']);
        foreach ($games as $game) {
            echo '<div class="col-md-6">' .$game . '</div>';
        }
        echo '</div>';
        echo '</div>';
        ?>
    </div>
<?php } ?>