<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sardardham Hostel Management</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
        }

        header .logo h1 {
            font-size: 24px;
        }

        header .auth-buttons a {
            margin-left: 20px;
            text-decoration: none;
            color: white;
            background-color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        header .auth-buttons a:hover {
            background-color: #555;
        }

        main {
            flex-grow: 1;
            padding: 50px;
            text-align: center;
        }

        main .description h2 {
            font-size: 32px;
            margin-bottom: 10px;
        }

        main .description p {
            font-size: 18px;
            margin-bottom: 50px;
        }

        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
            overflow: hidden; /* Hide overflow */
        }

        /* Hide the images by default */
        .mySlides {
            display: none;
            width: 100%; /* Full width */
        }

        /* Next & previous buttons */
        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            margin-top: -22px; /* Center the buttons vertically */
            padding: 16px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 3px;
            user-select: none;
            background-color: rgba(0,0,0,0.6); /* Add a semi-transparent background */
            z-index: 1; /* Ensure buttons are above images */
        }

        .prev {
            left: 0; /* Position on the left */
        }

        .next {
            right: 0; /* Position on the right */
        }

        .prev:hover, .next:hover {
            background-color: rgba(0,0,0,0.8); /* Darker on hover */
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active, .dot:hover {
            background-color: #717171;
        }

        /* Footer Section */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
        }
        .logo a {
    text-decoration: none;
}
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
        <a href="index.php"><h1>SDHOSTEL </h1></a>
        </div>
        <div class="auth-buttons">
            <a href="login.php" class="btn">Login</a>
            <a href="register.php" class="btn">Sign Up</a>
        </div>
    </header>

    <!-- Main Section -->
    <main>
        <div class="description">
            <h2>Welcome to Sardardham Hostel</h2>
            <p>We offer the best accommodation for students with all essential amenities to make your stay comfortable and enjoyable.</p>
        </div>
        <div class="slideshow-container">

            <!-- Full-width images with number and caption text -->
            <div class="mySlides fade">
                <div class="numbertext">1 / 5</div>
                <img src="photos/sardar2.webp" style="width:100%">
                <div class="text">HOSTEL</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">2 / 5</div>
                <img src="photos/image1.jpeg" style="width:100%">
                <div class="text">HOSTEL</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">3 / 5</div>
                <img src="photos/image3.jpeg" style="width:100%">
                <div class="text">HOSTEL</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">4 / 5</div>
                <img src="photos/image4.jpeg" style="width:100%">
                <div class="text">HOSTEL</div>
            </div>

            <div class="mySlides fade">
                <div class="numbertext">5 / 5</div>
                <img src="photos/image5.jpeg" style="width:100%">
                <div class="text">HOSTEL</div>
            </div>

            <!-- Next and previous buttons -->
            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
            <a class="next" onclick="plusSlides(1)">&#10095;</a>
        </div>
        <br>

        <!-- The dots/circles -->
        <div style="text-align:center">
            <span class="dot" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
            <span class="dot" onclick="currentSlide(4)"></span>
            <span class="dot" onclick="currentSlide(5)"></span>
        </div>
    </main>

    <!-- Footer Section -->
    <footer>
        <p>© 2024 Pateldham Hostel Management. All rights reserved.</p>
    </footer>

    <!-- JavaScript to manage slides -->
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }

            if (n > slides.length) {slideIndex = 1}
            if (n < 1) {slideIndex = slides.length}
            slides[slideIndex - 1].style.display = "block";  
            dots[slideIndex - 1].className += " active";
        }

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        setInterval(() => {
            showSlides(slideIndex += 1);
        }, 2000);
    </script>
</body>
</html>
