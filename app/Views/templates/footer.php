 <p>&nbsp;</p>
 <footer>
     <h4>Footer Menu</h4>
     <nav>
         <ul>
             <?php
                // main menu
                foreach ($menus['static_pages'] as $menu_item) {
                    echo "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a>";
                    if (isset($menu_item['sub-menu'])) {
                        echo "<ul>";
                        foreach ($menu_item['sub-menu'] as $sub_menu_item) {
                            echo "<li><a href='" . $sub_menu_item['loc'] . "'>" . $sub_menu_item['display'] . "</a>";
                        }
                        echo "</ul>";
                    }
                    echo "</li>";
                }
                ?>
         </ul>
     </nav>
     Coyote &copy; <?= date("Y"); ?>
 </footer>
 </body>

 </html>