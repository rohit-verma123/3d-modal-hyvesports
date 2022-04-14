
<?php
if ( !defined('ABSPATH') ) {
    //If wordpress isn't loaded load it up.
    $path = $_SERVER['DOCUMENT_ROOT'];
    include_once $path . '/wp-load.php';
}

?>
<?php  global $post;
//$page_id = $post->ID;
global $wpdb;
 ?>
<!doctype html>
<html lang="en">

<head>
      <style>
    .hyv-dyo {
  display: block !important;
  width: 800px;
  margin: auto;
}
  </style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Model</title>
  <!-- <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/starter-template/"> -->
  <!-- Bootstrap core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css" />
  <link href="<?php echo _3D_CONFIG_URL; ?>assets/css/hyve.css?ver=<?php echo rand(100,999); ?>" rel="stylesheet">
     <link href="<?php echo _3D_CONFIG_URL; ?>assets/css/style.css?ver=<?php echo rand(100,999); ?>" rel="stylesheet">
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@700&family=Roboto:ital,wght@0,400;0,500;0,700;1,400&display=swap"
    rel="stylesheet">

 <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
 <script src="https://unpkg.com/three@0.136.0/build/three.min.js"></script>
 <script src="https://unpkg.com/three@0.136.0/examples/js/loaders/RGBELoader.js"></script>
 <script src="https://unpkg.com/three@0.136.0/examples/js/loaders/GLTFLoader.js"></script>
 <script src="https://unpkg.com/three@0.136.0/examples/js/controls/OrbitControls.js"></script>
 <script src="https://unpkg.com/three@0.136.0/examples/js/geometries/DecalGeometry.js"></script>
  <script src="https://unpkg.com/three@0.136.0/examples/js/loaders/DRACOLoader.js"></script>
 <script src="https://unpkg.com/three@0.136.0/examples/js/libs/stats.min.js"></script>


 <script src="https://cdnjs.cloudflare.com/ajax/libs/dat-gui/0.7.1/dat.gui.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/2.2.1/fabric.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/fabric-customise-controls@2.0.6-beta/dist/customiseControls.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/get-svg-colors-browser@2.0.5/dist/get-svg-colors-browser.min.js"></script>
 
 <link href="https://fonts.cdnfonts.com/css/maximum-impact" rel="stylesheet">
 <link href="https://fonts.cdnfonts.com/css/amavos" rel="stylesheet">
 <link href="https://fonts.cdnfonts.com/css/betinya-sans" rel="stylesheet">
 <link href="https://fonts.cdnfonts.com/css/schoolbell" rel="stylesheet">
 <link href="https://fonts.cdnfonts.com/css/infected" rel="stylesheet">
                
                
                
                
 
<?php 
if( wp_is_mobile() ) { ?>
<script src="<?php echo _3D_CONFIG_URL; ?>assets/js/fabric_with_gestures.js"></script>
<?php /* ?> <script src="<?php echo _3D_CONFIG_URL; ?>assets/js/prism.js"></script> <?php */ ?>
<?php } ?>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->

<!--   <script src=
"https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.5/dist/html2canvas.min.js"></script> -->


</head>
<input type="hidden" id="threedata" value="0" name="">
<input type="hidden" id="pre-color" value="0" name="">
<input type="hidden" id="windowclose" value="0" name="">
<input type="hidden" id="siteurl" value="<?php echo site_url(); ?>" name="">
<input type="hidden" id="pluginurl" value="<?php echo _3D_CONFIG_URL; ?>" name="">
<?php
if(isset($_GET['unique_id'])) {
if(isset($_GET['unique_id']))
{

 $uu_id = $_GET['unique_id'];
 $u_id = md5(uniqid(rand(), true));
 $cmz = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_customization WHERE unique_id = '$uu_id';" );

 if(count($cmz) == 0)
 {
  
    $url = site_url();
  ?>
<script>
    window.location = '<?php echo $url; ?>';
</script> <?php

die;
 }



 foreach( $cmz as $key => $row) { 

  $productID = $row->product_id;
  $pset_col = $row->pset_col;
  $td_count = $row->teams_data;

  

 }

 $threedb = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_threejsdata WHERE unique_id = '$uu_id';" );

 foreach( $threedb as $key => $row) { 

  $data = $row->data;

    
  //$data1 = json_decode($data);

  

 }

  $pset_col2 = json_decode($pset_col);

   echo '<script>
function threedata()
{
  configObject1 = '.$data.';
  
  
   

}



document.getElementById("pre-color").value = '.$pset_col.';


document.getElementById("threedata").value = 1;
  </script>';

$ddata = json_decode($data);
$thr_array = json_decode(json_encode($ddata), True);

}


$table_1 = $wpdb->prefix . '3dconfig_models';
$table_2 = $wpdb->prefix . '3dconfig_product_meta_lookup';
$value = $wpdb->get_results( "SELECT m_file,pattern_count,svgfilecol,filesize FROM $table_1 INNER JOIN $table_2 ON $table_1.m_id = $table_2.m_id WHERE $table_2.product_id = $productID" );
$all = $wpdb->get_results( "SELECT * FROM $table_2  WHERE product_id = $productID" );
// var_dump($all);

foreach ($all as $curr){
   $product_url1 = $curr->preset_colors;
   $preset = json_decode($product_url1);
   $m_id = $curr->m_id;
   
   $assets_string =$curr->assets_string;
   $assets_default =$curr->assets_default;
$assets_string = json_decode($assets_string);
$assets_default = json_decode($assets_default);
$gender = $curr->gender;

}











foreach ($value as $cur){
   $product_url = $cur->m_file;
   $preset_cc = $cur->pattern_count;
   $svgfilecol = $cur->svgfilecol;
   $filesize = $cur->filesize;
   


   echo '<input type="hidden" id="productUrl" value="'._3D_CONFIG_URL.'assets/models/'.$product_url.'">';
   echo '<input type="hidden" id="uniquedesignid" value="'.$u_id.'">';
   echo '<input type="hidden" id="filesize" value="'.$filesize.'">';

}


$as = 0; 
while($as < count($assets_string))
{
$az = $wpdb->get_results( "SELECT assets_name  FROM {$wpdb->prefix}3dconfig_assets WHERE assets_id = $assets_string[$as]" ); 
foreach ($az as $cz) {

  $as_name[] = $cz->assets_name;
}
$as++; }
function array_flatten($array) { 
  if (!is_array($array)) { 
    return FALSE; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } 
    else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
} 
$ssdef = array_flatten($assets_default);


$filteredFoo = array_diff($as_name, $ssdef);

$filteredFoo = implode(', ', $filteredFoo);
$filteredFoo = str_replace(' ', '', $filteredFoo);
echo '<input type="hidden" id="hiddenassets" value="'.$filteredFoo.'">';


?>

<body <?php body_class( 'class-name' ); ?>>

  <main>
    <div style="padding-bottom: 0 !important;" class="hyv-dyo-container has-fixed-total">
      <div class="hyv-dyo">
        <div class="hyv-preview">
          <div class="hyv-preview__container">
               <figure style="justify-content: center;
align-items: center;
display: flex;" class="hyv-preview__figure">
<?php 
if ( wp_is_mobile() ) { ?>
 <div id="3d2" class="" style="width: 100% !important">
      
      <div id="3d-product-view" class="3d-product-view" style="width: 100% !important;">
                        <div style="justify-content: center;
display: flex;background: #fff;width: 100% !important;position: absolute;height: 100%;" id="threedoverlay">
                  <img style="margin: auto;" src="<?php echo _3D_CONFIG_URL.'/assets/';?>Settings1.gif">

</div>

        <?php }
else
{ ?>
           <div id="3d2" class="" style="width: 100% !important; height: 100% !important">
      
      <div id="3d-product-view" class="3d-product-view" style="width: 100% !important; height: 100% !important;">
         <div style="transition: all .4s ease-in-out;
position: absolute;
width: 100%;
top: 0;
left: 0;
height: 670px;
z-index: 9;
opacity: 1;
justify-content: center;
align-items: center;
padding-bottom: 90px;" id="threedoverlay">
                  <img style="max-width: 100%" src="<?php echo _3D_CONFIG_URL.'/assets/';?>Settings1.gif">

</div>
  <?php }
?>
           <!--   <img src="https://dummyimage.com/800x400">  -->
            <script src="<?php echo _3D_CONFIG_URL.'/assets/js/ThreeDConfigurator.js?ver=1.3'; ?>" type="module"></script>

      </div>

  </div>

<?php 
if ( wp_is_mobile() ) { ?>
  <div id="3d2" class="" style="width: 100% !important; display:none;">

    <canvas id="canvas" width="512" height="512"></canvas>
      <?php }
else
{ ?>
<div id="3d2" class="" style="width: 100% !important; height: 100% !important; display:none">
  <canvas id="canvas" width="2048" height="2048"></canvas>
   <?php }
?>
  

  </div>

<?php 
if ( wp_is_mobile() ) { ?>
  <div id="3d2" class="" style="width: 100% !important; display:none;">

     <canvas id="canvasimg" width="512" height="512"></canvas>
      <?php }
else
{ ?>
<div id="3d2" class="" style="width: 100% !important; height: 100% !important; display:none">

   <canvas id="canvasimg" width="2048" height="2048"></canvas>
   <?php }
?>
      
 

  </div>
              <figcaption class="hyv-preview__figcaption">MTB RC-V CUSTOM CYCLING TEE</figcaption>
            </figure>
          </div>
        </div>

      </div>
    </div>
  </main>
<?php
$table_pattern = $wpdb->prefix . '3dconfig_pattern';
$pattern_tb = $wpdb->get_results( "SELECT * FROM $table_pattern  WHERE m_id = $m_id" );
foreach ($pattern_tb as $curr){
   $svg = $curr->pattern_file;
   } ?>
<iframe style="display: none;" id="emb" src="<?php echo _3D_CONFIG_URL; ?>assets/img/pattern/<?php echo $svg; ?>"></iframe>
  <script src="<?php echo _3D_CONFIG_URL; ?>assets/js/svgcolor.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>
<div style="display:none;" id="blackOverlay" class="loading">Loading&#8230;</div>

     

</body>
<?php
}
else
{
  $url = site_url();
  ?>
<script>
    window.location = '<?php echo $url; ?>';
</script> <?php
}
?>


</html>