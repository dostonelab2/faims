<pre>
    <?php //print_r($purchase_request_details); ?>
    <?php //
    //$purchase_requests
    /*foreach($purchase_request_details as $details){
        //$key = array_search($details['purchase_request_number'], array_column($purchase_requests, 'purchase_request_number'));
        $key = 0;
        $key = array_search($details['purchase_request_number'], array_column($purchase_requests, 'purchase_request_number'));
        array_push($purchase_requests[$key]['pr_details'], $details);
    }*/
//    $new_PRs = [];
//    foreach($purchase_requests as $pr){
//        $key = array_search($pr['purchase_request_number'], array_column($purchase_request_details, 'purchase_request_number'));
//        array_push($pr['pr_details'], $purchase_request_details[$key]);
//        array_push($new_PRs, $pr);
//        //echo $pr['purchase_request_number'].' - '.$key.'<br/>';
//        //print_r($pr);
//        //echo '<br/>';
//    }

    print_r($new_PRs); 
    ?>
    
    
</pre>
