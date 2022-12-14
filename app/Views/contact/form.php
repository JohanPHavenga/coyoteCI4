<div class="container">
    <div class="row">

        <div class="col-xl-8 col-lg-8 content-right-offset">
            <?php
            $errors = $validation->getErrors();
            if (!empty($errors)) {
                echo '<div class="notification error"><ul>';
                foreach ($errors as $error) {
                    echo '<li>' . esc($error) . '</li>';
                }
                echo '</ul></div>';
            } else {
                echo '<div class="boxed-list margin-bottom-40">
                <div class="boxed-list-headline">
                <h3><i class="icon-material-outline-question-answer"></i> Any questions? Feel free to contact us!</h3></div>
                <div class="clearfix"></div></div>';
            }

            $attributes = array(
                'method' => 'post',
                'class' => 'contact-form',
                'id' => 'contact-form',
                'autocomplete' => 'on',
                'role' => 'form'
            );
            echo form_open(base_url('contact'), $attributes);
            ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="input-with-icon-left">
                        <input class="with-border" name="name" type="text" id="name" placeholder="Your Name" required="required" value="<?= set_value('name'); ?>" />
                        <i class="icon-material-outline-account-circle"></i>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-with-icon-left">
                        <input class="with-border" name="email" type="email" id="email" placeholder="Email Address" value="<?= set_value('email'); ?>" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" required="required" />
                        <i class="icon-material-outline-email"></i>
                    </div>
                </div>
            </div>

            <div class="input-with-icon-left">
                <input class="with-border" name="subject" type="text" id="subject" placeholder="Subject" required="required" value="<?= set_value('subject'); ?>" />
                <i class="icon-material-outline-assignment"></i>
            </div>

            <div>
                <textarea class="with-border" name="comments" cols="40" rows="5" id="comments" placeholder="Message" spellcheck="true" required="required"><?= set_value('comments'); ?></textarea>
            </div>

            <input type="submit" class="submit button margin-top-15" id="submit" value="Submit Message" />

            <?php
            echo reCaptcha3('reCaptcha3', ['id' => 'recaptcha_v3'], ['action' => 'contactForm']);
            echo form_close();
            ?>
            

        </div>

        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
            </div>
        </div>

    </div>
</div>