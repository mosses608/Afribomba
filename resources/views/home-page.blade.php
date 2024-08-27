<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AFRIBOMBA WHLESALE SHOP</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap"> <!-- Google Fonts -->
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            background: url('https://siliconcompanies.com/assets/img/Service%20details/Logistics%20.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .header-wrapper {
            background-color: #003366; /* Updated to desired color */
            color: #fff;
            padding: 10px;
            width: 100%;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header-wrapper img{
            float:left;
            width:60px;
            border-radius:30px;
            height:60px;
        }

        .min-header-container{
            float: left;
            margin-left:28%;
        }
        .search-bar-container{
            float:left;
            margin-left:23%;
        }

        header h1 {
            margin: 0;
            font-size: 26px;
        }

        header p {
            margin: 5px 0;
            font-size: 14px;
        }

        .hours {
            margin-top: 10px;
            font-size: 14px;
        }

        .search-bar-container {
            display: flex;
            width:50%;
            justify-content: center; /* Centered the search bar */
            padding: 10px 20px;
            margin-bottom: 20px;
        }

        .search-bar-container a{
            margin-left:2%;
            width:80px;
            font-size:14px;
            text-decoration:none;
            padding:6px;
            background-color:#FFFFFF;
            color:#000;
            border-radius:4px;
        }

        .search-bar-container input[type="text"] {
            width: 80%;
            padding: 8px 40px 8px 10px; /* Add space for the icon */
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }

        .search-bar-container i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #fff; /* Updated for visibility on dark background */
        }

        .product-list {
            display: inline;
            /* grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); */
            gap: 15px;
            padding: 10px;
            width: 100%;
            margin: 0 auto;
        }

        .product {
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            float:left;
            width:18%;
            margin-left:5%;
            margin-top:2%;
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .product img {
            width: 100%;
            height:200px;
            border-bottom: 1px solid #ddd;
        }

        .product h2 {
            font-size: 20px;
            margin: 15px 0 10px 0;
            padding: 0 10px;
        }

        .product p {
            margin: 5px 0;
            font-size: 16px;
            color: #555;
        }

        .product .price {
            color: #0033cc; /* Dark blue */
            font-weight: bold;
            font-size: 18px;
        }

        .product .quantity {
            color: #0066cc; /* Blue */
            font-weight: bold;
            font-size: 16px;
        }

        .product .available {
            color: green;
            font-weight: bold;
            margin-bottom: 15px;
        }

        footer {
            background-color: rgba(51, 51, 51, 0.7); /* Semi-transparent background */
            color: #fff;
            text-align: center;
            padding: 20px;
            position: relative;
            width: 100%;
            box-shadow: 0 -2px 4px rgba(0, 0, 0, 0.1);
            margin-top: auto; /* Pushes footer to the bottom */
        }

        footer .contact-info {
            margin: 10px 0;
            font-size: 14px;
        }

        footer .contact-info i {
            margin-right: 8px;
        }

        footer .contact-info a {
            color: #fff;
            text-decoration: none;
        }

        footer .contact-info a:hover {
            text-decoration: underline;
        }

        .footer-distributed {
            background: #666;
            box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.12);
            box-sizing: border-box;
            width: 100%;
            text-align: left;
            font: bold 16px 'Open Sans', sans-serif;
            padding: 6px;
        }

        .footer-distributed .footer-left,
        .footer-distributed .footer-center,
        .footer-distributed .footer-right {
            display: inline-block;
            vertical-align: top;
        }

        .footer-left img{
            width: 200px;
            height: 150px;
            border-radius:6px;
        }

        .footer-distributed .footer-left {
            width: 40%;
        }

        .footer-distributed h3 {
            color: #ffffff;
            font: normal 36px 'Open Sans', cursive;
            margin: 0;
        }

        .footer-distributed h3 span {
            color: lightseagreen;
        }

        .footer-distributed .footer-links {
            color: #ffffff;
            margin: 20px 0 12px;
            padding: 0;
        }

        .footer-distributed .footer-links a {
            display: inline-block;
            line-height: 1.8;
            font-weight: 400;
            text-decoration: none;
            color: inherit;
        }

        .footer-distributed .footer-company-name {
            color: #222;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
        }

        .footer-distributed .footer-center {
            width: 100%;
        }
        .footer-center div{
            float:left;
            padding:10px;
        }

        .footer-distributed .footer-center i {
            background-color: #33383b;
            color: #ffffff;
            font-size: 25px;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            text-align: center;
            line-height: 42px;
            margin: 10px 15px;
            vertical-align: middle;
        }

        .footer-distributed .footer-center i.fa-envelope {
            font-size: 17px;
            line-height: 38px;
        }

        .footer-distributed .footer-center p {
            display: inline-block;
            color: #ffffff;
            font-weight: 400;
            vertical-align: middle;
            margin: 0;
        }

        .footer-distributed .footer-center p span {
            display: block;
            font-weight: normal;
            font-size: 14px;
            line-height: 2;
        }

        .footer-distributed .footer-center p a {
            color: lightseagreen;
            text-decoration: none;
        }

        .footer-distributed .footer-links a:before {
            content: "|";
            font-weight: 300;
            font-size: 20px;
            left: 0;
            color: #fff;
            display: inline-block;
            padding-right: 5px;
        }

        .footer-distributed .footer-links .link-1:before {
            content: none;
        }

        .footer-distributed .footer-right {
            width: 100%;
        }

        .footer-distributed .footer-company-about {
            line-height: 20px;
            color: #92999f;
            font-size: 13px;
            font-weight: normal;
            margin: 0;
        }

        .footer-distributed .footer-company-about span {
            display: block;
            color: #ffffff;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .footer-icons {
            margin-top: 25px;
            display:none;
        }
        #sign-in-link{
            float:right;
            color:#000;
        }

        .paginate-builder{
            justify-content:center;
            box-shadow: 0 0 4px rgba(0,0,0.5px);
            width:90%;
            background-color:#FFF;
            padding:10px;
        }

        .paginate-builder a{
            text-decoration:none;
        }

        .paginate-builder p{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .paginate-builder span{
           padding:6px;
           font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .paginate-builder a{
            color:#000;
        }

        .paginate-builder svg{
            font-size:20px;
            display:none;
        }

        .paginate-builder{
            border-radius:10px;
        }

        @media (max-width: 1000px) {
            .footer-distributed .footer-left,
            .footer-distributed .footer-center,
            .footer-distributed .footer-right {
                display: block;
                width: 100%;
                margin-bottom: 40px;
                text-align: center;
            }

            .footer-distributed .footer-center i {
                margin-left: 0;
            }
        }

        @media (max-width: 600px) {
            header h1 {
                font-size: 22px;
            }

            .product h2 {
                font-size: 18px;
            }

            .product p {
                font-size: 14px;
            }

            .product .price {
                font-size: 16px;
            }

            .search-bar-container {
                padding: 10px;
                margin-left:0%;
                float:none;
            }

            .search-bar-container input[type="text"] {
                width: 80%;
                margin-left:0%;
                padding: 8px 40px 8px 10px;
            }
        }

        @media (max-width:768px){
            .header-wrapper{
                width:100%;
            }
            .header-wrapper img{
                display:none;
            }
            .min-header-container{
                float:none;
                width:100%;
                margin-left:0%;
            }
            .min-header-container h1{
                font-size: 16px;
            }
            .min-header-container p{
                font-size:12px;
            }
            .hours{
                font-size:12px;
            }
            .search-bar-container{
                float:none;
                width: 100%;
                margin-left:0%;
            }
            .product-list{
                width:100%;
                margin-left:0%;
            }
            .product{
                margin-top:2%;
                width:25%;
            }
            .product h2,p{
                font-size:12px;
                font-weight:400;
            }
            
            .product img{
                height:100px;
            }
            .footer-distributed{
                width:100%;
            }
            .footer-center{
                float:left;
                font-size:14px;
            }
            .footer-iconsn{
                display:none;
            }
            .footer-icons{
                display:block;
            }
            .footer-icons a {
                display: inline-block;
                width: 35px;
                height: 35px;
                border-radius: 2px;
                background-color: #33383b;
                color: #ffffff;
                font-size: 20px;
                text-align: center;
                line-height: 35px;
                margin: 0 5px;
                transition: background 0.3s;
            }
            .footer-icons a:hover {
            background-color: #000000;
            }

        }
    </style>
</head>
<body>
    <header class="header-wrapper">
    <a href="/index"><img src="{{asset('assets/images/background-logo.png')}}" alt=""></a>
        <div class="min-header-container">
            <h1>AFRIBOMBA WHOLESALE SHOP</h1>
            <p>Note: Price may change anytime | Contact: +255 653 881 184</p>
            <div class="hours">Open: 7:00 AM | Close: 6:00 PM</div>
        </div>
        <center>
            <form action="/" method="GET" class="search-bar-container">
            @csrf
            <input type="text" id="searchInput" onkeyup="searchProducts()" placeholder="Search for products...">
             <!-- <a href="/index" style="color:#000;" id="sign-in-link">Sign In</a> -->
        </form>
    </center>
    </header>

   

    <section class="product-list" id="productList">
        @foreach($products as $product)
        <div class="product">
            <img src="{{asset('storage/' . $product->product_image)}}" alt="Image" loading="lazy">
            <h2>{{$product->product_name}}</h2>
            <p class="quantity">Available</p>
            <p class="available">{{$product->description}}</p>
            <p class="price">Tsh {{number_format($product->product_price)}}</p>
        </div>
        @endforeach
    </section>
<br>
   <center>
   <div class="paginate-builder">
        {{$products->links()}}
    </div>
   </center>
<br><br>
    <footer class="footer-distributed">
        <!-- <div class="footer-left">
            <a href="/index"><img src="{{asset('assets/images/background-logo.png')}}" alt="Image" loading="lazy"></a>
            <p class="footer-links">
                <a href="#" class="link-1">Home</a>
                <a href="#">Blog</a>
                <a href="#">Pricing</a>
                <a href="#">About</a>
                <a href="#">Faq</a>
                <a href="#">Contact</a>
            </p>
            <p class="footer-company-name" style="color:#FFFFFF;">AFRIBOMBA WHOLESALE SHOP &copy; <span class="currentYear"></span></p>
        </div> -->

        <script>
            const currentYear = new Date();
            const yearOption = {weekly: 'long', year: 'numeric'};
            const formattedYear = currentYear.toLocaleDateString('en-US', yearOption);
            document.querySelector('.currentYear').textContent = formattedYear;
        </script>

        <center>
        <div class="footer-center">
            <div>
                <i class="fa fa-map-marker"></i>
                <p><a href="https://www.google.com/maps?q=Sikukuu+St+%26+Michikichi+St,+Dar+es+Salaam" style="color:#FFF;">Mchikichi na Sikukuu</a></p>
            </div>
            <div>
                <i class="fa fa-phone"></i>
                <p style="color:#FFF;">+255 653 881 184</p>
            </div>
            <div>
                <i class="fa fa-envelope"></i>
                <p><a href="mailto:baracky2000@gmail.com" style="color:#FFF;">baracky2000@gmail.com</a></p>
            </div>
            <div class="footer-iconsn">
                <a href="https://wa.me/+255653881184"><i class="fab fa-facebook"></i></a>
            </div>
            <div class="footer-iconsn">
                <a href="https://wa.me/+255653881184"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="footer-iconsn">
                <a href="https://wa.me/+255653881184"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="footer-iconsn">
                <a href="https://wa.me/+255653881184"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div class="footer-icons">
            <a href="https://wa.me/+255653881184"><i class="fab fa-facebook"></i></a>
            <a href="https://wa.me/+255653881184"><i class="fab fa-twitter"></i></a>
            <a href="https://wa.me/+255653881184"><i class="fab fa-whatsapp"></i></a>
            <br><br>
        </div>
        </center>

        <div class="footer-right">
            <p class="footer-company-about">
                <span>About the company</span>
                <p style="color:#ccc;">Is an online platform offering a wide range of industrial equipment, tools, and supplies. They cater to various industries with quality products and reliable service.</p>
            </p>
           
        </div>
    </footer>

    <script>
        function searchProducts() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const products = document.querySelectorAll('.product');

            products.forEach(product => {
                const title = product.querySelector('h2').textContent.toLowerCase();
                const isVisible = title.includes(input);
                product.style.display = isVisible ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
