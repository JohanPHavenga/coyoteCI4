<?php
$search_url = base_url("result/search");
$attributes = array('class' => '', 'role' => 'form', 'id' => 'search_results', 'method' => 'get');
echo form_open($search_url, $attributes);
?>
<div class="row">
    <div class="form-group col-lg-10">
        <label>Search races for results</label>
        <div class="input-group mb-3">
            <?php
            echo form_input([
                'name' => 'race',
                'id' => 'race_name',
                'value' => set_value('result_search', $s),
                'class' => 'form-control required',
                'placeholder' => 'Search for a race',
                'autocomplete' => 'off',
                'style' => 'height: 40px;',
                'required' => '',
            ]);
            ?>
            <div class="input-group-append">
                <?php
                $data = array(
                    'type' => 'submit',
                    'content' => 'Search',
                    'class' => 'btn',
                );
                echo form_button($data);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
echo form_close();

echo "Search for <span style='background-color: yellow'>" . $race_search ."</span>";

echo "<h4>Races with results</h4>";
echo "<ul>";
foreach ($race_list_with_results as $race) {
    $link = base_url('result/claim/list/' . $race['race_id']);
    echo "<li>" . $race['edition_name'] . " | " . $race['race_name'] . " | <a href='$link'>View</a></li>";
}
echo "</ul>";

echo "<h4>Races with NO results</h4>";
echo "<ul>";
foreach ($race_list_with_no_results as $race) {
    $link = base_url('result/claim/list/' . $race['race_id']);
    echo "<li>" . $race['edition_name'] . " | " . $race['race_name'] . " | <a href='$link'>View</a></li>";
}
echo "</ul>";
