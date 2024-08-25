<!-- MEDIA SCREEEN PC, DESKTOP VIEW -->

<div class="side-menu-container-ajax-wrapper">
    <br>
    <form action="/staff/all-products" method="GET" class="search-category-product-stock">
        @csrf
        <input type="text" name="search" id="search-input" placeholder="Search......"><button type="submit"><i class="fa fa-search"></i></button>
    </form><br>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            document.querySelector('.search-category-product-stock').addEventListener('submit', function(event){
                event.preventDefault();
                const searchValue = document.getElementById("search-input").value;

                if(searchValue === ""){
                    alert('Please, write something on this field!');
                }
            });
        });
    </script>
    <div class="dashboard-mini-menu" onclick="showDashboardMenuuu()">
        <a href="/staff/staff-dashboard"><span><i class="fa fa-home"></i></span> <p>Dahboard</p> <em><i class="fa fa-angle-down"></i></em></a> <br>
    </div>
    <div class="hidden-dashboard-menu">
        <a href="/staff/staff-dashboard"><span><i class="fa fa-times-circle"></i></span> <p>Staff Dashboard</p></a><br>
    </div>
    
    <div class="product-catalog-menu" onclick="showProductMenu()">
        <a href="#"><span><i class="fa fa-list"></i></span> <p>Products</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <div class="hidden-product-menu">
        <a href="/staff/all-products"><span><i class="fa fa-times-circle"></i></span> <p>All Products</p></a><br><br>
        <a href="/staff/exported-products"><span><i class="fa fa-times-circle"></i></span> <p>Exported Products</p></a><br><br>
        <a href="/staff/instock-products"><span><i class="fa fa-times-circle"></i></span> <p>InStock Products</p></a><br><br>
        <a href="/staff/less-product"><span><i class="fa fa-times-circle"></i></span> <p>LessStock Products</p></a><br><br>
        <a href="/staff/outstock-product"><span><i class="fa fa-times-circle"></i></span> <p>OutStock Products</p></a><br><br>
    </div>
    <div class="store-mgt-menu">
        <a href="/staff/all-stores"><span><i class="fas fa-store-alt"></i></span> <p>All Stores</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <!-- <div class="user-all-mgt-menu">
        <a href="/admin/users"><span><i class="fa fa-users"></i></span> <p>All Users</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="report-menu">
        <a href="/admin/reports"><span><i class="fa fa-bar-chart"></i></span> <p>Reports</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div> -->
    <div class="profile-cat-menu">
        <a href="/staff/profile"><span><i class="fa fa-user"></i></span> <p>Staff Profile</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="recommend-menu">
        <a href="/staff/recommended"><span><i class="fa fa-paper-plane"></i></span> <p>Recommendations</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <!-- <div class="product-comment-menu">
        <a href="/admin/comments"><span><i class="fa fa-comments"></i></span> <p>Product Comments</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div> -->
    <!-- <div class="setting-menu">
        <a href="/admin/settings"><span><i class="fa fa-cog"></i></span> <p>Account Settings</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div> -->
    <div class="invalidate-logout">
        <form action="/logout" method="POST" class="logout-invalidate">
            @csrf
            <button type="submit"><span><i class="fa fa-sign-out"></i></span> <p>Logout</p></button>
        </form><br>
    </div>
</div>



<!-- MEDIA SCREEEN MOBILE VIEW -->

<div class="side-menu-container-ajax-wrapper-mobile">
    <br>
    <form action="/staff/admin-dashboard" method="GET" class="search-category-product-stock">
        @csrf
        <input type="text" name="search" id="" placeholder="Search......"><button type="submit"><i class="fa fa-search"></i></button>
    </form><br>
    <div class="dashboard-mini-menu" onclick="showDashboardMenuuu()">
        <a href="/staff/admin-dashboard"><span><i class="fa fa-home"></i></span> <p>Dahboard</p> <em><i class="fa fa-angle-down"></i></em></a> <br>
    </div>
    <div class="hidden-dashboard-menu">
        <a href="/staff/admin-dashboard"><span><i class="fa fa-times-circle"></i></span> <p>Staff Dashboard</p></a><br>
    </div>
   
    <div class="product-catalog-menu" onclick="showProductMenu()">
        <a href="#"><span><i class="fa fa-list"></i></span> <p>Products</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <div class="hidden-product-menu">
        <a href="/staff/all-products"><span><i class="fa fa-times-circle"></i></span> <p>All Products</p></a><br><br>
        <a href="/staff/exported-products"><span><i class="fa fa-times-circle"></i></span> <p>Exported Products</p></a><br><br>
        <a href="/staff/instock-products"><span><i class="fa fa-times-circle"></i></span> <p>InStock Products</p></a><br><br>
        <a href="/staff/less-product"><span><i class="fa fa-times-circle"></i></span> <p>LessStock Products</p></a><br><br>
        <a href="/staff/outstock-product"><span><i class="fa fa-times-circle"></i></span> <p>OutStock Products</p></a><br><br>
    </div>
    <div class="store-mgt-menu">
        <a href="#"><span><i class="fas fa-store-alt"></i></span> <p>All Stores</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <!-- <div class="user-all-mgt-menu">
        <a href="#"><span><i class="fa fa-users"></i></span> <p>All Users</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="report-menu">
        <a href="#"><span><i class="fa fa-bar-chart"></i></span> <p>Reports</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div> -->
    <div class="profile-cat-menu">
        <a href="/staff/profile"><span><i class="fa fa-user"></i></span> <p>Staff Profile</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="recommend-menu">
        <a href="#"><span><i class="fa fa-paper-plane"></i></span> <p>Recommendations</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <!-- <div class="product-comment-menu">
        <a href="#"><span><i class="fa fa-comments"></i></span> <p>Product Comments</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="setting-menu">
        <a href="#"><span><i class="fa fa-cog"></i></span> <p>Account Settings</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div> -->
    <div class="invalidate-logout">
        <form action="/logout" method="POST" class="logout-invalidate">
            @csrf
            <button type="submit"><span><i class="fa fa-sign-out"></i></span> <p>Logout</p></button>
        </form><br>
    </div>
</div>




<!-- SCRIPT THE SIDE MENU -->
 <script>
    function showDashboardMenu(){
        document.querySelector('.hidden-dashboard-menu').classList.toggle('active');
    }

    function showProductMenu(){
        document.querySelector('.hidden-product-menu').classList.toggle('active');
    }

    function showMobileSideMenu(){
        document.querySelector('.side-menu-container-ajax-wrapper-mobile').classList.toggle('activate');
    }
 </script>