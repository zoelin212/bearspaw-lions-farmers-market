
// check window size
window.addEventListener('resize', handleLayoutChange);

// start checking
//handleLayoutChange();

function handleLayoutChange() {
  // get element
  const announcement = document.getElementById('announcement');
  const annH2 = document.getElementById('annH2');
  const anContent = document.getElementById('anContent');
  const girlBox = document.getElementById('girlBox');

  const about = document.getElementById('about');
  const aboutLeft = document.getElementById('aboutLeft');
  const firstParagraph = aboutLeft.querySelector('p');
  const aboutImgBox = about.querySelector('.aboutImg');

  const register = document.getElementById('register');
  const vendorImgBox = register.querySelector('.vendorImgBox');
  const vendorRight = register.querySelector('.vendorRight');
  const h2 = vendorRight.querySelector('h2');
  const vendorIn = vendorRight.querySelector('.vendorIn');

  const deTitles = document.querySelectorAll(".deTilte h4");
  const deInfoParagraphs = document.querySelectorAll(".deInfo p");
  const deInfoContainers = document.querySelectorAll(".deInfo");


  // checking window size for needed
  if (window.innerWidth <= 750) {
    // max-width: 750px
    annH2.parentNode.insertBefore(girlBox, annH2.nextSibling);
    aboutLeft.insertBefore(aboutImgBox, firstParagraph.nextSibling);
    vendorRight.insertBefore(vendorImgBox, h2.nextSibling);
  } else {
    // min-width: 750px
    announcement.appendChild(girlBox);
    about.appendChild(aboutImgBox);
    register.insertBefore(vendorImgBox, vendorRight);
  }

  if (window.innerWidth <= 1000) {
    if (deTitles.length === deInfoParagraphs.length) {
      for (let i = 0; i < deTitles.length; i++) {
        deTitles[i].insertAdjacentElement("afterend", deInfoParagraphs[i]);
      }
    }
  } else {
  }
}

handleLayoutChange();

//announcement btns
const openBtns = document.querySelectorAll('.openBtn');

openBtns.forEach((openBtn) => {
  const closeBtn = openBtn.nextElementSibling;
  const content = openBtn.parentElement.nextElementSibling.querySelector('.content p');

  openBtn.addEventListener('click', () => {
    openBtns.forEach((otherOpenBtn) => {
      const otherCloseBtn = otherOpenBtn.nextElementSibling;
      const otherContent = otherOpenBtn.parentElement.nextElementSibling.querySelector('.content p');


      if (otherOpenBtn === openBtn) {
        openBtn.style.display = 'none';
        closeBtn.style.display = 'block';
        content.style.display = 'block';
      } else {
        otherOpenBtn.style.display = 'block';
        otherCloseBtn.style.display = 'none';
        otherContent.style.display = 'none';
      }
    });


  });

  closeBtn.addEventListener('click', () => {
    openBtn.style.display = 'block';
    closeBtn.style.display = 'none';
    content.style.display = 'none';
  });
});

//vendor List
document.addEventListener("DOMContentLoaded", function () {
  const openList = document.querySelector(".openList");
  const closeList = document.querySelector(".closeList");
  const vendorList = openList.parentElement.nextElementSibling;

  function toggleContentVisibility(visible) {
    vendorList.style.display = visible ? "flex" : "none";
  }

  openList.addEventListener("click", function () {
    toggleContentVisibility(true);
  });

  closeList.addEventListener("click", function () {
    toggleContentVisibility(false);
  });

  //display none in the first
  toggleContentVisibility(false);
  closeList.style.display = 'none';
});

//detailBox
const vendors = document.querySelectorAll('li');
const vendorTops = document.querySelectorAll('.vendorTop');
const placehold = document.getElementById('verdor00');
const body = document.querySelector('body');
const getblur = document.querySelector('.blur');

let selectedVendor = null;

vendors.forEach(vendor => {
  vendor.addEventListener('click', (e) => {
    e.stopPropagation();

    placehold.style.display ='none';
    if (window.innerWidth <= 750) {
      getblur.style.display= "block";
      body.style.overflow = 'hidden';
    }else {
      getblur.style.display= "none";
    }
    //main.classList.add("blur");

    const id = vendor.getAttribute('id').slice(4);
    const detailsId = 'vendor' + id;

    if (selectedVendor) {
      selectedVendor.style.backgroundColor = '';
      selectedVendor.style.color = 'black';
      selectedVendor.style.paddingLeft = '0';
      document.getElementById('vendor' + selectedVendor.id.slice(4)).classList.add('hidden');
    }

    selectedVendor = vendor;
    selectedVendor.style.backgroundColor = '#266AA6';
    selectedVendor.style.color = 'white';
    selectedVendor.style.paddingLeft = '0.25rem';
    document.getElementById(detailsId).classList.remove('hidden');

    positionPopup();
  });
});

vendorTops.forEach(vendorTop => {
  const xmark = vendorTop.querySelector(".fa-square-xmark");
  xmark.addEventListener("click", (e) => {
    e.stopPropagation();

    body.style.overflow = 'unset';
    getblur.style.display= "none";
    // main.classList.remove("blur");
    
    if (window.innerWidth <= 750) {
      placehold.style.display ='none';
    }else {
      placehold.style.display ='block';
    }

    if (selectedVendor) {
      selectedVendor.style.backgroundColor = '';
      selectedVendor.style.color = 'black';
      selectedVendor.style.paddingLeft = '0';
      document.getElementById('vendor' + selectedVendor.id.slice(4)).classList.add('hidden');
      selectedVendor = null;
    }
  });
});

// List: get popup in user view
const detailBox = document.querySelector(".detailBox");

function positionPopup() {
  const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
  const viewportWidth = window.innerWidth || document.documentElement.clientWidth;
  const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
  const detailBoxWidth = detailBox.offsetWidth;
  const detailBoxHeight = detailBox.offsetHeight;

  detailBox.style.top = scrollTop + (viewportHeight - detailBoxHeight) / 2 + "px";
  //detailBox.style.left = (viewportWidth - detailBoxWidth) / 2 + "px";
  detailBox.style.left = 0 + "px";
}

window.addEventListener("resize", positionPopup);




// desktop gallery
const morePic = document.querySelector(".grid .photoBox:last-of-type");
const lightBox = document.getElementById("lightBox");
morePic.addEventListener("click", () => {
  lightBox.classList.remove("hidden");
});

const closeLightBox = document.getElementById("closeLightBox");
closeLightBox.addEventListener("click", () => {
  lightBox.classList.add("hidden");
});



// get each booth's transformOrigin for css
const pins = document.querySelectorAll(".pin");
pins.forEach((pin) => {
    const pinBBox = pin.getBBox();
    const centerX = pinBBox.x + pinBBox.width / 2;
    const centerY = pinBBox.y + pinBBox.height / 2;

    pin.style.transformOrigin = `${centerX}px ${centerY}px`;
});

// removed yellow border
function removeAllColoredBorders() {
  svgGroups.forEach(group => {
    const coloredBorder = group.querySelector('.colored-border');
    if (coloredBorder) {
      coloredBorder.remove();
    }
  });
}

//display verdor info while clicking on map
function displayVendorInfo(vendorName) {
  let result = vendors['all'].filter(obj => obj['name'] == vendorName);

  if (result.length > 0) {
    // display info from properties
    infoName.innerHTML = result[0]['name'];
    infoDesc.innerHTML = result[0]['description'];
  }
}

//map: search by category
const catSelect = document.getElementById("category");
const infoName = document.querySelector("#info #name");
const infoId = document.querySelector("#info #boothId")
const infoPro = document.querySelector("#info #products");
const vendorType = document.querySelector("#info #vendorType");

//map: search by name
const selectByName = document.getElementById('sel1');
const svgGroups = document.querySelectorAll('svg g');

//map: search by keyword
const searchInput = document.getElementById('searchTyping');

//map: closeBtn
const infoX = document.querySelector("#infoX");
const info = document.querySelector("#info");


var requestOptions = {
  method: 'GET',
  redirect: 'follow'
};
const getData = async (file) => {
  let call = await fetch(`src/${file}.json`, requestOptions)

  let result = await call.json();
  return result;
};

const vendors_data = async () => {
  let vendors = await getData('vendors_data');
  let scheduleData = await getData('vendors_schedule');
  let schedule = {
    location: scheduleData[0],
    vendor_id: scheduleData[1],
    vendor_name: scheduleData[2]
  };

  //search by category
  let cate = vendors['category'];
  console.log("schedule=> ", schedule);
  // attach vendor id to booth
  schedule['location'].map((val, index) => {
    //console.log('Location in schedule:', schedule['location']);
      // val -> booth number
      // if booth number exist on map
      if (document.getElementById(val)) {
          // assign vendor name to pins
          document.getElementById(val).dataset.vendorName = schedule['vendor_name'][index];
      }
  })
  // highlight category when select the drop down menu
  catSelect.addEventListener("change", () => {
      pins.forEach(pin => pin.classList.remove("zoomed-in"));
      removeAllColoredBorders();
      // get value of selected category
      console.log("select category: ", catSelect.value);
      // loop over all vendors in this category
      cate[catSelect.value].forEach((shop, index) => {
          // see if this vendor on schedule
          if (schedule['vendor_name'].includes(shop['name'])) {
              console.log(shop['name'], " on schedule!!");
              // Find the pin corresponding to the shop name
              const targetPin = document.querySelector(`[data-vendor-name='${shop['name']}']`);
              // read vendor name from dataset then manipulate css
              targetPin.classList.add("zoomed-in");

              const group = targetPin;
              const bbox = group.getBBox();
              const borderRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
              borderRect.setAttribute('x', bbox.x);
              borderRect.setAttribute('y', bbox.y);
              borderRect.setAttribute('width', bbox.width);
              borderRect.setAttribute('height', bbox.height);
              borderRect.classList.add('colored-border');
              group.insertBefore(borderRect, group.firstChild);

              console.log("Added colored-border to: ", shop['name']);
              console.log("group: ", targetPin);

          } else {
              // console.log(shop['name'], " not on schedule");
          }

      });
  });


  // click on pins
  pins.forEach((pin, index) => {
    pin.addEventListener("click", () => {
        info.style.display ='block';
        // read vendor name from dataset
        console.log({ "booth": pin.id, "vendor": pin.dataset.vendorName });
        // filter the vendor name from all vendor
        let result = vendors['all'].filter(obj => obj['name'] == pin.dataset.vendorName)
        console.log(result);

        // display info from properties
        infoName.innerHTML = result[0]['name'];
        infoPro.innerHTML = result[0]['products'];
        infoId.innerHTML = pin.id;

        // Find the category of the vendor
        let vendorCategory = '';
        for (const category in cate) {
            if (cate[category].some(shop => shop['name'] === result[0]['name'])) {
                vendorCategory = category;
                break;
            }
        }
        const vendorCategoryUpper = vendorCategory.charAt(0).toUpperCase() + vendorCategory.slice(1);
        vendorType.innerHTML = vendorCategoryUpper;
        
    });
  });


  //search by name
  selectByName.addEventListener('change', () => {
    const selectedValue = selectByName.value.replace('byName', '');
  
    svgGroups.forEach(group => {
      group.classList.remove('zoomed-in');
      const coloredBorder = group.querySelector('.colored-border');
      if (coloredBorder) {
        coloredBorder.remove();
      }
  
      if (group.id === selectedValue) {
        group.classList.add('zoomed-in');
        const bbox = group.getBBox();
        const borderRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        borderRect.setAttribute('x', bbox.x);
        borderRect.setAttribute('y', bbox.y);
        borderRect.setAttribute('width', bbox.width);
        borderRect.setAttribute('height', bbox.height);
        borderRect.classList.add('colored-border');
        group.insertBefore(borderRect, group.firstChild);
      }
    });
  });

  //search by keyword
  searchInput.addEventListener('input', () => {
    const searchTerm = searchInput.value.toLowerCase().trim();

    // Remove existing zoom and colored borders
    pins.forEach(pin => pin.classList.remove('zoomed-in'));
    removeAllColoredBorders();

    if (searchTerm === '') return;

    const matchedVendors = vendors['all'].filter(vendor =>
      vendor['name'].toLowerCase().includes(searchTerm) ||
      vendor['description'].toLowerCase().includes(searchTerm) ||
      vendor['products'].toLowerCase().includes(searchTerm)
    );

    matchedVendors.forEach(vendor => {
      const targetPin = document.querySelector(`[data-vendor-name='${vendor['name']}']`);

      if (targetPin) {
        targetPin.classList.add('zoomed-in');

        const group = targetPin;
        const bbox = group.getBBox();
        const borderRect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
        borderRect.setAttribute('x', bbox.x);
        borderRect.setAttribute('y', bbox.y);
        borderRect.setAttribute('width', bbox.width);
        borderRect.setAttribute('height', bbox.height);
        borderRect.classList.add('colored-border');
        group.insertBefore(borderRect, group.firstChild);
      }
    });
  });


  // clostBtn
  infoX.addEventListener('click', () => {
    info.style.display ='none';
  });

}
vendors_data();