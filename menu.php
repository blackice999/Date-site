<div class="header">
    <div class="title">This is the menu page</div>
    <div class="main_menu_container">
        <div class="menu_item"><a href="login.php">Login</a></div>
        <div class="menu_item"><a href="register.php">Register</a></div>
        <div class="menu_item">Item3</div>
        <div class="menu_item">Item4</div>
        <?php if(isset($_SESSION['token'])):
            foreach ($_SESSION['token'] as $tokenKey => $email):
        ?>
                <div class="menu_item right">Logged in as: <?php echo $email; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
