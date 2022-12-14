<div class="sidebar-widget">
    <h3>Tags</h3>
    <div class="task-tags">
        <?php
        foreach ($tag_list as $tag) {
        ?>
             <span><a href="<?= base_url("search/tag/" . $tag['tagtype_name'] . "/" . $tag['tag_name']); ?>"><?= $tag['tag_name']; ?></a></span>
        <?php
        }
        ?>
    </div>
</div>