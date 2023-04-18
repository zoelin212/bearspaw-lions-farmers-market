<?php
include dirname(__FILE__) . '/admin/defines.php';
$connection = mysqli_connect($sever, $user, $pass, $database);
include dirname(__FILE__) . '/src/load-vendors.php';
// Get landing section content 
function getLanding($title_tag,$content_tag,$span=""){
    global $connection;
    $query= "SELECT `title`, `content` FROM `index_content` WHERE `location`=0";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);

    $title = $row['title'];
    $content = $row['content'];
    
    if($span!=""){
        $titleArr = explode($span,$title);
        if(count($titleArr)>1){
            $output = "<$title_tag>$titleArr[0]<span>$span</span>$titleArr[1]</$title_tag><$content_tag>$content</$content_tag>";

        }else{
            $output = "<$title_tag>$title</$title_tag><$content_tag>$content</$content_tag>";
        }
        //print_r($tempArr);
    }else{
        $output = "<$title_tag>$title</$title_tag><$content_tag>$content</$content_tag>";
    }

    echo $output;
}

// get socialmedia
function getSocials($platform=null){
    global $connection;
    $options = ["twitter","facebook","instagram"];
    if($platform!=null){
        $output="no match";
        $qTerm = "";

        for ($i = 0; $i < count($options); $i++) {
            if ($platform === $options[$i]) {
                $qTerm = "sns" . ($i + 1);
                break;
            }
        }

        if ($qTerm !== "") {
        $query = "SELECT * FROM `settings` WHERE `option_name` = '$qTerm'";

        $sql = mysqli_query($connection, $query);
        if ($row = mysqli_fetch_array($sql)) {
            $output = $row['option_value'];
        }
    }
    echo $output;
    }else{
        $arr = [];
        for($i=1;$i<=3;$i++){
            $qTerm=  "sns" . ($i + 1);
            $query = "SELECT * FROM `settings` WHERE `option_name` = '$qTerm'";
            $sql = mysqli_query($connection, $query);
            if($row = mysqli_fetch_array($sql)){
                $arr[] = $row['option_value'];
            }
        }
        return $arr;
    }
}

// get contacts
function getContacts($title_tag=null){
    global $connection;
    $outputArr = array();
    $queryPhone = "SELECT * FROM `settings` WHERE `option_name` = 'phone'";
    $sqlPhone = mysqli_query($connection, $queryPhone);
    if($row = mysqli_fetch_array($sqlPhone)){
        $phone = $row['option_value'];  
        $outputArr['phone']=$phone;
    }
    for($i=1;$i<=3;$i++){
        $qTermTitle = 'contact'.$i.'Title';
        $qTitle = "SELECT * FROM `settings` WHERE `option_name` = '$qTermTitle'";
        
        $sqlTitle = mysqli_query($connection, $qTitle);
        $rowTitle = mysqli_fetch_array($sqlTitle);
        
        $title = $rowTitle['option_value'];
        $qTermEmail = 'contact'.$i.'email';
        $qEmail = "SELECT * FROM `settings` WHERE `option_name` = '$qTermEmail'";
        $sqlEmail = mysqli_query($connection, $qEmail);
        $rowEmail = mysqli_fetch_array($sqlEmail);
        $outputArr[$title] = $rowEmail['option_value'];
        
    }
    if ($title_tag != null) {
        foreach ($outputArr as $key => $value) {
            echo "<$title_tag>$key</$title_tag>";
            if (strpos($value, '@') !== false) {
                echo "<a href='mailto:" . $value . "'>$value</a>";
            } elseif (preg_match("/^\d+$/", $value)) {
                echo "<a href='tel:" . $value . "'>$value</a>";
            } else {
                echo "<a href='" . $value . "'>$value</a>";
            }
        }
    }else{
        return $outputArr;
    }
}

// get address
function getAddress(){
    global $connection;
    $query = "SELECT * FROM `settings` WHERE `option_name` = 'address'";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    echo '<address class="footer-items">'.$row['option_value'].'</address>';
}

// get address details
function getAddressDetails($tag="p"){
    global $connection;
    $query = "SELECT * FROM `settings` WHERE `option_name` = 'addressDesc'";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    echo "<$tag>".$row['option_value']."</$tag>";
}

// get event date
function eventDate($option = null, $tag = "time") {
    global $connection;
    if ($option == null) {
        $arr = [];
        $queryStart = "SELECT * FROM `settings` WHERE `option_name` = 'startdate'";
        $sqlStart = mysqli_query($connection, $queryStart);
        $rowStart = mysqli_fetch_array($sqlStart);
        
        $startDate = new DateTime($rowStart['option_value']);
        $arr['start'] = $startDate->format('F jS');
        
        $queryEnd = "SELECT * FROM `settings` WHERE `option_name` = 'enddate'";
        $sqlEnd = mysqli_query($connection, $queryEnd);
        $rowEnd = mysqli_fetch_array($sqlEnd);
        
        $endDate = new DateTime($rowEnd['option_value']);
        $arr['end'] = $endDate->format('F jS');
        
        $arr['year'] = $startDate->format('Y');
        
        return $arr;
    } else {
        $qTerm = $option . "date";
        $query = "SELECT * FROM `settings` WHERE `option_name` = '$qTerm'";
        $sql = mysqli_query($connection, $query);
        $row = mysqli_fetch_array($sql);

        $date = new DateTime($row['option_value']);
        $year = $date->format('Y');
        $formattedDate = $date->format('F jS');

        echo "<$tag>$year</$tag>";
        echo "<$tag>$formattedDate</$tag>";
    }
}


// get announcement
function getAnnouncement(){
    global $connection;
    $currDate=date("Ymd");
    $query = "SELECT * FROM `announcements` WHERE `status` = '1' AND start_date <= $currDate AND end_date >= $currDate LIMIT 4";
    $sql = mysqli_query($connection, $query);
    $arr=[];
    while($row = mysqli_fetch_array($sql)){
        $tempArr['title'] = $row['title'];
        $tempArr['content']=$row['content'];
        $arr[]=$tempArr;
    }
    
    return $arr;
}


// get vendor number
function getVendorNumber(){
    global $connection;
    $query="SELECT COUNT(*) FROM `vendor`";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    return $row[0];
}

// get photos
function getPhoto($num=6,$size=80){
    global $connection;
    $numQ = "SELECT COUNT(*) FROM `photo`";
    $numSql = mysqli_query($connection, $numQ);
    $numRow = mysqli_fetch_array($numSql);
    $photoLeft = $numRow[0]-$num;
   
    // assets/photo/JJSFp4pwNJjpg
    $query = "SELECT * FROM `photo` ORDER BY id DESC LIMIT $num";
    $sql = mysqli_query($connection, $query);
    $i=1;
    
    $index = 'https://www.zoelindev.com/bearspaw-farmersmarket/';
    
    while($row = mysqli_fetch_array($sql)){
        echo '<div class="photoBox" id="photo'.$row['id'].'">';

        echo '<img src="'.$index.''.$row['filename'] . '" alt="'. $row['caption'] . '" width="'.$size.'" class="thumbnail galleryPhoto" data-count="'.($i-1).'">';
        //echo '<img src="../'. $row['filename'] . '" alt="'. $row['caption'] . '" width="'.$size.'" class="thumbnail galleryPhoto" data-count="'.($i-1).'">';

        if ($i == $num && $photoLeft>0){
            echo '<div id="morePhoto">+'.$photoLeft.' more</div>';
        }
        echo '</div>';
        $i++;
    }
}

function getPhotoList() {
    global $connection;
    $outputArr = array();
    $query = "SELECT * FROM `photo` ORDER BY id DESC";
    $sql = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_array($sql)) {
        $outputArr[] = [$row['id'], $row['filename'], $row['caption']];
    }
    return $outputArr;
}


function getGoogleMap($width=100,$height=600){
    global $connection;
    $query = "SELECT * FROM `settings` WHERE `option_name` = 'address'";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    echo '<div style="width: '.$width.'%"><iframe width="'.$width.'%" height="'.$height.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width='.$width.'%25&amp;height='.$height.'&amp;hl=en&amp;q='.$row['option_value'].'+(Bearspaw%20farmaers%20market)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"><a href="https://www.maps.ie/distance-area-calculator.html">measure area map</a></iframe></div>';
}

function getAbout($title_tag,$content_tag){
    global $connection;
    $query= "SELECT * FROM `index_content` WHERE `location`=4";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    $title = $row['title'];
    $content = $row['content'];
    $output = "<$title_tag>$title</$title_tag><$content_tag>$content</$content_tag>";
    echo $output;
}

function getMapSchedule()
{
    global $connection;
    $query = "SELECT `location_id`,`vendor_id`,`date` FROM `vendor_map` WHERE `date` >= CURRENT_DATE ORDER BY `date` ASC LIMIT 1";
    $sql = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($sql);
    $array_location = explode(",", $row['location_id']);
    $array_vendor = explode(",", $row['vendor_id']);
    $array_vendor_name = [];
    foreach ($array_vendor as $key => $value) {
        # code...
        if ($value != ' ') {
            // echo'id='.$value;
            $query_name = "SELECT `name` FROM `vendor` WHERE `id` = '$value'";
            $sqlname = mysqli_query($connection, $query_name);
            $rowname = mysqli_fetch_array($sqlname);
            array_push($array_vendor_name, $rowname['name']);
        } else {
            array_push($array_vendor_name, null);
            continue;
        }
    }
    // echo '<p>$array_location=';
    // print_r($array_location);
    // echo '</p>';
    // echo '$array_vendor=';
    // print_r($array_vendor);
    // echo '</p>';
    // echo '$array_vendor_name=';
    // print_r($array_vendor_name);
    // echo '</p>';
    $data = [$array_location,$array_vendor,$array_vendor_name];

    // Convert the data to JSON format

    $json = json_encode($data);
    file_put_contents('src/vendors_schedule.json',$json);

    // echo 'json=' . $json;
    // Return the JSON string
    return [$array_location,$array_vendor,$array_vendor_name];
}
getMapSchedule();

?>
