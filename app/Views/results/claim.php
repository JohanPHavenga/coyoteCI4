<?php
    echo "<h3>".$result_list[0]['edition_name']."</h3>";
    echo "<h4>".$result_list[0]['race_name']."</h4>";
    // dd($result_list);
    echo "<table>";    
    echo "<tr><th>POS</th><th>NAME</th><th>SURNAME</th><th>CLUB</th><th>AGE</th><th>CAT</th><th>TIME</th><th>Yours?</th></tr>";
    foreach ($result_list as $result) {
        $link = base_url('result/claim/result/' . $result['result_id']);
        echo "<tr>";                
        echo "<td>" . $result['result_pos'] . "</td>";
        echo "<td>" . $result['result_name'] . "</td>";
        echo "<td>" . $result['result_surname'] . "</td>";
        echo "<td>" . $result['result_club'] . "</td>";
        echo "<td>" . $result['result_age'] . "</td>";
        echo "<td>" . $result['result_cat'] . "</td>";
        echo "<td>" . $result['result_time'] . "</td>";
        echo "<td><a href='$link'>CLAIM</a></td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<p>Can't find yourself? Above is a summary view of entries matching your name or surname. click below to view full result set.</p>";
    echo "<p><a class='btn btn-outline' href='". base_url('result/claim/list/'.$race_id.'/full') ."'>Show full result list</a>";
    echo "<p><a class='btn btn-outline' href='". base_url('result/search') ."'>Back</a>";