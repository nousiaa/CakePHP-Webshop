
<!-- our top menu-bar -->

<header class="top-header">
    <div class="top-bar">
    <ul class="li2">
        <li>
            <b>Webshop</b> 
        </li>
        <li>
            <a href="/products" class="link-button">Products</a>
        </li>
        <li>
            <a href="/products/search" class="link-button">Search</a>
        </li>
        <?php if($authUser["role"]): // show add product button to admin only (role = 'A' in database, currently can only be set by hand)?>
        <li>
            <a href="/products/add" class="link-button">Add Product</a>
        </li>
        <?php endif;?>
        <li>
            <a href="/products/basket" class="link-button">Shopping Cart</a>
        </li>
        <?php if(!$authUser):  // hide some buttons when not logged in?>
        <li>
            <a href="/user/login" class="link-button">Login</a>
        </li>
        <li>
            <a href="/user/register" class="link-button">Register</a>
        </li>
<?php else: ?>
        <li>
            <a href="/products/orders" class="link-button">My Orders</a>
        </li>
        <li>
            <a href="/user/profile" class="link-button">My Profile</a>
        </li>
        <li>
            <a href="/user/logout" class="link-button">Logout</a>
        </li>
<?php endif;?>
        
    </ul>
    </div>
</header>