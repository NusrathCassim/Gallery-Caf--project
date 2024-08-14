<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="/main-folder/asset/logo.png">
    <link rel="stylesheet" href="main-page.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Café</title>
    <link rel="stylesheet" href="../general/media.css">
</head>
<body>
    <nav id="dekstop-nav">
        <div class="Logo"> Gallery Café</div>
        <div>
            <ul class="nav-links">
                <li><a href="#about">About</a></li>
                <li><a href="#menu">Menu</a></li>
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
                    li><a href="#menu" onclick="toggleMenu()">Menu</a></li>
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
        <img src="../asset/arrow.png" alt="arrow icon" class="icon arrow" onclick="location.href='#menu'">
    </section>
    <section id="menu">
         <div class="menu-section">
            <p class="Section_Text_p1">Discover Our Chef's Special!</p>
            <h1 class="title">Signature Dish</h1>
            <div class="dish-container">
                <div class="signature-dish-card">
                    <img src="/main-folder/asset/sushii.jpg" alt="Signature Dish" class="dish-image">
                    <div class="dish-info">
                        <h2 class="dish-title">Sushi Symphony Platter</h2>
                        <p class="dish-description">A delightful assortment of Nigiri, Sashimi, and Maki rolls, featuring fresh tuna, salmon, yellowtail, and inventive combinations</p>
                    </div>
                </div>
                <div class="signature-dish-card">
                    <img src="/main-folder/asset/grilled.jpg" alt="Signature Dish" class="dish-image">
                    <div class="dish-info">
                        <h2 class="dish-title">Grilled Chicken Combo</h2>
                        <p class="dish-description">Juicy grilled chicken served with a choice of sides and dipping sauces. Perfectly seasoned and cooked to perfection for a satisfying meal.</p>
                    </div>
                </div>
                <div class="signature-dish-card">
                    <img src="/main-folder/asset/tacos.jpg" alt="Signature Dish" class="dish-image">
                    <div class="dish-info">
                        <h2 class="dish-title">Gluten-Free Tacos</h2>
                        <p class="dish-description">Featuring gluten-free tortillas packed with fresh, flavorful ingredients and your choice of savory protein. Savor each bite of this delicious and worry-free fiesta for your taste buds!</p>
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
                
              
            </div>
            <div class="b">
                <div class="operating-hours">
                    <h3>Operating Hours</h3>
                    <p><strong>Monday - Friday:</strong> 10:00 AM - 10:00 PM</p>
                    <p><strong>Saturday - Sunday:</strong> 9:00 AM - 11:00 PM</p>
                </div>
                <a href="../SignUp/common-page.php" class="signup-link">Sign Up Now</a>
                <div class="contact-details">
                    <h3>Contact Details</h3>
                    <p><strong>Address:</strong> 123 Art Street, Colombo, Sri Lanka</p>
                    <p><strong>Phone:</strong> +94 112 345 678</p>
                    <p><strong>Email:</strong> info@gallerycafe.lk</p>
                </div>
               
            </div>
        </div>
    </section>
    <footer>
        <nav>
            <div class="nav-links-container">
                <ul class="nav-links">
                    <li><a href="#about">About</a></li>
                    <li><a href="#menu">Menu</a></li>
                    <li><a href="#contact">Join</a></li>
                </ul>
            </div>
        </nav>
        <p>Copyright &#169; 2024 Nusrath. All Rights Reserved.</p>
    </footer>    
    <script  src="main-page.js"></script>
</body>
</html>