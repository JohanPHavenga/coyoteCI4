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

        $js = "onchange='this.form.submit()'";
        echo form_dropdown('province_switch', $province_options, $_SESSION['site_province'], $js);
        echo form_close();
        ?>
     <nav>
         <ul>
             <?php
                // main menu
                unset($menus['static_pages']['login']);
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
                if (logged_in()) {
                    echo "<li><a href='" . base_url('user/dashboard') . "'>" . user()->name . "</a></li><ul>";
                    foreach ($menus['user_menu'] as $menu_item) {
                        echo "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "<li><a href='" . base_url('login') . "'>Login</a></li>";
                }
                ?>
         </ul>
     </nav>
     Coyote &copy; <?= date("Y"); ?>
 </footer>

 <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

 <?php
    if (isset($scripts_to_load)) :
        foreach ($scripts_to_load as $row) :
            if (substr($row, 0, 4) == "http") {
                $js_link = $row;
            } else {
                $js_link = base_url('assets/js/' . $row);
            }
            echo "<script src='$js_link' type='text/javascript'></script>
            ";
        endforeach;
    endif;

    if (logged_in() && isset($edition)) {
    ?>
     <script type="text/javascript">
         // Ajax post
         $(document).ready(function() {
             $("#fav_but_add").click(function() {
                 $('#fav_but_add').removeClass('btn-light');
                 $('#fav_but_add').addClass('btn-primary');
                 jQuery.ajax({
                     type: "POST",
                     url: "<?php echo base_url('favourite/add_fav'); ?>",
                     dataType: 'html',
                     data: {
                         user_id: <?= user()->id; ?>,
                         edition_id: <?= $edition['edition_id']; ?>
                     },
                    //  success: function(res) {
                    //      if (res == 1) {
                    //          alert('Data saved successfully');
                    //      } else {
                    //          alert('Data not saved');
                    //      }
                    //  },
                    //  error: function() {
                    //      alert('data not saved');
                    //  }
                 });
             });

             $("#fav_but_remove").click(function() {
                 $('#fav_but_remove').removeClass('btn-primary');
                 $('#fav_but_remove').addClass('btn-light');
                 jQuery.ajax({
                     type: "POST",
                     url: "<?php echo base_url('favourite/remove_fav'); ?>",
                     dataType: 'html',
                     data: {
                         user_id: <?= user()->id; ?>,
                         edition_id: <?= $edition['edition_id']; ?>
                     },
                    //  success: function(res) {
                    //      if (res == 1) {
                    //          // alert('Data saved successfully');
                    //      } else {
                    //          // alert('Data not saved');
                    //      }
                    //  },
                    //  error: function() {
                    //      // alert('data not saved');
                    //  }
                 });
             });
         });
     </script>
 <?php
    }
    ?>

 </body>

 </html>

 <?php
    d($_SESSION);
