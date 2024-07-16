<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Boxicons CSS -->
     <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
   
    
    <link rel="stylesheet" href="main-page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Café</title>
    <link rel="stylesheet" href="media.css">
</head>
<body>
    <nav id="dekstop-nav">
        <div class="Logo"> Gallery Café</div>
        <div>
            <ul class="nav-links">
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Join</a></li>
            </ul>
        </div>
    </nav>
    <nav id="hamburger-nav">
            <div class="Logo"> Gallery Café</div>
            <div class="hamburger-menu">
                <div class="hamburger-icon" onclick="toggleMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="menu-links">
                    <li><a href="#about" onclick="toggleMenu()">About</a></li>
                    <li><a href="#contact" onclick="toggleMenu()">Join</a></li>
    
                </div>
            </div>
    
    </nav>
   
    <section id="profile">
        <div class="profile-section">
            <p class="main-title">Gallery Café</p>
        </div>
        
      
    </section>
    <section id="about">
        <div class="about-section">
            <p class="Section_Text_p1">Get To Know More</p>
            <h1 class="title">About Us</h1>
            
            <div class="section-container">
            
                <div class="about-details-container">
                    <div class="text-container">
                        <p>
                            "Welcome to The Gallery Café
                            
                            Located in the heart of Colombo,
                             The Gallery Café offers an exquisite
                             culinary experience. Our talented chefs
                              use the finest ingredients to create dishes 
                             that celebrate flavors, culture, and community. 
                             Join us for a truly
                              memorable dining experience."</p>
               
                    </div>
                    
                    <div class="about-containers">
                        <div class="details-container"> <!-- cards stack -->
                            <div class="service-card" data-card="1"> <!-- card 1 -->
                                <div class="overlay"></div>
                                <div class="card-data">
                                    <!-- <i class='bx bxs-food-menu'></i> -->
                                    <p id="item">Diverse Menu</p>
                                </div>
                            
                                
                            </div>
                            <div class="service-card" data-card="2"> <!-- card 2 -->
                                <div class="overlay"></div>
                                <div class="card-data">
                                    <!-- <i class='bx bxs-home-alt-2'></i> -->
                                    <p id="item">Online Reservation </p>
                                </div>
                            </div>
                            <div class="service-card" data-card="3"> <!-- card 3 -->
                                <div class="overlay"></div>
                                <div class="card-data">
                                    <!-- <i class='bx bxs-bowl-hot'></i> -->
                                    <p id="item">Pre-Order Food</p>
                                </div>
                            </div>
                            <div class="service-card" data-card="4"> <!-- card 4 -->
                                <div class="overlay"></div>
                                <div class="card-data">
                                    <!-- <i class='bx bxs-message-rounded-dots'></i> -->
                                    <p id="item"> Customer Service</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
       
        </div>
        <img src="../asset/arrow.png" alt="arrow icon" class="icon arrow" onclick="location.href='#contact'">
    </section>
    

    <section id="contact">
        <div class="contact-section">
            <p class="Section_Text_p1">Join us</p>
            <h1 class="title">Sign Up Today!</h1>
            <div class="signup-link-container">
                <a href="../SignUp/signup.php" class="signup-link">Sign Up Now</a>
            </div>
        </div>
    </section>
    <footer>
        <nav>
            <div class="nav-links-container">
                <ul class="nav-links">
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Join</a></li>
                </ul>
            </div>
        </nav>
        <p>Copyright &#169; 2024 Nusrath. All Rights Reserved.</p>
    </footer>    
    <script  src="main-page.js"></script>
</body>
</html>