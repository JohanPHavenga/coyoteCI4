<!-- Dashboard Sidebar
	================================================== -->
<div class="dashboard-sidebar">
    <div class="dashboard-sidebar-inner" data-simplebar>
        <div class="dashboard-nav-container">

            <!-- Responsive Navigation Trigger -->
            <a href="#" class="dashboard-responsive-nav-trigger">
                <span class="hamburger hamburger--collapse">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </span>
                <span class="trigger-title">Dashboard Navigation</span>
            </a>

            <!-- Navigation -->
            <div class="dashboard-nav">
                <div class="dashboard-nav-inner">

                    <ul data-submenu-title="Start">
                        <?php
                        // DASHBOARD
                        $menu_item = 'dashboard';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>";

                        // FAVOURITES
                        $menu_item = 'favourite';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>";

                        // Results
                        $menu_item = 'results';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>";
                        ?>
                    </ul>

                    <ul data-submenu-title="Organise and Manage">
                        <?php
                        // Subscriptions
                        $menu_item = 'subscriptions';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>";

                        // Subscriptions
                        $menu_item = 'donate';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>";
                        ?>

                    </ul>

                    <ul data-submenu-title="Account">
                        <?php
                        // PROFILE
                        $menu_item = 'profile';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>";

                        // DASHBOARD
                        $menu_item = 'logout';
                        $active = ($method == $menu_item) ? "active" : false;
                        echo "<li class='$active'>";
                        echo "<a href='" . $user_menu[$menu_item]['loc'] . "'><i class='" . $user_menu[$menu_item]['icon'] . "'></i> " . $user_menu[$menu_item]['display'] . "</a>";
                        echo "</li>"
                        ?>
                    </ul>

                </div>
            </div>
            <!-- Navigation / End -->

        </div>
    </div>
</div>
<!-- Dashboard Sidebar / End -->