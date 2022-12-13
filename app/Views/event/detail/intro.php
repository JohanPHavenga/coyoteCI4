<!-- <div class="boxed-list margin-bottom-40">
    <div class="boxed-list-headline">
        <h3><i class="icon-material-outline-check-circle"></i> Comrades & Two Oceans Qualifier Race</h3>
    </div>
    <div class="clearfix"></div>    
</div> -->
<?php
if (strlen($edition_data['edition_intro_detail'] > 10)) {
?>
    <div class="single-page-section">
        <?php
        if (strlen($edition_data['edition_intro_detail'] > 10)) {
            echo $edition_data['edition_intro_detail'];
        }
        ?>
    </div>
<?php
}
?>