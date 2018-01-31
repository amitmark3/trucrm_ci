<ul class="progress-indicator">
    <?php
    $steps = [0 => 'Start', 1 => 'Price Plan', 2 => 'Checkout', 3 => 'Confirmation'];
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