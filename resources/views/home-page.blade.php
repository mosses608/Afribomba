@extends('home-page-layout')

@section('content')

<center><br>
    <form action="/" method="GET" class="search-bar-container">
    @csrf
    <input type="text" id="searchInput" onkeyup="searchProducts()" placeholder="Search for products...">
        <!-- <a href="/index" style="color:#000;" id="sign-in-link">Sign In</a> -->
    </form>
    </center>

<section class="product-list" id="productList">
        @foreach($products as $product)
        <div class="product">
            <img src="{{asset('storage/' . $product->product_image)}}" alt="Image" loading="lazy">
            <strong><h2 class="product-name">{{$product->product_name}}</h2></strong>
            <p class="available">{{$product->description}}</p>
            <strong><p class="price-tag">Tsh {{number_format($product->product_price)}}/=</p></strong>
        </div>
        @endforeach
    </section>
<br><br>
    <footer class="footer-distributed">
        <script>
            const currentYear = new Date();
            const yearOption = {weekly: 'long', year: 'numeric'};
            const formattedYear = currentYear.toLocaleDateString('en-US', yearOption);
            document.querySelector('.currentYear').textContent = formattedYear;
        </script>

        <center>
        <div class="footer-center">
            <div>
                <!-- <i class="fa fa-map-marker"></i> -->
                <p><a href="https://www.google.com/maps?q=Sikukuu+St+%26+Michikichi+St,+Dar+es+Salaam" style="color:#FFF;"><i class="fas fa-map-marker-alt"></i> Mchikichi na Sikukuu</a></p>
            </div>
            <div>
                <!-- <i class="fa fa-phone"></i> -->
                <p style="color:#FFF;"><i class="fa fa-phone"></i> +255 762 881 188</p>
            </div>
            <div>
                <!-- <i class="fa fa-envelope"></i> -->
                <p><a href="mailto:baracky2000@gmail.com" style="color:#FFF;"><i class="fa fa-envelope"></i> </a></p>
            </div>
            <!-- <div class="footer-iconsn">
                <a href="https://wa.me/+255762881188">Facebook</a>
                <p>Facebook</p>
            </div>
            <div class="footer-iconsn">
                <a href="https://wa.me/+255762881188">Twitter</a>
                <p>Twitter</p>
            </div>
            <div class="footer-iconsn">
                <a href="https://wa.me/+255762881188">Instagram</a>
                <p>Instagram</p>
            </div> -->
            <div class="footer-iconsn">
                <a href="https://wa.me/+255762881188"><i class="fab fa-whatsapp"></i></a>
                <!-- <p>WhatsApp</p> -->
            </div>
        </div>
        <!-- <div class="footer-icons">
            <a href="https://wa.me/+255653881184"><i class="fab fa-facebook"></i></a>
            <a href="https://wa.me/+255653881184"><i class="fab fa-twitter"></i></a>
            <a href="https://wa.me/+255653881184"><i class="fab fa-whatsapp"></i></a>
            <br><br>
        </div> -->
        </center>
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

@stop

