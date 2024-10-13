<!-- MEDIA SCREEEN PC, DESKTOP VIEW -->

<div class="side-menu-container-ajax-wrapper">
    <br>
    <form action="/admin/users" method="GET" class="search-category-product-stock">
        @csrf
        <input type="text" name="search" id="" placeholder="Search......"><button type="submit"><i class="fa fa-search"></i></button>
    </form><br>
    <div class="dashboard-mini-menu" onclick="showDashboardMenuuu()">
        <a href="/admin/admin-dashboard"><span><i class="fa fa-home"></i></span> <p>Dashboard</p> <em><i class="fa fa-angle-down"></i></em></a> <br>
    </div>
    <div class="hidden-dashboard-menu">
        <a href="/admin/admin-dashboard"><span><i class="fa fa-times-circle"></i></span> <p>Admin Dashboard</p></a><br>
    </div>
    <div class="product-catalog-menu" onclick="showProductMenu()">
        <a href="#"><span><i class="fa fa-list"></i></span> <p>Products</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <div class="hidden-product-menu">
    <a href="/admin/all-products"><span><i class="fa fa-times-circle"></i></span> <p>All Products</p></a><br><br>
    <a href="/admin/exported-products"><span><i class="fa fa-times-circle"></i></span> <p>Exported Products</p></a><br><br>
    <a href="/admin/transfered-products"><span><i class="fa fa-times-circle"></i></span> <p>Tranfered Products</p></a><br><br>
    <a href="/admin/instock-products"><span><i class="fa fa-times-circle"></i></span> <p>InStock Products</p></a><br><br>
    <a href="/admin/less-product"><span><i class="fa fa-times-circle"></i></span> <p>LessStock Products</p></a><br><br>
    <a href="/admin/outstock-product"><span><i class="fa fa-times-circle"></i></span> <p>OutStock Products</p></a><br><br>
    <a href="/admin/loans-product"><span><i class="fa fa-times-circle"></i></span> <p>Product Loans</p></a><br><br>
    </div>
    <div class="profile-cat-menu">
        <a href="/admin/create-orders"><span><i class="fa fa-shopping-cart"></i></span> <p>Product Order</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="store-mgt-menu">
        <a href="/admin/all-stores"><span><i class="fas fa-store-alt"></i></span> <p>All Stores</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <div class="user-all-mgt-menu">
        <a href="/admin/users"><span><i class="fa fa-users"></i></span> <p>All Users</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="report-menu">
        <a href="/admin/reports"><span><i class="fa fa-bar-chart"></i></span> <p>Reports</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="recommend-menu">
        <a href="/admin/recommended"><span><i class="fa fa-paper-plane"></i></span> <p>Recommendations</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="product-comment-menu">
        <a href="/admin/comments"><span><i class="fa fa-comments"></i></span> <p>Product Comments</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="setting-menu">
        <a href="/admin/profile"><span><i class="fa fa-cog"></i></span> <p>Account Settings</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="setting-menu">
        <a href="/admin/logs"><span><i class="fas fa-history"></i></span> <p>System Logs</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
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
    <form action="/admin/admin-dashboard" method="GET" class="search-category-product-stock">
        @csrf
        <input type="text" name="search" id="" placeholder="Search......"><button type="submit"><i class="fa fa-search"></i></button>
    </form><br>
    <div class="dashboard-mini-menu" onclick="showDashboardMobileMenuuu()">
        <a href="/admin/admin-dashboard"><span><i class="fa fa-home"></i></span> <p>Dashboard</p> <em><i class="fa fa-angle-down"></i></em></a> <br>
    </div>
    <div class="hidden-dashboard-menu" id="hidden-dashboard-menu">
        <a href="/admin/admin-dashboard"><span><i class="fa fa-times-circle"></i></span> <p>Admin Dashboard</p></a><br>
    </div>
    
    <div class="product-catalog-menu" onclick="showProductMobileMenu()">
        <a href="#"><span><i class="fa fa-list"></i></span> <p>Products</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <div class="hidden-product-menu" id="hidden-product-menu">
    <a href="/admin/all-products"><span><i class="fa fa-times-circle"></i></span> <p>All Products</p></a><br><br>
    <a href="/admin/exported-products"><span><i class="fa fa-times-circle"></i></span> <p>Exported Products</p></a><br><br>
    <a href="/admin/transfered-products"><span><i class="fa fa-times-circle"></i></span> <p>Tranfered Products</p></a><br><br>
    <a href="/admin/instock-products"><span><i class="fa fa-times-circle"></i></span> <p>InStock Products</p></a><br><br>
    <a href="/admin/less-product"><span><i class="fa fa-times-circle"></i></span> <p>LessStock Products</p></a><br><br>
    <a href="/admin/outstock-product"><span><i class="fa fa-times-circle"></i></span> <p>OutStock Products</p></a><br><br>
    </div>
    <div class="store-mgt-menu">
        <a href="/admin/all-stores"><span><i class="fas fa-store-alt"></i></span> <p>All Stores</p> <em><i class="fa fa-angle-down"></i></em></a><br>
    </div>
    <div class="user-all-mgt-menu">
        <a href="/admin/users"><span><i class="fa fa-users"></i></span> <p>All Users</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="report-menu">
        <a href="/admin/reports"><span><i class="fa fa-bar-chart"></i></span> <p>Reports</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="profile-cat-menu">
        <a href="/admin/profile"><span><i class="fa fa-user"></i></span> <p>User Profile</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="recommend-menu">
        <a href="/admin/recommended"><span><i class="fa fa-paper-plane"></i></span> <p>Recommendations</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="product-comment-menu">
        <a href="/admin/comments"><span><i class="fa fa-comments"></i></span> <p>Product Comments</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
    <div class="setting-menu">
        <a href="/admin/profile"><span><i class="fa fa-cog"></i></span> <p>Account Settings</p> <em><i class="fa fa-angle-right"></i></em></a><br>
    </div>
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

    function showDashboardMobileMenu(){
        document.getElementById("hidden-dashboard-menu").classList.toggle('active');
    }

    function showProductMobileMenu(){
        document.getElementById("hidden-product-menu").classList.toggle('active');
    }
 </script>