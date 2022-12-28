<section id="contact" class="margin-bottom-40">
    <?php
    $errors = $validation->getErrors();
    if (!empty($errors)) {
        echo '<div class="notification error"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . esc($error) . '</li>';
        }
        echo '</ul></div>';
    } else {
        echo '<p class="margin-top-15 margin-bottom-35">Use the contact form below to contact the race organisers directly</p>';
    }

    $attributes = array(
        'method' => 'post',
        'class' => 'contact-form',
        'id' => 'contact-form',
        'autocomplete' => 'on',
        'role' => 'form'
    );
    $prepop = ["name" => '', 'email' => ''];
    if (logged_in()) {
        $prepop = [
            "name" => user()->name . " " . user()->surname,
            "email" => user()->email,
        ];
    }
    echo form_open($contact_url, $attributes);
    ?>
    <div class="row">
        <div class="col-md-6">
            <div class="input-with-icon-left">
                <input class="with-border" name="name" type="text" id="name" placeholder="Your Name" required="required" value="<?= set_value('name', $prepop['name']); ?>" />
                <i class="icon-material-outline-account-circle"></i>
            </div>
        </div>

        <div class="col-md-6">
            <div class="input-with-icon-left">
                <input class="with-border" name="email" type="email" id="email" placeholder="Email Address" value="<?= set_value('email', $prepop['email']); ?>" pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$" required="required" />
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
</section>

<section id="contact" class="margin-bottom-60">
    <div class="contact-location-info margin-bottom-50 maring-right-5">
        <div class="contact-address">
            <?php
            if ($edition_data['club_id'] != 8) {
                $heading = $edition_data['club_name'];
            } else {
                $heading = "Contact Info";
            }
            ?>
            <ul>
                <li class="contact-address-headline"><?= $heading; ?></li>
                <li>Email: <a href="mailto:<?= $edition_data['user_email']; ?>?subject=<?= $edition_data['event_name']; ?> query from roadrunning.co.za"><?= $edition_data['user_email']; ?></a></li>
                <?php
                if ($edition_data['user_contact']) {
                ?>
                    <li>Phone: <?= fphone($edition_data['user_contact']); ?>
                    <?php
                }
                // dd($edition_data['club_url_list']);
                if (isset($edition_data['club_url_list'])) {
                    if ($edition_data['club_url_list']) {
                        $key = key($edition_data['club_url_list']);
                        echo "<li><a href='" . $edition_data['club_url_list'][$key]['url_name'] . "' target='_blank' title='Visit organiser website' class='link'>Organiers' Website</a></li>";
                    }
                }
                    ?>
            </ul>
        </div>

        <div class="timingptrovider_img">
            <!-- to add club image? -->
        </div>
    </div>
</section>