# bearspaw-farmers-market
 Produciton Company III: non-profit project

## Functions

```php
 getLanding($title_tag,$content_tag,$span="")

//Example
getLanding("h2","p");
getLanding("h2","p",30);
```

```php
 getAbout($title_tag,$content_tag)

//Example
getAbout("h2","p");

```

```php
 getSocials($platform=null)
//Example
getSocials(); //get social media link array
getSocials("facebook"); //get facebook link
 ```

```php
 getContacts($title_tag=null)

 //Example
getContacts(); //get associative array
getContacts("h5"); //get html elements
```

```php
getAddress()

 //Example
getAddress(); //address with address tag

```

```php
getAddressDetails($tag="p")

 //Example
 getAddressDetails(); //address details with p tag
getAddressDetails("small"); //address details with samll tag

```
```php
eventDate($option=null,$tag="time")

//Example
eventDate(); 
//get associative array e.g.
$arr = eventDate();
echo $arr['start']
eventDate("end","h3"); //address details with samll tag

```

```php
getAnnouncement()

//Example
getAnnouncement(); //get associative array
$arr = getAnnouncement();
$arr[0]['title']; //get title of first post
$arr[0]['content'];//get conotent of first post

```

```php
getVendorNumber()

//Example
echo getVendorNumber(); //get number

```


Get gallery
```php
getPhoto($num=6,$size=80)

//Example
getPhoto(2); //get 2 pics, 80px
getPhoto(10,100);  //get 10 pics, 100px

```
Get all photo in array
```php
getPhotoList()

// array structure
Array ( [0] => 14 [1] => assets/photo/lLPNrheblApng [2] => a cat pic )

//Example
$arr =  getPhotoList(); 
foreach ($arr  as $key => $value) {
    # code...
    echo '<figcaption>'.$value[2].'</figcaption>';
}
```
```php
getGoogleMap($width=100,$height=600) // First argument is percentage
//Example
getGoogleMap();
getGoogleMap(50,100);
```

