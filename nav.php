 <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">ZB-TRADERS</a>
        </div>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <!-- Top Navigation: Left Menu -->
        <!-- Top Navigation: Right Menu -->
        <ul class="nav navbar-right navbar-top-links">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i> <?=$userInfo['name'];?> <b class="caret"></b>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li>
                        <a href="profile.php?UID=<?=$userInfo['id']?>">
                            <i class="fa fa-user fa-fw"></i> User Profile</a>
                    </li>
                   <!--  <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                    </li> -->
                    <li class="divider"></li>
                    <li><a href="logout.php?csrf_token=<?=$csrf_token?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Sidebar -->
       <?php include "sidebar.php"; ?>
    </nav>