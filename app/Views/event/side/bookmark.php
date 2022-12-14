<div class="sidebar-widget">
    <h3>Bookmark or Share</h3>

    <!-- Bookmark Button -->
    <button class="bookmark-button margin-bottom-25" id="fav_but">
        <span class="bookmark-icon"></span>
        <span class="bookmark-text">Bookmark</span>
        <span class="bookmarked-text">Bookmarked</span>
    </button>

    <!-- Copy URL -->
    <div class="copy-url">
        <input id="copy-url" type="text" value="" class="with-border">
        <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
    </div>

    <!-- Share Buttons -->
    <div class="share-buttons margin-top-25">
        <div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
        <div class="share-buttons-content">
            <span>Interesting? <strong>Share It!</strong></span>
            <ul class="share-buttons-icons">
                <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?=current_url();?>" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
                <li><a href="https://twitter.com/intent/tweet?url=<?=current_url();?>" data-button-color="#1da1f2" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                <li><a href="https://pinterest.com/pin/create/button/?url=<?=current_url();?>" data-button-color="#dd4b39" title="Share on Pinterest" data-tippy-placement="top"><i class="icon-brand-pinterest"></i></a></li>
                <li><a href="https://www.linkedin.com/shareArticle?mini=true&url=<?=current_url();?>" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
            </ul>
        </div>
    </div>
</div>