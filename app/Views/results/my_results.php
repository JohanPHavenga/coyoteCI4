<h1>My Results</h1>
<p>We are busy working on this module where you can search and <b>claim a result</b> as your own,
    building up a nice history of your results. </p>
<p>The result set is limited at the moment but will be expanded on over time. <br>
    Note that not all races publish their results in a format that allows us to import it into our site.</p>
<?php
// check if user is logged in
if (!logged_in()) {
?>
    <p>To give it a try, please <a href="<?= base_url('login'); ?>">log in</a> or <a href="<?= base_url('register'); ?>">create a new profile</a>.</p>
    <div class="form-group">
        <a class="btn" href="/login">Login</a>
        <a class="btn btn-light" href="/register">Register</a>
    </div>
<?php
} else {
?>
    <p>Use the search form below to search for your results to add them to your profile. Alternatively
        you can add results manually if the result set is not available.
    </p>
    <div class="row">
        <div class="col-lg-8">
            <h4 class="text-uppercase">Link results</h4>
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
                            'value' => set_value('result_search'),
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
            ?>
        </div>
        <div class="col-lg-4 m-b-20">
            <h4 class="text-uppercase">Auto Search</h4>
            <p>Use your name & surname to auto find suggested results</p>
            <a href="<?= base_url("result/auto"); ?>" class="btn btn-primary">Auto Search</a>
        </div>
    </div>

    <h4 class="text-uppercase">Your claimed results</h4>
<?php
    if ($user_result_list) {
        $table = new \CodeIgniter\View\Table();
        $template = array(
            'table_open' => '<table id="datatable_date" class="table table-striped table-bordered table-hover table-sm result_table">',
        );

        $table->setTemplate($template);
        $table->setHeading('Date', 'Event', 'Distance', 'Time');
        foreach ($user_result_list as $result) {
            $url = base_url("result/view/" . $result['result_id']);
            $row = [
                "<a href='$url'>" . fdateShort($result['edition_date']) . "</a>",
                "<a href='$url'>" . $result['edition_name'] . "</a>",
                "<a href='$url'><span class='badge badge-" . race_color($result['race_distance']) . "'>" . round($result['race_distance'], 0) . "km</span></a>",
                "<a href='$url'>" . $result['result_time'] . "</a>",
            ];
            $table->addRow($row);
        }
        echo $table->generate();
    } else {
        echo "<p>No results linked to your profile</p>";
    }
}
