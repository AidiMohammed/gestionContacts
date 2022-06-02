<?php 
    require_once('../../lib/library.php');
    $segments = explode('/',$_SERVER['REQUEST_URI']);
    $segment = $segments[count($segments)-1];
?>

<main>
    <div class="container">
        <nav>
            <ul id="nav">
                <li <?php $segment === "account.php" ? setString("class='current'") : false?>><a href="account.php"> <i class="fa-solid fa-house-chimney"></i> My account</a></li>
                <li <?php $segment === "addnewcontact.php" ? setString("class='current'") : false?>><a href="addnewcontact.php"> <i class="fa-solid fa-square-plus"></i> Add new contact</a>
                <li <?php $segment === "importfile.php" ? setString("class='current'") : false?>><a href="importfile.php"> <i class="fa-solid fa-file-arrow-down"></i> Import a file</a></li>
                <li <?php $segment === "exportfile.php" ? setString("class='current'") : false?>><a href="exportfile.php"> <i class="fa-solid fa-file-arrow-up"></i> Export a file</a></li>

                <div id="admin-btn">
                    <li <?php ($segment === "profile.php" || $segment === "seting.php") ? setString("class='current'") : ""?>><a href="#"><?php echo $_SESSION['user_auth']->user_name?> <i class="fa-solid fa-caret-down"></i></a>
                       <ul>
                            <li><a href="profile.php"> <i class="fa-solid fa-user"></i> Profil</a></li>
                            <li><a href="seting.php"> <i class="fa-solid fa-gear"></i> Setings</a></li>
                            <li style="border-top: 1px solid #c7c4c4;"><a href="../logout.php"> <i class="fa-solid fa-power-off"></i> Logout</a></li>
                        </ul>
                    </li>
                </div>
            </ul>
        </nav>