<?php
// get all active vendors
function getVendors(){
    // echo 'start loding vendors...';
    //catagory
    $clothes = ['clothes','hat','gloves','clothing','scarf','shirt','pants','sweater'];
    $jewelry = ['jewelry','jewelries','accessories','accessory'];
    $bakery = ['bake','baking','baked goods','bread','pastry','taffy','biscotti','pie','buns'];
    $drinks = ['coffe','tea','beer'];
    $home = ['candle','mat'];
    $food = ['food','gourmet','snacks','ice cream','pizza','popcorn','nann','roti','lunch','mustard','honey','potatoes'];
    $art = ['art'];
    $produce = ['vegetable','fruit','cucumber','cherry','orange','zucchini'];
    $otherArr = [];
    $allKeyword = Array('clothesArr'=>$clothes,'jewelryArr'=>$jewelry,'bakeryArr'=>$bakery,'drinksArr'=>$drinks,'homeArr'=>$home,'foodArr'=>$food,'artArr'=>$art,'produceArr'=>$produce, 'other'=> $otherArr);
    $allVendors=[];
    // query all active vendors
    global $connection;
    $query="SELECT `Name`, `Products`, `Business Description`, `id` FROM `vendor` WHERE `Status` = 'Approved'";
    $sql = mysqli_query($connection, $query);
    while($row = mysqli_fetch_array($sql)){
        $allVendors[]=Array("id"=>$row['id'],"name"=>htmlspecialchars($row['Name']),"description"=>htmlspecialchars($row['Business Description']),"products"=>htmlspecialchars($row['Products']));
        $categorized = false;
        foreach ($allKeyword as $arrName => $arr) {
            $added = false; // flag to check if the vendor was already added to the category array
            // Check if the vendor matches a category keyword
            for($i=0;$i<count($arr);$i++){
                $re = "/$arr[$i]/mi";
                if(preg_match($re, $row['Products'])&& !in_array($row['Name'], $arr)&& !$added){
                    // echo 'compare original: '.$row['Name'].' / to converted: '.addslashes($row['Name']).'<br>';
                    $$arrName[]=Array("id"=>$row['id'],"name"=>htmlspecialchars($row['Name']),"description"=>htmlspecialchars($row['Business Description']),"products"=>htmlspecialchars($row['Products']));
                    $added = true;
                    $categorized = true;
                }         
            }
        }
        if (!$categorized) {
            $otherArr[] = Array("id"=>$row['id'],"name"=>htmlspecialchars($row['Name']),"description"=>htmlspecialchars($row['Business Description']),"products"=>htmlspecialchars($row['Products']));
        }
    }
    $vendors_by_cat= Array('clothes'=>$clothesArr,'jewelry'=>$jewelryArr,'bakery'=>$bakeryArr,'drinks'=>$drinksArr,'home'=>$homeArr,'food'=>$foodArr,'art'=>$artArr,'produce'=>$produceArr,'other'=>$otherArr);
    $output = Array('all'=>$allVendors,'category'=>$vendors_by_cat);
   
    # write in JSON
  
    file_put_contents('src/vendors_data.json',json_encode($output));
   
    return $output;

}
$vendors_data = getVendors();

?>