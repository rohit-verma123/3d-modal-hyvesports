class TConfig{
  constructor() {
  this.productUrl = "";
  this.tshirtStyle = [];
  this.TCtexts = [];
  this.TCimages = [];
  }
}

class TCtext{
  constructor() {
  this.text = "";
  this.font = "";
  this.align = "";
  this.size = 0;
  this.color = "";
  this.top = 0;
  this.left = 0;
  this.bold = "";
  this.italics = "";
  this.underlined = false;
  this.id = "";
  this.rot = 0;
  }
}

class TCImage{
  constructor() {
  this.imageUrl = "";
  this.size = 0;
  this.top = 0;
  this.left = 0;
  this.height = 0;
  this.width = 0;
  this.rot = 0;
  }
}
//// //comment this after doing color functionality
// var canvasimg = new fabric.Canvas("canvasimg");
// canvasimg.backgroundColor = null;
// fabric.util.loadImage("http://localhost/svgd/s.svg", function (img) {  



//       var img = new fabric.Image(img, {
//           left: 0,
//           top: 0,          
//       });

//       img.scaleToWidth(canvasimg.width);
//       img.scaleToHeight(canvasimg.height);
//       img.selectable = false
//       canvasimg.add(img);
//       canvasimg.requestRenderAll();



// });
//////

let camera, scene, renderer, tshirtModel, controls, minCameraRadius,materials, container, Ftexture,Fmaterial, moved = false, tshirtStyle = [],Ctexture,Cmaterial;
let normalMap, metalnessMap, roughnessMap, recenteredCameraPosition,isRotate = false, tshirtModelChildren = [];
let mobile = isMobile();
let isElementSelected = false, selectedElement, isScaling = false,doubleFinger = false;
let camPosX = [], camPosY = [], camPosZ = [];
let deviceP;
if(!mobile){

  fabric.Canvas.prototype.customiseControls({
    tl: {
        action: 'rotate',
        cursor: 'pointer',
    },
    br: {
      action: 'scale',
      cursor: 'pointer',
    },
  });
}

let oldVal, oldMin = 0,oldMax = 2000, newMin = 0, newMax = 2.25;
let configObject = new TConfig();
configObject.productUrl = document.getElementById('productUrl').value;
// configObject.tshirtStyle = [ 'short_sleeves','collar', 'round_neck'];
configObject.tshirtStyle = document.getElementById('hiddenassets').value.split(",");
const TCtext1 = new TCtext();
// //TCtext1.text = "Hello Hyve";
// TCtext1.font = "Arial";
// TCtext1.size = 15;
// TCtext1.align = "center";
// TCtext1.color = "#1900ff";
// TCtext1.bold = 'bold';
// TCtext1.italics = 'italic';
// TCtext1.underlined = true;
// TCtext1.top = 364.5;
// TCtext1.left = 167.5;

const TCImage1 = new TCImage();
// // TCImage1.imageUrl = "http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/sprite.png";
TCImage1.size = 0.5;
TCImage1.top = 364.5;
TCImage1.left = 167.5;

// configObject.TCimages = [TCImage1];
// configObject.TCtexts = [TCtext1];

  if(document.getElementById("threedata").value == 1)

{

// const TCImage1 = new TCImage();
// const TCtext1 = new TCtext();
 threedata();
 // console.log(configObject1);
 // console.log(configObject);
  // configObject = {"productUrl":"","tshirtStyle":["v_neck6666","long_sleeves"],"TCtexts":[{"text":"ddddd","font":"Arial","align":"","size":"16","color":"#585c9c","top":"1322.5","left":"620.5","bold":"bold","italics":"","underlined":"false"}],"TCimages":[{"imageUrl":"http:\/\/localhost\/hyve\/wp-content\/plugins\/3d-config\/assets\/cliparts\/usa-logo.jpg","size":"46.875","top":"1216","left":"514"}]};
 configObject = Object.assign(configObject1);
 // console.log(configObject)
 
 configObject.productUrl = document.getElementById('productUrl').value;
 // console.log(configObject);



}

var canvas = new fabric.Canvas("canvas");
canvas.backgroundColor = null;      
canvas.selection = false;


var onClickPosition = new THREE.Vector2();
var raycaster = new THREE.Raycaster();
var mouse = new THREE.Vector2();

// if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
//  var containerHeight = $(window).width();
// var containerWidth = $(window).width();
// }
// else
// {
//   var containerHeight = "670";
// var containerWidth = "740";
var containerHeight = document.getElementById("3d-product-view").offsetWidth -70;
var containerWidth = document.getElementById("3d-product-view").offsetWidth;
// }

var loader = new THREE.TextureLoader(); 
           
init();
render();

function init(){

  container = document.getElementById("3d-product-view");

  camera = new THREE.PerspectiveCamera( 45, container.innerWidth / container.innerHeight, 0.25, 20 );
  camera.position.set( - 1.8, 0.6, 2.7 );

  scene = new THREE.Scene();
  
  let AA;
  if(mobile){
      AA = true;
      deviceP = window.devicePixelRatio * 0.4;
  }
  else{
      AA = true;
      deviceP = window.devicePixelRatio;
  }
  renderer = new THREE.WebGLRenderer( { antialias: AA, powerPreference: "high-performance" } );
  renderer.setPixelRatio( deviceP );
  renderer.setSize(containerWidth, containerHeight);
  renderer.toneMapping = THREE.LinearToneMapping;
  renderer.toneMappingExposure = 1;
  renderer.shadowMap.type = THREE.PCFSoftShadowMap;
  //renderer.outputEncoding = THREE.sRGBEncoding;
  container.appendChild(renderer.domElement);

  
  //const light = new THREE.DirectionalLight( 0xffffff, 0.75, 100 );
  const light = new THREE.DirectionalLight( 0xffffff, 0.45 );
light.position.set( 0, 1, 0 ); //default; light shining from top
light.castShadow = true; // default false
scene.add( light );
//Set up shadow properties for the light
light.shadow.mapSize.width = 512; // default
light.shadow.mapSize.height = 512; // default
light.shadow.camera.near = 0.5; // default
light.shadow.camera.far = 500; // default
var light2 = new THREE.HemisphereLight(0xFFFFFF, 0xFFFFFF, 0.65);
scene.add(light2);
  ///creating material from the canvas fabric
  Ftexture = new THREE.Texture(document.getElementById("canvas"));
  Ftexture.anisotropy = renderer.capabilities.getMaxAnisotropy();
  Ftexture.flipY = false;
  Fmaterial = new THREE.MeshBasicMaterial({ map: Ftexture,
    transparent:true,
    side:2
  });

  Ctexture = new THREE.Texture(document.getElementById("canvasimg"));  //converting color image canavas to texture
  Ctexture.flipY = false;
  Cmaterial = new THREE.MeshStandardMaterial({ map: Ctexture,
    transparent:true,
    side:2
  });
  

  controls = new THREE.OrbitControls( camera, renderer.domElement );
//   controls.addEventListener( 'change', function(){
//       moved = true;
//   } ); // use if there is no animation loop
  controls.addEventListener( 'change', render ); // use if there is no animation loop
    controls.enablePan = false;
  controls.minDistance = 2;
  controls.maxDistance = 10;
  controls.target.set( 0, 0, - 0.2 );
  controls.update();

        new THREE.RGBELoader()
      .load( document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/textures/bright_roof_multi.hdr', function ( texture,textureData ) {
          texture.mapping = THREE.EquirectangularReflectionMapping;
          texture.encoding = THREE.LinearEncoding;
		  texture.minFilter = THREE.LinearFilter;
		  texture.magFilter = THREE.LinearFilter;
		  texture.generateMipmaps = false;
		  texture.flipY = true;
          scene.background = new THREE.Color( 0xffffff);
         // scene.environment = texture;
          render();
          loadModel();
          
      } );
      

  window.addEventListener( 'resize', onWindowResize );

  // window.addEventListener("keyup", function (event) {   //add text when add text button is clicked.(here using enter as trigger)
  // if (event.keyCode === 13) {
  //     event.preventDefault();
  //     container.addEventListener("pointerdown", function(e){
  //       getCanvasUVPointAndPlaceTheText(container,e);
  //     }, {once : true});
  // }
  // });

function getTransformUvfromCameraCentre(sceneContainer, evt){
  var array = getMousePosition(container, evt.clientX, evt.clientY);
  let centerPoint =  new THREE.Vector2(0.5,0.5);
  var intersects = getIntersects(centerPoint, scene.children);
  if (intersects.length > 0 && intersects[0].uv) {
      var uv = intersects[0].uv;
      intersects[0].object.material[0].map.transformUv(uv);
      return {
      x: getRealPosition('x', uv.x),
      y: getRealPosition('y', uv.y)
      }
  }
  return null                
}




//     jQuery("#text_upload").click(function (event) { 

//     if(document.getElementById('hyvEnterName').value != "") {   //add text when add text button is clicked.(here using enter as trigger)

//       event.preventDefault();

// if(document.getElementById('edval22').value == 0)

// {
// $(".hyv-preview").css({"cursor": "crosshair"});

//       container.addEventListener("pointerdown", function(e){
//         getCanvasUVPointAndPlaceTheText(container,e);
//       }, {once : true});

// }

// else
// {

// TCtext1.font = document.getElementById('selection-box').value;
// TCtext1.size = document.getElementById('txt_size').value;
// TCtext1.italics  = document.getElementById('italic').value;
//   TCtext1.underlined  = document.getElementById('underline').value;
  
// if (TCtext1.underlined === "true") {
//   TCtext1.underlined = true;
// }
// else
// {
//   TCtext1.underlined = false;
// }
// if (typeof TCtext1.italics === "undefined") {
//   TCtext1.italics = '';
// }
// TCtext1.bold  = document.getElementById('bold').value;
// if (typeof TCtext1.bold === "undefined") {
//   TCtext1.bold = 'bold';
// }
// TCtext1.align = document.getElementById('align-value').value;
// if (typeof TCtext1.align === "undefined") {
//     TCtext1.align = "center";
// }

// console.log(TCtext1.underlined);
    
//  editTextOnCanvas( document.getElementById('edval22').value, document.getElementById('hyvEnterName').value, TCtext1.font, document.getElementById('colorPicker').value, TCtext1.align, TCtext1.bold, TCtext1.underlined, TCtext1.italics, TCtext1.size);
//  document.getElementById('edval22').value = 0;  

// }

// } 


  
//   });
  
  
  
     jQuery("#text_upload").click(function (event) { 

    if(document.getElementById('hyvEnterName').value != "") {   //add text when add text button is clicked.(here using enter as trigger)

      event.preventDefault();

if(document.getElementById('edval22').value == 0)

{
  TCtext1.underlined  = document.getElementById('underline').value;
if (TCtext1.underlined === "true") {
   TCtext1.underlined = true;
}
else
{
  TCtext1.underlined = false;
}
TCtext1.italics  = document.getElementById('italic').value;
if (typeof TCtext1.italics === "undefined") {
  TCtext1.italics = '';
}
TCtext1.bold  = document.getElementById('bold').value;
// if (typeof TCtext1.bold === "undefined") {
//   TCtext1.bold = 'bold';
// }
TCtext1.align = document.getElementById('align-value').value;
if (typeof TCtext1.align === "undefined") {
    TCtext1.align = "center";
}

//console.log(TCtext1.underlined);

      TCtext1.font = document.getElementById('selection-box').value;
      TCtext1.size = document.getElementById('txt_size').value;

       let canvasUvPoint = getTransformUvfromCameraCentre(container,event);
      // console.log("pos ", canvasUvPoint.x, " ", canvasUvPoint.y);
      TCtext1.id = document.getElementById('textid').value;

      addTextOnCanvas(canvasUvPoint,document.getElementById('hyvEnterName').value, TCtext1.font, document.getElementById('colorPicker').value, TCtext1.align, TCtext1.bold, TCtext1.underlined, TCtext1.italics, TCtext1.size, TCtext1.id, 0);

}

else
{

TCtext1.font = document.getElementById('selection-box').value;
TCtext1.size = document.getElementById('txt_size').value;
TCtext1.italics  = document.getElementById('italic').value;
  TCtext1.underlined  = document.getElementById('underline').value;
  
if (TCtext1.underlined === "true") {
   TCtext1.underlined = true;
}
else
{
  TCtext1.underlined = false;
}
if (typeof TCtext1.italics === "undefined") {
  TCtext1.italics = '';
}
TCtext1.bold  = document.getElementById('bold').value;
// if (typeof TCtext1.bold === "undefined") {
//   TCtext1.bold = 'bold';
// }
TCtext1.align = document.getElementById('align-value').value;
if (typeof TCtext1.align === "undefined") {
    TCtext1.align = "center";
}

//console.log(TCtext1.underlined);
 //TCtext1.rot = document.getElementById('txt_angle').value;
 TCtext1.id = document.getElementById('textid').value;
 editTextOnCanvas( document.getElementById('edval22').value, document.getElementById('hyvEnterName').value, TCtext1.font, document.getElementById('colorPicker').value, TCtext1.align, TCtext1.bold, TCtext1.underlined, TCtext1.italics, TCtext1.size, TCtext1.id, document.getElementById('txt_angle').value);
 document.getElementById('edval22').value = 0;
 document.getElementById('textid').value = 0;

}

} 


  
  });
  
  jQuery(document).on('click', '.ed_text1', function(event) { 
       var textId = document.getElementById('textid').value;

        let objects = canvas.getObjects();
      objects.forEach(function(e) {
      if (e && e.type === 'i-text') { 
      if (e.id === textId) {
       
            //console.log(Math.round(e.scaleX*10));
            document.getElementById('txt_angle').value = e.angle;
            document.getElementById('txt_size').value = Math.round(e.scaleX*10);
            jQuery('.txt_size_range').html(Math.round(e.scaleX*10)+"pt");
              
} }

   });  }); 
  
  

// $('#myfield').text('New value');
//$('#myfield').val('New value');

  // jQuery("#uploadflag").change(function (event){ 



//   jQuery(document).on('click', '#uploadflag', function(event) { //add logo when add logo button is clicked.(here using enter as trigger)

//       $(".hyv-preview").css({"cursor": "crosshair"});

//       event.preventDefault();      
//       container.addEventListener("pointerdown", function(e){
//         getCanvasUVPointAndPlaceTheLogo(container,e);
//       }, {once : true});

// });

   jQuery(document).on('click', '#uploadflag', function(event) { //add logo when add logo button is clicked.(here using enter as trigger)

      let canvasUvPoint = getTransformUvfromCameraCentre(container,event);
      addLogoOnCanvas(canvasUvPoint, document.getElementById('uploadflag').value, TCImage1.size,0,150);

});

  // jQuery("#hyvLogoupload").change(function (event){   //add logo when add logo button is clicked.(here using enter as trigger)

  //   $(".hyv-preview").css({"cursor": "crosshair"});

  //     event.preventDefault();      
  //     container.addEventListener("pointerdown", function(e){
  //       getCanvasUVPointAndPlaceTheLogo(container,e);
  //     }, {once : true});
  
  // });


//  jQuery(document).on('click', '.vv', function(event) {

//   $(".hyv-preview").css({"cursor": "crosshair"});

//   window.setTimeout(  
//       function() {    //add logo when add logo button is clicked.(here using enter as trigger)
  
//       event.preventDefault();      
//       container.addEventListener("pointerdown", function(e){
//         getCanvasUVPointAndPlaceTheLogoclip(container,e);
//       }, {once : true});
//   },  
//       100
//   );
  
//   });


jQuery(document).on('click', '.vv', function(event) {

  window.setTimeout(  
      function() {    //add logo when add logo button is clicked.(here using enter as trigger)
     let canvasUvPoint = getTransformUvfromCameraCentre(container,event);
     addLogoOnCanvas(canvasUvPoint, document.getElementById('clipart-url').value, TCImage1.size,0,150);
   },  
      100
  );
  
  });



  // window.addEventListener("keyup", function (event) {   //chnage tshirt pattern color
  //     if (event.keyCode === 67) {
  //         console.log('enter')
  //         let id = 2;
  //         event.preventDefault();
  //         changeColorOnPattern(id, '#ff0000')
  //         render();
  //     }
  // });

  // window.addEventListener("keyup", function (event) {   //add tshirt styles when style is changed
  //   if (event.keyCode === 84) {
  //       event.preventDefault();
  //       addTshirtStyles(['long_sleeves','collar', 'v_neck'])
  //       render();
  //   }
  // });


//   window.addEventListener("keyup", function (event) {   //change tshirt patterns
//     if (event.keyCode === 80) {
//         console.log('enter')
//         event.preventDefault();
// ////
// const newColor1 = new TCColor();
// newColor1.patternUrl = "http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/1.png";
// newColor1.patternColor = "#ffffff";
// newColor1.patternId = 1;
// const newColor2 = new TCColor();
// newColor2.patternUrl = "http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/color1.svg";
// newColor2.patternColor = "#0000ff";
// newColor2.patternId = 2;
// const newColor3 = new TCColor();
// newColor3.patternUrl = "http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/color2.svg";
// newColor3.patternColor = "#00ff00";
// newColor3.patternId = 3;
// const newPatternArray = [newColor1,newColor2,newColor3];
// ////
//         changePatternsOnTshirtModel(newPatternArray);
//         render();
//     }
//   });

  // window.addEventListener("keyup", function (event) {   //edit text when edit button is clicked.(here using enter as trigger)
  //   if (event.keyCode === 69) {
  //       event.preventDefault();
  //       editTextOnCanvas( TCtext1.text, "Bye Hyve", "Pacifico", "red", TCtext1.align, TCtext1.bold, false, TCtext1.italics, TCtext1.size);
  //   }
  // });
 var threeurl = document.getElementById('pluginurl').value;
 var desid = jQuery('#uniquedesignid').val();

 
 jQuery(document).on('click', '#savemydesign', function() {
      
      $("#save_team").click();
     
      let setflag = $(this).attr("alt");
    
    
    if(setflag == 1)
     {
    jQuery('#blackOverlay').show();
     }
     if(setflag == 2)
     {
    jQuery('#blackOverlay').show(); 
     jQuery('.savemybt2').hide();
    jQuery('#progicon').show();
     }

 document.getElementById('windowclose').value = 1;   //save canvas elements when s is pressed
    
     //   event.preventDefault();
        // saveTheCanvasElements();
          let savedConfigObject = new TConfig();
  let elements = canvas.getObjects();
  elements.forEach(e => {
    if(e && e.type === 'i-text'){
      let savedText = new TCtext();
      savedText.text = e.text;
      savedText.font = e.fontFamily;
      savedText.size = e.scaleX*10;
      savedText.align = e.textAlign;
      savedText.color = e.fill;
      savedText.bold = e.fontWeight;
      savedText.italics = e.fontStyle;
      savedText.underlined = e.underline;
      savedText.top = e.top;
      savedText.left = e.left;
      savedText.id = e.id;
      savedText.rot = e.angle;
      savedConfigObject.TCtexts.push(savedText);
    }
    else{
      let savedLogo = new TCImage();
      savedLogo.imageUrl = e.cacheKey;
      savedLogo.size = e.scaleX;
      savedLogo.top = e.top;
      savedLogo.left = e.left;
      savedLogo.height = e.height;
      savedLogo.width = e.width;
      savedLogo.rot = e.angle;
      savedConfigObject.TCimages.push(savedLogo)
    }
  });     
  // for(let index=0; index< materials.length; index++){
  //   if(index !== 0){
  //     let savedPattern = new TCColor();
  //     savedPattern.patternId = index;
  //     savedPattern.patternColor = materials[index].color;
  //     savedPattern.patternUrl = materials[index].map.image.currentSrc;
  //     savedConfigObject.TCColor.push(savedPattern)
  //   }
  // }
  savedConfigObject.tshirtStyle = tshirtStyle;
  // console.log(savedConfigObject)

       var myJsonString = JSON.stringify(savedConfigObject);
       

         // AJAX request
         $.ajax({
             url: threeurl+'form.php?unique_id='+desid,
            type: 'post',
            data: {threedata:myJsonString },
            success: function(data){
               // console.log(data);
            }
         });


    
  });

var id;
  jQuery(document).on('click', '#savemydesign', function() { 

            renderSS();
            let setflag = $(this).attr("alt");
            jQuery('#setflag').val(setflag);                                                      //save canvas elements when s is pressed
    
     //   event.preventDefault();



var i = 0; 
 var j = 4; 
 var x = 0;                //  set your counter to 1

function myLoop() { 

camera.position.set(camPosX[i],camPosY[i],camPosZ[i] );        //  create a loop function
  setTimeout(function() { 
    
     var img = new Image();
    // Without 'preserveDrawingBuffer' set to true, we must render now
    renderer.render(scene, camera);
    img.src = renderer.domElement.toDataURL();
    // w.document.body.appendChild(img); 
     var base64URL = img.src;
       $.ajax({
            url: threeurl+'form.php?unique_id='+desid,
            type: 'post',
            data: {image: base64URL,pos: i},
            success: function(data){
               // console.log('Upload successfully');
               if(x == 3)
               {
                // jQuery('#add_to_cart2').trigger('submit');

                //console.log(jQuery('#add_to_cart2'));
                 jQuery('#add_to_cart2').submit();


               }
               x++;

               


            }
         });





       
    i++;                    
    if (i < j) { 


              
      myLoop();             
    }
    else{
        cancelAnimationFrame(id);
    }
  }, 500)
}

myLoop(); 



      });
      
      function renderSS() {
  id = requestAnimationFrame( renderSS );
  controls.update();
  renderer.render( scene, camera );
}

  // window.addEventListener("keyup", function (event) {   //delete the element from canvas by pressing d
  //   if (event.keyCode === 68) {
  //       event.preventDefault();
  //       deleteTextFromCanvas("Hello Hyve")
  //       //deleteLogoFromCanvas("http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/sprite.png")
  //   }
  // });

    jQuery(document).on('click', '.updelimgcl', function() {

  window.setTimeout(  
      function() {      

    var deldata = document.getElementById('updelimg00').value;
    deleteLogoFromCanvas(deldata);
    
      },  
      100
  );


 }) ;
 
 
 
 
 

     jQuery(document).on('click', '.del_text1', function() {

  window.setTimeout(  
      function() {      

    var deldata = document.getElementById('deltextid').value;
deleteTextFromCanvas(deldata);
    
      },  
      100
  );


 }) ;


        jQuery(document).on('click', '.h_asts', function() {

  window.setTimeout(  
      function() {

var hide_assets = document.getElementById('hide_assets').value;
      if(hide_assets != 0) {      

    

    // console.log(hide_assets);
        //event.preventDefault();
  
        // console.log(hide_assets.split(","));
        addTshirtStyles(hide_assets.split(","));
        render();

      }
    
      },  
      100
  );


 }) ;


  // window.addEventListener("keyup", function (event) {   //recenter camera
  //   if (event.keyCode === 82) {
  //       event.preventDefault();
  //       //recenterCameraToTargetPosition(recenteredCameraPosition);
  //       isRotate = true;
  //   }
  // });
 var threeurl = document.getElementById('pluginurl').value;
 var desid = jQuery('#uniquedesignid').val();
 
var id;
  jQuery(document).on('click', '#sharemydesign', function() {   //screen shot recenter in order i=0(left), i=1(right), i=2(back), i=3(front)
renderSS();
            jQuery('#blackOverlay').show();
        
        event.preventDefault();

let mathrand = Math.floor((Math.random() * 100) + 1);

var i = 0; 
 var j = 4; 
 var x = 0;                //  set your counter to 1

function myLoop() { 

camera.position.set(camPosX[i],camPosY[i],camPosZ[i] );        //  create a loop function
  setTimeout(function() { 
    
     var img = new Image();
    // Without 'preserveDrawingBuffer' set to true, we must render now
    renderer.render(scene, camera);
    img.src = renderer.domElement.toDataURL();
    // w.document.body.appendChild(img); 
     var base64URL = img.src;
       $.ajax({
            url: threeurl+'form.php?unique_id='+desid+mathrand,
            type: 'post',
            data: {image: base64URL},
            success: function(data){
               // console.log('Upload successfully');
               if(x == 3)
               {
                  jQuery('#blackOverlay').hide();
                  var dd = threeurl + 'mydesign.php?sharemydesign='+desid+mathrand;
                  
                    $('#p1').val(dd);
                document.getElementById('shareMyDes1').innerHTML = "COPY TO CLIPBOARD";
                jQuery('#hyvSharepop').click();
                 
                  
                  
//                   $(function () {
//                   $('[data-toggle="tooltip"]').tooltip('enable');
//                   $('[data-toggle="tooltip"]').tooltip('show');
// })

                //alert(threeurl + 'mydesign.php?sharemydesign='+desid+mathrand)
               }
               x++;

               


            }
         });





       
    i++;                    
    if (i < j) { 


              
      myLoop();             
    }
    else{
        cancelAnimationFrame(id);
    }
  }, 500)
}

myLoop();  






    
  });
}


function copyToClipboard1()
{
var $temp = $("<input>");
$("body").append($temp);
$temp.val($('#p1').val()).select();
document.execCommand("copy");
//console.log("hello");
$temp.remove();

}

function loadModel(){       
  const loader = new THREE.GLTFLoader();
  const dracoLoader = new THREE.DRACOLoader();
    dracoLoader.setDecoderPath( document.getElementById('pluginurl').value+'assets/draco' );
    loader.setDRACOLoader( dracoLoader );

  loader.load( configObject.productUrl, function ( gltf ) {
      tshirtModel = gltf.scene;
      materials = [Cmaterial,Fmaterial];
     // createPatternMaterials(configObject.TCColor.length, configObject.TCColor);
      gltf.scene.traverse(function (child) {

          if (child.isMesh && child.geometry) {
              let geometry = child.geometry;
              geometry.clearGroups();
              geometry.addGroup(0, Infinity, 0);
              geometry.addGroup(0, Infinity, 1);

             // addMeshGeometry(geometry, configObject.TCColor.length);
             // materials.forEach(element => {
                   materials[0].normalMap = child.material.normalMap;
                  normalMap = child.material.normalMap;
                   materials[0].metalnessMap = child.material.metalnessMap;
                  metalnessMap = child.material.metalnessMap;
                   materials[0].roughnessMap = child.material.roughnessMap;
                  roughnessMap = child.material.roughnessMap;
            //  });
              child.material = materials;

              if(configObject.tshirtStyle) addTshirtStyles(configObject.tshirtStyle);

          }
      });

      if(configObject.TCtexts){
        for(let index = 0; index<configObject.TCtexts.length; index++){

            let point = new THREE.Vector2();
            point.x = configObject.TCtexts[index].left;
            point.y = configObject.TCtexts[index].top;
            addTextOnCanvas(point, configObject.TCtexts[index].text, configObject.TCtexts[index].font, configObject.TCtexts[index].color, configObject.TCtexts[index].align, configObject.TCtexts[index].bold, configObject.TCtexts[index].underlined, configObject.TCtexts[index].italics, configObject.TCtexts[index].size, configObject.TCtexts[index].id, configObject.TCtexts[index].rot);
        }
      }
      if(configObject.TCimages){
          for(let index = 0; index<configObject.TCimages.length; index++){
              let point = new THREE.Vector2();
              point.x = configObject.TCimages[index].left;
              point.y = configObject.TCimages[index].top;
              addLogoOnCanvas(point, configObject.TCimages[index].imageUrl, configObject.TCimages[index].size, configObject.TCimages[index].rot);
          }
      }
      camera.aspect = container.clientWidth / container.clientHeight;       
      camera.updateProjectionMatrix();
      renderer.setSize(containerWidth, containerHeight);
      fitCameraToModel(); 
      scene.add( gltf.scene );
      render();
  }, function(event){
    let progressEvent = Math.round((event.loaded/document.getElementById('filesize').value)*100); 
    
       //console.log(event.loaded);
        //console.log(event.loaded);
    //console.log(progressEvent);
    if(progressEvent == 100)  
{ 
    render();
    window.setTimeout(  
      function() {
        jQuery('#threedoverlay').hide();
        },  
      2000
  );

 }

//      if(1/progressEvent == 0)
// { 

//   jQuery('#threedoverlay').hide();

//  }



     //get the percentage of model loaded.
  });
}

function fitCameraToModel()  {
  const boundingBox = new THREE.Box3();
  boundingBox.setFromObject( tshirtModel );
  const center = boundingBox.getCenter(new THREE.Vector3());
  const size = boundingBox.getSize(new THREE.Vector3());

  minCameraRadius = Math.max( size.x, size.y, size.z );
  if(camera){
      camera.position.set(0, minCameraRadius, minCameraRadius * 1.1)
      recenteredCameraPosition = new THREE.Vector3(camera.position.x,camera.position.y,camera.position.z);   
      camera.updateProjectionMatrix();
      camPosX = [minCameraRadius * 1.1,-minCameraRadius * 1.1,0,0];
      camPosY = [minCameraRadius,minCameraRadius,minCameraRadius,minCameraRadius];
      camPosZ = [0,0,-minCameraRadius * 1.1,minCameraRadius * 1.1];
    }
  if ( controls ) {
      controls.target = center;
      controls.minDistance = minCameraRadius * 0.75;
      controls.maxDistance = minCameraRadius * 2;
      controls.update();
      controls.saveState();
  } 
}      

// function createPatternMaterials(length, patternArray){
//   for(let index = 0; index < length; index++){
//     let map = loader.load( patternArray[index].patternUrl, render );
//     map.flipY = false;
//     let material = new THREE.MeshStandardMaterial( {
//       color: patternArray[index].patternColor, 
//       map: map,
//       alphaTest: 0.5,
//       visible: true,
//       side:2
//     } );
//     materials.push(material)
//   }
// }

// function addMeshGeometry(geometry, length){
//   for(let index = 0; index < length; index++){
//     geometry.addGroup(0, Infinity, index+1);
//   }
// }

function getCanvasUVPointAndPlaceTheText(sceneContainer, evt){
  var array = getMousePosition(container, evt.clientX, evt.clientY);
  onClickPosition.fromArray(array);
  var intersects = getIntersects(onClickPosition, scene.children);
  if (intersects.length > 0 && intersects[0].uv) {
      var uv = intersects[0].uv;
      intersects[0].object.material[1].map.transformUv(uv);
      let canvasUvPoint = new THREE.Vector2();
      canvasUvPoint.x = getRealPosition('x',uv.x);
      canvasUvPoint.y = getRealPosition('y',uv.y);


  TCtext1.underlined  = document.getElementById('underline').value;
if (TCtext1.underlined === "true") {
   TCtext1.underlined = true;
}
else
{
  TCtext1.underlined = false;
}
TCtext1.italics  = document.getElementById('italic').value;
if (typeof TCtext1.italics === "undefined") {
  TCtext1.italics = '';
}
TCtext1.bold  = document.getElementById('bold').value;
if (typeof TCtext1.bold === "undefined") {
  TCtext1.bold = 'bold';
}
TCtext1.align = document.getElementById('align-value').value;
if (typeof TCtext1.align === "undefined") {
    TCtext1.align = "center";
}

//console.log(TCtext1.underlined);

      TCtext1.font = document.getElementById('selection-box').value;
      TCtext1.size = document.getElementById('txt_size').value;

      addTextOnCanvas(canvasUvPoint,document.getElementById('hyvEnterName').value, TCtext1.font, document.getElementById('colorPicker').value, TCtext1.align, TCtext1.bold, TCtext1.underlined, TCtext1.italics, TCtext1.size);
      $(".hyv-preview").css({"cursor": "default"});
      return {
      x: getRealPosition('x', uv.x),
      y: getRealPosition('y', uv.y)
      }
  }
  return null                
}

function getCanvasUVPointAndPlaceTheLogo(sceneContainer, evt){
  var array = getMousePosition(container, evt.clientX, evt.clientY);
  onClickPosition.fromArray(array);
  var intersects = getIntersects(onClickPosition, scene.children);
  if (intersects.length > 0 && intersects[0].uv) {
      var uv = intersects[0].uv;
      intersects[0].object.material[1].map.transformUv(uv);
      let canvasUvPoint = new THREE.Vector2();
      canvasUvPoint.x = getRealPosition('x',uv.x);
      canvasUvPoint.y = getRealPosition('y',uv.y);
      // addLogoOnCanvas(canvasUvPoint, "http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/bmwtax2.jpg", TCImage1.size);

addLogoOnCanvas(canvasUvPoint, document.getElementById('uploadflag').value, TCImage1.size,150);
$(".hyv-preview").css({"cursor": "default"});
      return {
      x: getRealPosition('x', uv.x),
      y: getRealPosition('y', uv.y)
      }
  }
  return null                
}

function getCanvasUVPointAndPlaceTheLogoclip(sceneContainer, evt){
  var array = getMousePosition(container, evt.clientX, evt.clientY);
  onClickPosition.fromArray(array);
  var intersects = getIntersects(onClickPosition, scene.children);
  if (intersects.length > 0 && intersects[0].uv) {
      var uv = intersects[0].uv;
      intersects[0].object.material[1].map.transformUv(uv);
      let canvasUvPoint = new THREE.Vector2();
      canvasUvPoint.x = getRealPosition('x',uv.x);
      canvasUvPoint.y = getRealPosition('y',uv.y);
      // addLogoOnCanvas(canvasUvPoint, "http://localhost/hyve/wp-content/plugins/3d-config/assets/textures/bmwtax2.jpg", TCImage1.size);

 addLogoOnCanvas(canvasUvPoint, document.getElementById('clipart-url').value, TCImage1.size,150);
 $(".hyv-preview").css({"cursor": "default"});
      return {
      x: getRealPosition('x', uv.x),
      y: getRealPosition('y', uv.y)
      }
  }
  return null                
}

function addTextOnCanvas(point, text, font, color, align, bold, underlined, italics, size, textId, rot){
  var text = new fabric.IText(text, {
      fontSize: 30,
      fontFamily: font,
      fill: color,
      textAlign: align,
      fontWeight: bold,
      left: point.x,
      top: point.y,
      angle: 0,
      originX: 'center',
      originY: 'center',
      underline: underlined,
      fontStyle: italics,
      borderColor: color,
      cornerColor: color,
      cornerSize: 20,
      cornerStyle: 'circle',
      transparentCorners: true,
      lockScalingFlip : true,
      scaleX : size/10,
      scaleY : size/10,
      id : textId,
      angle: rot,

  });

  if(!mobile){
      text.customiseCornerIcons({
        settings: {
            borderColor: 'gray',
            cornerSize: 40,
            cornerShape: 'circle',
           cornerBackgroundColor: 'gray',
            cornerPadding: 20,
        },
        tl: {
            icon: document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/icons/rotate.svg',
        },
        tr: {
            icon: document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/icons/resize.svg',
        },
    }, function() {
        canvas.renderAll();
    });
    text.setControlsVisibility({
        mt: false, 
        mb: false, 
        ml: false, 
        mr: false, 
        mtr: false,
        br: false,
        bl: false
    });
  }
  else{
    text.setControlsVisibility({
        mt: false, 
        mb: false, 
        ml: false, 
        mr: false, 
        mtr: false,
        br: false,
        bl: false,
        tr: false,
        tl: false
    });
  }
  canvas.add(text);    
  
  canvas.requestRenderAll();
}

function addLogoOnCanvas(point, imagUrl, size,rot, dfsize = null){
  
  fabric.util.loadImage(imagUrl, function (img) {
      var img = new fabric.Image(img, {
          left: point.x,
          top: point.y,
          scaleX:size,
          scaleY:size,
          angle: 0,
          borderColor: 'gray',
          cornerColor: 'gray',
          cornerSize: 20,
          cornerStyle: 'circle',
          originX: 'center',
          originY: 'center',
          transparentCorners: true,
          lockScalingFlip : true,
          cacheKey: imagUrl,
          centeredRotation:true,
          angle: rot,
          
      });
// console.log(dfsize);
if(dfsize != null) {
// console.log(dfsize);
     img.scaleToHeight(dfsize);
     img.scaleToWidth(dfsize); }

      if(!mobile){
          img.customiseCornerIcons({
            settings: {
                borderColor: 'gray',
                cornerSize: 40,
                cornerShape: 'circle',
                cornerBackgroundColor: 'gray',
                cornerPadding: 20,
            },
            tl: {
                icon: document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/icons/rotate.svg',
            },
            tr: {
                icon: document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/icons/resize.svg',
            },
        }, function() {
            canvas.renderAll();
        });
        img.setControlsVisibility({
            mt: false, 
            mb: false, 
            ml: false, 
            mr: false, 
            mtr: false,
            br: false,
            bl: false
        });
      }
      else{
        img.setControlsVisibility({
            mt: false, 
            mb: false, 
            ml: false, 
            mr: false, 
            mtr: false,
            br: false,
            bl: false,
            tr: false,
            tl: false
        });
      }
      canvas.add(img);
      canvas.renderAll();
  });
  
  canvas.requestRenderAll();
}

function addTshirtStyles(TshirtStyleToBeRemoved){
  tshirtModel.traverse(function(child){
    child.visible = true;
    if(TshirtStyleToBeRemoved.length>0){
      for(let index = 0; index < TshirtStyleToBeRemoved.length; index++){
        if(child.name === TshirtStyleToBeRemoved[index])
        {
            child.visible = false;
        }
      }
    }
  });
  tshirtStyle = TshirtStyleToBeRemoved;
  tshirtModelChildren = [];
  tshirtModel.children.forEach(child => {
    tshirtModelChildren.push(child);
  });

}

// function changeColorOnPattern(patternId, patternColor){
//   materials[patternId].color = new THREE.Color(patternColor);
// }

// function changePatternsOnTshirtModel(newPatternArray){
//   materials = null;
//   materials = [Fmaterial];
//   console.log("patterns ", materials)
//   createPatternMaterials(newPatternArray.length,newPatternArray);
//   tshirtModel.traverse(function(child){
//     if (child.isMesh && child.geometry) {
//       let geometry = child.geometry;
//       geometry.clearGroups();
//       geometry.addGroup(0, Infinity, 0);
//       addMeshGeometry(geometry,newPatternArray.length);
//       materials.forEach(element => {
//           element.normalMap = normalMap;
//           element.metalnessMap = metalnessMap;
//           element.roughnessMap = roughnessMap;
//       });
      
//       child.material = materials;
//     }

//   });
// }

function editTextOnCanvas( oldText, newText, font, color, align, bold, underlined, italics, size, textId, rot){
  let objects = canvas.getObjects();
  objects.forEach(function(e) {
      if (e && e.type === 'i-text') { 
      if (e.id === textId) {
          e.set({ 
              text:newText,
              fontFamily: font, 
              fill: color,
              textAlign: align,
              fontWeight: bold,
              underline: underlined,
              fontStyle: italics,
              scaleX : size/10,
              scaleY : size/10,
              borderColor: color,
              cornerColor: color,
              angle:rot,
          }); 
          if(!mobile){
            e.customiseCornerIcons({
              settings: {
                  borderColor: 'gray',
                  cornerSize: 40,
                  cornerShape: 'circle',
                  cornerBackgroundColor: 'gray',
                  cornerPadding: 20,
              },
              tl: {
                  icon: document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/icons/rotate.svg',
              },
              tr: {
                  icon: document.getElementById('siteurl').value+'/wp-content/plugins/3d-config/assets/icons/resize.svg',
              },
          }, function() {
              canvas.renderAll();
          });
          e.setControlsVisibility({
              mt: false, 
              mb: false, 
              ml: false, 
              mr: false, 
              mtr: false,
              br: false,
              bl: false
          });
        }
        else{
          e.setControlsVisibility({
              mt: false, 
              mb: false, 
              ml: false, 
              mr: false, 
              mtr: false,
              br: false,
              bl: false,
              tr: false,
              tl: false
          });
        }
      }
  }    
  });  
  canvas.requestRenderAll();
}


function deleteTextFromCanvas( id){
  let objects = canvas.getObjects();
  objects.forEach(function(e) {
      if (e && e.type === 'i-text') { 
          if (e.id === id) {
              canvas.remove(e);

          }
      }    
  });  
  canvas.requestRenderAll();
}
function deleteLogoFromCanvas( urlId){
  let objects = canvas.getObjects();
  objects.forEach(function(e) {
      if (e && e.type !== 'i-text') { 
          if (e.cacheKey === urlId) {
            canvas.remove(e);
          }
      }    
  });  
  canvas.requestRenderAll();
}

function saveTheCanvasElements(){
  let savedConfigObject = new TConfig();
  let elements = canvas.getObjects();
  elements.forEach(e => {
    if(e && e.type === 'i-text'){
      let savedText = new TCtext();
      savedText.text = e.text;
      savedText.font = e.fontFamily;
      savedText.size = e.scaleX;
      savedText.align = e.textAlign;
      savedText.color = e.fill;
      savedText.bold = e.fontWeight;
      savedText.italics = e.fontStyle;
      savedText.underlined = e.underline;
      savedText.top = e.top;
      savedText.left = e.left;
      savedConfigObject.TCtexts.push(savedText);
    }
    else{
      let savedLogo = new TCImage();
      savedLogo.imageUrl = e.cacheKey;
      savedLogo.size = e.scaleX;
      savedLogo.top = e.top;
      savedLogo.left = e.left;
      savedConfigObject.TCimages.push(savedLogo)
    }
  });     
  // for(let index=0; index< materials.length; index++){
  //   if(index !== 0){
  //     let savedPattern = new TCColor();
  //     savedPattern.patternId = index;
  //     savedPattern.patternColor = materials[index].color;
  //     savedPattern.patternUrl = materials[index].map.image.currentSrc;
  //     savedConfigObject.TCColor.push(savedPattern)
  //   }
  // }
  savedConfigObject.tshirtStyle = tshirtStyle;
  // console.log(savedConfigObject)
}

function isMobile() {
  var match = window.matchMedia || window.msMatchMedia;
  if(match) {
      var mq = match("(pointer:coarse)");
      return mq.matches;
  }
  return false;
}

function onWindowResize() {
  camera.aspect = container.clientWidth / container.clientHeight;       
  camera.updateProjectionMatrix();
  renderer.setSize(containerWidth, containerHeight);
  render();
}

jQuery('.s-color').change(function(){
        window.setTimeout(
      function() {
        render();
      },
          200
      );
    
});
jQuery('.preset').click(function(){
        window.setTimeout(
      function() {
        render();
      },
          200
      );
    
});
jQuery('.reset-col').click(function(){
        window.setTimeout(
      function() {
        render();
      },
          200
      );
    
});

function render() {
//   requestAnimationFrame( render );

//   if(isRotate){
//     camera.position.set(recenteredCameraPosition.x,recenteredCameraPosition.y ,recenteredCameraPosition.z)
//     tshirtModel.rotation.y += 0.01;
//     if(tshirtModel.rotation.y >= 6.25){
//       isRotate = false;
//       tshirtModel.rotation.y = 0;
//     }
//   }
//   controls.update();
  Ctexture.needsUpdate = true;
  renderer.render( scene, camera );
}


/**
* Fabric.js patch
*/
let previousPointer = new THREE.Vector2();
fabric.Canvas.prototype.getPointer =  function (e, ignoreZoom) {
  if (this._absolutePointer && !ignoreZoom) {
      return this._absolutePointer;
  }
  if (this._pointer && ignoreZoom) {
      return this._pointer;
  }
  var pointer = fabric.util.getPointer(e),
      upperCanvasEl = this.upperCanvasEl,
      bounds = upperCanvasEl.getBoundingClientRect(),
      boundsWidth = bounds.width || 0,
      boundsHeight = bounds.height || 0,
      cssScale;

  if (!boundsWidth || !boundsHeight ) {
      if ('top' in bounds && 'bottom' in bounds) {
      boundsHeight = Math.abs( bounds.top - bounds.bottom );
      }
      if ('right' in bounds && 'left' in bounds) {
      boundsWidth = Math.abs( bounds.right - bounds.left );
      }
  }
  this.calcOffset();
  pointer.x = pointer.x - this._offset.left;
  pointer.y = pointer.y - this._offset.top;
  /* BEGIN PATCH CODE */
  if (e.target !== this.upperCanvasEl) {
      var positionOnScene = getPositionOnScene(container, e);
      if(positionOnScene){
          previousPointer.x = positionOnScene.x;
          previousPointer.y = positionOnScene.y;
          pointer.x = positionOnScene.x;
          pointer.y = positionOnScene.y;
      }
      else{
          pointer.x = previousPointer.x;
          pointer.y = previousPointer.y;
      }

  }
  /* END PATCH CODE */
  if (!ignoreZoom) {
      pointer = this.restorePointerVpt(pointer);
  }

  if (boundsWidth === 0 || boundsHeight === 0) {
      cssScale = { width: 1, height: 1 };
  }
  else {
      cssScale = {
      width: upperCanvasEl.width / boundsWidth,
      height: upperCanvasEl.height / boundsHeight
      };
  }

  return {
      x: pointer.x * cssScale.width,
      y: pointer.y * cssScale.height
  };
}

/**
* Event handler
*/

function onMouseEvt(evt) {
      evt.preventDefault();
      const positionOnScene = getPositionOnScene(container, evt)
      if (positionOnScene) {
          const canvasRect = canvas._offset;
          const simEvt = new MouseEvent(evt.type, {
          clientX: canvasRect.left + positionOnScene.x,
          clientY: canvasRect.top + positionOnScene.y - window.scrollY
          });
          canvas.upperCanvasEl.dispatchEvent(simEvt);
      }
      else{
          canvas.discardActiveObject();
          canvas.renderAll(); 
      }
}



/**
* Three.js Helper functions
*/
function getPositionOnScene(sceneContainer, evt) {
  try{
    var array = getMousePosition(container, evt.clientX, evt.clientY);
    onClickPosition.fromArray(array);
    var intersects = getIntersects(onClickPosition, scene.children);
    let i = 0;
    tshirtModelChildren.forEach(child => {
        if(child.visible === false){
          if(intersects[i] !== undefined){
            while(intersects[i].object.name === child.name){
              i++;
            }
          }

        }
    });


    if (intersects.length > 0 && intersects[i].uv ) {
        var uv = intersects[i].uv;
        intersects[i].object.material[1].map.transformUv(uv);

        return {
        x: getRealPosition('x', uv.x),
        y: getRealPosition('y', uv.y)
        }
    }
    else{
        if(!isElementSelected) controls.enabled = true;

    }
    return null
  }
  catch(err) {
    //console.log(err)
  }
}

function getRealPosition(axis, value) {
  let CORRECTION_VALUE = axis === "x" ? 4.5 : 5.5;
  
  if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
      var dessizev = 1024;
  }
  else 
  {
       var dessizev = 2048;
  }
  
  

  return Math.round(value * dessizev) - CORRECTION_VALUE;   //replace 2048 if canvas size is increased to 4096
}

var getMousePosition = function(dom, x, y) {
  var rect = dom.getBoundingClientRect();
  var windowWidth = dom.offsetWidth;
  var windowHeight = dom.offsetHeight;
  return [((x - rect.x) / windowWidth) , ((y - rect.y) / windowHeight) ];
};

var getIntersects = function(point, objects) {
  mouse.set(point.x * 2 - 1, -(point.y * 2) + 1);
  raycaster.setFromCamera(mouse, camera);
  return raycaster.intersectObjects(objects);
};
if(!mobile){
  container.addEventListener("mousedown", onMouseEvt, false);

  canvas.on('selection:created', function(e) {
    isElementSelected = true;
    controls.enabled = false;

  });
  canvas.on('selection:cleared', function(e) {
    isElementSelected = false;
    controls.enabled = true;

  });

  canvas.on('after:render', function(){
    Ftexture.needsUpdate = true;
     render();
  });
}
if(mobile){
  container.addEventListener("mousedown", onMouseEvt, false);

  let start = [];
  canvas.on('after:render', function(){
    Ftexture.needsUpdate = true;
     render();
    selectedElement = canvas.getActiveObject();
  });
  canvas.on('selection:created', function(e) {
      isElementSelected = true;
     // selectedElement = e.target;
      controls.enabled = false;
  });

  canvas.on('selection:cleared', function(e) {
      isElementSelected = false;
      controls.enabled = true;

  });
var cur=0, thn=0, currot=0 , thnrot= 0;
  container.addEventListener('touchstart', function (e) {
      if(e.touches.length === 2) {
          isScaling = true
          doubleFinger = true;
          start = e.touches;
            cur=0;
            thn=0;
            currot = 0;
            thnrot = 0;
      }
  });
  container.addEventListener('touchend', function (e) {
      if(e.touches.length < 2) {
          isScaling = false;
          doubleFinger = false
      }
  });
  container.addEventListener('touchmove', function (e) {
    if(e.touches.length>=2&&isScaling&&isElementSelected){
      var now=e.touches; // Get the second set of two points
      var scale=getDistance(now[0],now[1])/getDistance(start[0],start[1]); // Get scaling, getdistance is a method of Pythagorean theorem
      var rotation=getAngle(now[0],now[1])-getAngle(start[0],start[1]); // Get the rotation angle, getangle is a way to get the angle
      e.scale=scale.toFixed(2);
      e.rotation=rotation.toFixed(2);
      
      //console.log("rotation ", e.rotation);
      
       cur = e.scale;
       currot = rotation;
 
 let zoomfactor= 0.05 ;

if(selectedElement.text !== undefined){
   zoomfactor = 0.05;
 }
 else{
      if(selectedElement.height < 100 || selectedElement.width < 100){
     zoomfactor= 0.05 ;
     }
     if((selectedElement.height > 100 && selectedElement.height <= 500) || (selectedElement.width > 100 && selectedElement.width <= 500)){
         zoomfactor= 0.01 ;
     } 
      if((selectedElement.height > 500 && selectedElement.height <= 1000) || (selectedElement.width > 500 && selectedElement.width <= 1000)){
         zoomfactor= 0.005 ;
     } 

     if((selectedElement.height > 1000 && selectedElement.height <= 1500) || (selectedElement.width > 1000 && selectedElement.width <= 1500)){
         zoomfactor= 0.001 ;
     }
 
     if((selectedElement.height > 1500 && selectedElement.height <= 2000) || (selectedElement.width > 1500 && selectedElement.width <= 2000)){
         zoomfactor= 0.0005 ;
     } 
  
    if((selectedElement.height > 2000 ) || (selectedElement.width > 2000)){
         zoomfactor= 0.0005 ;
     } 
 }


// console.log(selectedElement,"sasa", zoomfactor)
    if(cur > thn)
    {
       // console.log("Got larger.");
        selectedElement.set('scaleX', selectedElement.scaleX + zoomfactor);
         selectedElement.set('scaleY', selectedElement.scaleY +zoomfactor);


           
                 canvas.requestRenderAll();

    }
    else if(cur < thn)
    {
       // console.log("Got smaller.");
         selectedElement.set('scaleX', selectedElement.scaleX - zoomfactor);
         selectedElement.set('scaleY', selectedElement.scaleY - zoomfactor);


                    
                 canvas.requestRenderAll();

    }



    // Store the changed value for comparison on new loop() call.
    // Notice how we do this AFTER 'now' has changed and we've compared it.
    thn = cur;
    
    if(currot === thnrot){
      // console.log("same")
    }    
    else if(currot > thnrot)
    {
        //console.log("Got larger.", e.rotation);
        selectedElement.set('angle',  selectedElement.angle + (zoomfactor+0.8));
        canvas.requestRenderAll();

    }

    else if(currot < thnrot)
    {
        //console.log("Got smaller.", e.rotation);
        selectedElement.set('angle',  selectedElement.angle - (zoomfactor+0.8)  );
        canvas.requestRenderAll();
    }


    // Store the changed value for comparison on new loop() call.
    // Notice how we do this AFTER 'now' has changed and we've compared it.
    thnrot = currot;

           console.log("rot ", selectedElement.angle, " + ", e.rotation, "  =  ", rotation) 


  

        // selectedElement.set('scaleX', e.scale);
        // selectedElement.set('scaleY', e.scale);
       // selectedElement.set('scaleX', e.scale/3);
       // selectedElement.set('scaleY', e.scale/3);
        selectedElement.set('originX', 'center');
        selectedElement.set('originY', 'center');

       // selectedElement.set('angle', selectedElement.angle + rotation);
        canvas.requestRenderAll();

    }
  });

  container.addEventListener("pointermove", function(evt){
      var points = getPositionOnScene(container, evt)
      if(isElementSelected && !doubleFinger){
          if(points){
              selectedElement.set('top', points.y);
              selectedElement.set('left', points.x);

              canvas.requestRenderAll();
          }
      }
  })

  function getDistance(p1, p2) {
    var x = p2.pageX - p1.pageX,
      y = p2.pageY - p1.pageY;
    return Math.sqrt((x * x) + (y * y));
  };
  function getAngle(p1, p2) {
    var x = p1.pageX - p2.pageX,
      y = p1.pageY- p2.pageY;
    return Math.atan2(y, x) * 180 / Math.PI;
  };
}




var intervalId = window.setInterval(function(){
   
  
 

}, 10);