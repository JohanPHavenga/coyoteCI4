 <p>&nbsp;</p>
 <footer>
     <h4>Footer</h4>

     <?php
        // search form
        $attributes = ['class' => 'search', 'id' => 'footer_search', 'method' => 'get'];
        echo form_open(base_url('search'), $attributes);
        $data = [
            'name'  => 's',
            'id'    => 'search_field',
            'value' => set_value('s', '', true),
        ];
        echo form_input($data);
        $data = [
            'id'      => 'footer_search_button',
            'value'   => 'false',
            'type'    => 'submit',
            'content' => 'Search',
        ];
        echo form_button($data);;
        echo form_close();

        // province switch form
        $attributes = ['class' => 'search', 'id' => 'site_version', 'method' => 'post'];
        echo form_open(base_url('province/switch'), $attributes);
        
        $js="onchange='this.form.submit()'";
        echo form_dropdown('province_switch', $province_options, $_SESSION['site_province'], $js);
        echo form_close();
        ?>
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

 <?php
    if (isset($scripts_to_load)) :
        foreach ($scripts_to_load as $row) :
            if (substr($row, 0, 4) == "http") {
                $js_link = $row;
            } else {
                $js_link = base_url($row);
            }
            echo "<script src='$js_link' type='text/javascript'></script>
            ";
        endforeach;
    endif;
    ?>

  <script>
     function onSubmit(token) {
         document.getElementById("contact-form").submit();
     }
 </script>

 </body>

 </html>

 <?php
    d($_SESSION);
