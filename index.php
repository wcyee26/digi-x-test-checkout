<html>
    <!-- <body style="background-color: black; color: white;"> -->
    <body>

<?php

    require_once('checkout.php');

    // Get pricing rules
    $pricingRules = file_get_contents('pricingRules.json');
    $pricingRules = json_decode($pricingRules);

    $all_items = [
        ['atv', 'atv', 'atv', 'vga']
        , ['atv', 'ipd', 'ipd', 'atv', 'ipd', 'ipd', 'ipd']
        , ['mbp', 'vga', 'ipd']
    ];
    
    foreach($all_items as $all_item_key => $all_item){
        $items = $all_item;

        $checkout = new Checkout($pricingRules);
        foreach($items as $item_key => $item){
            $checkout->scan($item);
        }
        $total = $checkout->total();
        $sub_totals = $checkout->sub_totals();

        echo 'SKUs Scanned: ' . implode(', ', $items);
        echo '<br>';
        echo 'Total expected: $' . $total;
        echo '<br>';
        
        echo "<a href='#' onclick='document.getElementById(\"sub-total-" . $all_item_key . "\").style.display = \"block\"; this.style.display = \"none\"'>More</a>";
        echo '<div id="sub-total-' . $all_item_key . '" style="display: none;">';
        echo 'Sub-total expected: ';
        foreach($sub_totals as $sub_total_key => $sub_total){
            echo $sub_total_key . ' | $' . $sub_total . ' ';
        }
        echo '</div>';

        echo '<br>';
        echo '<br>';
    }    

?>

    </body>
</html>