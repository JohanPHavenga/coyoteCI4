<div class="attachments-container">
    <?php
    $n = 0;
    if (!empty($file_list)) {
        foreach ($file_list as $filetype_id => $filetype) {
            if (!is_null($filetype[0]['filetype_buttontext'])) { // remove files that should not be listed
                foreach ($filetype as $file) {
                    $n++;
                    $url = base_url("file/edition/" . $slug . "/" . strtolower($file['filetype_name']) . "/" . $file['file_name']);
                    $name = $file['filetype_buttontext'];
                    $sub_text = str_replace(".", "", strtoupper($file['file_ext']));
                    echo "<a href='$url' class='attachment-box ripple-effect'><span>$name</span><i>$sub_text</i></a>";
                }
            }
        }
    }
    if ($n == 0) {
    ?>
        <div class="notification warning closeable">
            <p>No documents uploaded for this event yet</p>
            <a class="close"></a>
        </div>
    <?php
    }
    ?>
</div>