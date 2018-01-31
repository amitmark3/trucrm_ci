<ul class="progress-indicator">
    <?php
    $steps = [5 => 'Import', 6 => 'Finish'];
    foreach ($steps as $step => $name)
    {
        echo "<li";
        if ($step < $current_step)
        {
            echo " class='completed'><span class='bubble'></span><i class='fa fa-check-circle'></i> ".$name."</li>";
        }
        elseif ($step == $current_step)
        {
            echo " class='active'><span class='bubble'></span><i class='fa fa-chevron-circle-down'></i> ".$name."</li>";
        }
        else
        {
            echo "><span class='bubble'></span> ".$name."</li>";
        }
    }
    ?>
</ul>