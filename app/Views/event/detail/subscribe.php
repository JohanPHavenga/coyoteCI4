<div class="row margin-bottom-40">
    <div class="col-lg-12">
        <p>Would you like to receive a notification email when information is loaded for the race, or when online <b>entries open</b>?
            How about when <b>results are loaded</b>?</p>

        <?php
        if (logged_in()) {
        ?>
            <p>
                Then you are in luck. Insert you email address below to be added mailing list for this race.</p>
            <?php
            $attributes = array('class' => 'form-inline', 'role' => 'form');
            echo form_open($subscribe_url, $attributes);
            ?>
            <div class="input-with-icon-left">
                <input value="<?= user()->email; ?>" class="with-border" name="email_sub" type="email" id="email_sub" placeholder="Email Address" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" required="required" />
                <i class="icon-material-outline-email"></i>
            </div>
        <?php
            $field_data = [
                'name'  => 'edition_id_sub',
                'id'    => 'edition_id_sub',
                'value' => $edition_data['edition_id'],
                'type' => 'hidden'
            ];
            echo form_input($field_data);
            $button_data = [
                'id'      => 'subscribe',
                'type'    => 'submit',
                'content' => 'Add to mailing list',
                'class' => "button"
            ];
            echo form_button($button_data);;
            echo form_close();
        } else {
            echo "<p>Please log in to use this feature.</p>";
            echo "<a href='" . base_url('login') . "' class='button ripple-effect'>Login <i class='icon-feather-log-in'></i></a>";
        }
        ?>

    </div>
</div>