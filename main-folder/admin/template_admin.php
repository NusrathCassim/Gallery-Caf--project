<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="template-admin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
    <div class="sidebar">
        <div class="logo-Details">
            <a href="/main-folder/admin/admin-home.php">
            <i class='bx bx-cube'></i></a>
            <span class="logo_name"; font-family="DM Serif Display";>Gallery Café</span>
            
        </div>
        
            <ul class="nav-links">
                <li>
               
                    <div class="icon-link">
                        <a href="/main-folder/admin/add-menu/add-menu.php">
                            <i class='bx bx-sitemap'></i>
                            <span class="link_name">
                                Menu
                            </span>
                        </a><i class="bx bx-chevron-down arrow"></i>
                        
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href=""> Manage</a></li>
                        <li><a href="/main-folder/admin/add-menu/add-menu.php">Add Menu</a></li>
                        <li><a href="/main-folder/admin/add-menu/edit-menu.php">Edit Menu</a></li>
                    </ul>
                
                </li>
                <li><a href="">
                    <i class='bx bxs-check-circle'></i>
                    <span class="link_name">
                    Check Reservations
                    </span>
                </a></li>
                <li>
                    <div class="icon-link">
                        <a href="#">
                            <i class='bx bxs-heart-circle'></i>
                                <span class="link_name">
                                Check Pre-Order Meals
                                </span>
                            </a>
                          
                    </div>
                    
                </li>
                <li><a href="#">
                    <div class="icon-link">
                        <a href="/main-folder/admin/add-specialEvents/add_specialEvents.php">
                            <i class='bx bx-cog'></i>
                            <span class="link_name">
                                Special Events
                            </span>
                        </a><i class="bx bx-chevron-down arrow"></i>
                        
                    </div>
                    <ul class="sub-menu">
                        <li><a class="link_name" href=""> Manage</a></li>
                        <li><a href="/main-folder/admin/add-specialEvents/add_specialEvents.php">Add Events</a></li>
                        <li><a href="/main-folder/admin/add-specialEvents/edit_event.php">Edit Events</a></li>
                    </ul>
            
                </li>
                <li><a href="#">
                    <i class='bx bx-grid-alt'></i>
                        <span class="link_name">
                        Customer Reviews
                        </span>
                </a></li>
                <li><a href="/main-folder/SignUp/logout.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">
                        Logout
                    </span>
                </a></li>

        </ul>    
    </div>
</body>
</html>