
<?php
/**
 * Template Name: Design Your Own Template
 * Template Post Type: post, page
 */
get_header(); ?>
<?php
if(isset($_POST['pid'])) {
global $woocommerce;
global $wpdb;
if($_POST['old_unique_id'] != 0) {
$old_cmz_id = $_POST['old_unique_id'];
}
//$mystring = $_POST['t-data'];
// $str = str_replace('\\', '', $mystring);


// $teams_data = json_decode($str);
// $array = json_decode(json_encode($teams_data), True);
//   $cc = 0;   
//           foreach( $array as $key => $row) {  
//           $ta[] = $row["value"];
//           $team_arr[] = array($row["name"] => $row["value"]);
//           $cc++;
//           if($cc == $_POST['td-count']) {
//           $team_a[] = $ta;
//           unset($ta);
//           $cc = 0; }
//                                             }



// $str = json_encode($team_a);


if(isset($_POST['addons-data'])) {
if($_POST['addons-data'] != null) {
$str_arr = preg_split ("/\,/", $_POST['addons-data']);
$teams = json_encode($str_arr);
}
else
{
  $teams = null;
}
}



$pset_col = preg_split ("/\,/", $_POST['pset-col']);

$pset_col = json_encode($pset_col);
if($_POST['setflag'] == 3)
{
  $flag1 = $_POST['setflag'];
  $_POST['setflag'] = 1;
}

function generateRandomString($length = 25) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    $randomString = strtoupper($randomString);
    return $randomString;
}
//usage 
$myRandomString = generateRandomString(8);



$cz_tbl = $wpdb->prefix . '3dconfig_customization';
//$wpdb->insert($cz_tbl, array('cmz_user_id' => $_COOKIE['UNIQUE_USER_ID'], 'product_id' => $_POST['pid'], 'teams_data' => $_POST['td-count'], 'addons_data' => $teams, 'pset_col' => $pset_col, 'unique_id' => $_POST['unique_id'], 'flag' => $_POST['setflag'], 'design_name' => $myRandomString)); 
// die;
if ( is_user_logged_in() ) {
  $wp_user_id = get_current_user_id();
  $wpdb->insert($cz_tbl, array('cmz_user_id' => $_COOKIE['UNIQUE_USER_ID'], 'product_id' => $_POST['pid'], 'teams_data' => $_POST['td-count'], 'addons_data' => $teams, 'pset_col' => $pset_col, 'unique_id' => $_POST['unique_id'], 'flag' => $_POST['setflag'], 'design_name' => $myRandomString, 'wp_user_id' => $wp_user_id, 'size' => $_POST['customsize'])); 

} else {
    $wpdb->insert($cz_tbl, array('cmz_user_id' => $_COOKIE['UNIQUE_USER_ID'], 'product_id' => $_POST['pid'], 'teams_data' => $_POST['td-count'], 'addons_data' => $teams, 'pset_col' => $pset_col, 'unique_id' => $_POST['unique_id'], 'flag' => $_POST['setflag'], 'design_name' => $myRandomString, 'size' => $_POST['customsize'])); 
}


if(isset($flag1))
{
  $_POST['setflag'] = 3;
}

$lastid44 = $wpdb->insert_id;

if($_POST['setflag'] == 1) {

$urlto = get_permalink().'?unique_id='.$_POST['unique_id'];

//wp_redirect( $urlto );

?>
<script>
    window.location = '<?php echo $urlto; ?>';
</script>
<?php

}
elseif($_POST['setflag'] == 2)
{
  //session_start();

 //$_SESSION["cmz_id"] =$lastid44;
//WC()->session->__unset( 'cmz_id' );
$retrive_data = WC()->session->get( 'cmz_id' );



if($retrive_data == "")
{
 $supper = array();

$arrayVariable = array(
    "pid"  => $_POST['pid'],
    "cmz_id" => $lastid44,
    "pqty" => $_POST['p-qty'],
);
array_push($supper, $arrayVariable);

WC()->session->set( 'cmz_id' , $supper );
$retrive_data = WC()->session->get( 'cmz_id' );

}
else
{
    
    //  foreach ($retrive_data as $k => $item) {
    // if ($item['pid'] == $_POST['pid']) {
    //       unset($retrive_data[$k]);
    //       break;
    // } } 
    
    if(isset($old_cmz_id))  {
     foreach ($retrive_data as $k => $item) {
    if ($item['cmz_id'] == $old_cmz_id) {
          unset($retrive_data[$k]);
          break;
    } } 
}
    
    $pq = 0;
     $pid = 0;
     foreach ($retrive_data as $k => $item) {
    if ($item['pid'] == $_POST['pid']) {
          $pid = $item['pid'];
          $pq = $pq+$item['pqty'];
    } } 
    
  $arrayVariable = array(
    "pid"  => $_POST['pid'],
    "cmz_id" => $lastid44,
    "pqty" => $_POST['p-qty'],
);

  
  array_push($retrive_data, $arrayVariable);


   WC()->session->set( 'cmz_id' , $retrive_data );
}







foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
     if ( $cart_item['product_id'] == $_POST['pid'] ) {
          WC()->cart->remove_cart_item( $cart_item_key );
     }
}



//WC()->cart->empty_cart();
//WC()->cart->add_to_cart( $_POST['pid'], $_POST['p-qty'] );


if($pid == $_POST['pid']) {
$pq = $pq + $_POST['p-qty'];
quadlayers_add_to_cart_function ($_POST['pid'],$pq);
} else {
quadlayers_add_to_cart_function ($_POST['pid'],$_POST['p-qty']);
}

//wp_redirect( wc_get_cart_url() );
$url = wc_get_cart_url();
//header('Location:'.$url);
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  window.location = '<?php echo $url; ?>';
}, false);
</script>
<?php
}
else
{
  //wp_redirect( site_url() );
  $url = site_url();
  header('Location:'.$url);
  ?>
<script>
    window.location = '<?php echo $url; ?>';
</script>
<?php
}



} ?>


<?php  global $post;
$page_id = $post->ID;
global $wpdb;
 ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Design Your Own - <?php if(isset($_GET["productID"])) {
  $product = wc_get_product( $_GET["productID"] );
  echo $product->get_title(); } ?></title>
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
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
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
if(isset($_GET["productID"]) || isset($_GET['unique_id'])) {



if(isset($_GET['unique_id']))
{

 $uu_id = $_GET['unique_id'];
 $u_id = md5(uniqid(rand(), true));
 $cmz = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_customization WHERE unique_id = '$uu_id';" );

 foreach( $cmz as $key => $row) { 

  $productID = $row->product_id;
  $pset_col = $row->pset_col;
   $td_count = $row->teams_data;
   $old_cmz_id = $row->cmz_id;

  

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

else

{

  $productID = $_GET["productID"];
  $u_id = md5(uniqid(rand(), true));


}





  


// foreach ( $colors as $hex => $count )
// {
//   if ( $count > 0 )
//   {
//     echo '<div style="width:100px;height:100px;background-color:#'.$hex.'"></div><br>';
//   }
// }

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



// $image = wp_get_attachment_image_src( get_post_thumbnail_id($productID), 'single-post-thumbnail' );
// $url       = $image[0];
// $uploads   = wp_upload_dir();
// $file_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $url );
// $num_results = (! empty($_POST['num_results'])) ? $_POST['num_results'] : 20;
// $num_results = $preset_cc;
// $delta = (! empty($_POST['delta'])) ? $_POST['delta'] : 24;
// $reduce_brightness = (isset($_POST['reduce_brightness'])) ? $_POST['reduce_brightness'] : 1;
// $reduce_gradients = (isset($_POST['reduce_gradients'])) ? $_POST['reduce_gradients'] : 1;


// include(_3D_CONFIG_PATH."colors.inc.php");


// $ex=new GetMostCommonColors();
// $colors=$ex->Get_Color($file_path, $num_results, $reduce_brightness, $reduce_gradients, $delta);

}
else

{
     
      echo '<input type="hidden" id="productUrl" value="'._3D_CONFIG_URL.'assets/models/indJersey.glb">';
}
?>
<?php
if($assets_string != null) {
    
$as = 0; 
while($as < count($assets_string))
{
$az = $wpdb->get_results( "SELECT assets_name  FROM {$wpdb->prefix}3dconfig_assets WHERE assets_id = $assets_string[$as]" ); 
foreach ($az as $cz) {

  $as_name[] = $cz->assets_name;
}
$as++; }
$ssdef = array_flatten($assets_default);

$filteredFoo = array_diff($as_name, $ssdef);

$filteredFoo = implode(', ', $filteredFoo);
$filteredFoo = str_replace(' ', '', $filteredFoo);
echo '<input type="hidden" id="hiddenassets" value="'.$filteredFoo.'">';
}
else
{
  echo '<input type="hidden" id="hiddenassets" value="">';
}


?>
<?php 
if(isset($_GET['unique_id']))
{
  echo '<input type="hidden" id="td_c" value="'.$td_count.'">';
}
else
{
   echo '<input type="hidden" id="td_c" value="0">';
}
?>
<body>
  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="svg-icon-hide">
    <defs>
      <clippath id="hyv_360-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_add-thick-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_add-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_addon-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_align-center-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_align-left-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_align-right-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_arrow-down-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_arrow-up-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_bold-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_browse-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_close-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_delete-outline-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_delete-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_edit-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_fav-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_image-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_italic-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_longarrow-left-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_longarrow-right-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_minus-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_reload-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_save-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_search-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_share-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_text-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_underline-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
      <clippath id="hyv_upload-a">
        <path d="M0 0h60v60H0z" />
      </clippath>
    </defs>
    <symbol id="hyv_360" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_360-a)">
        <path data-name="Path 500"
          d="M6.529 29.293c-10.854 5.957 18.384 9.575 24.973 9.517v-3.633l5.474 5.469-5.474 5.478v-3.4c-13.239.5-44.4-8.024-25.656-15.091a10.884 10.884 0 0 0 .635 1.567l.049.1Zm46.383-11.235a4.014 4.014 0 0 1 .69-2.676 2.689 2.689 0 0 1 2.1-.762 3.243 3.243 0 0 1 1.113.166 2.026 2.026 0 0 1 .708.435 2.29 2.29 0 0 1 .435.562 2.771 2.771 0 0 1 .254.688 6.323 6.323 0 0 1 .186 1.567 4.546 4.546 0 0 1-.62 2.671 2.464 2.464 0 0 1-2.129.845 3.039 3.039 0 0 1-1.367-.269 2.211 2.211 0 0 1-.855-.791 2.98 2.98 0 0 1-.376-1.011 6.8 6.8 0 0 1-.137-1.426Zm-39.986 1.064-4.395-.781a5.526 5.526 0 0 1 2.1-3.218A7.44 7.44 0 0 1 15.04 14c2.183 0 3.755.405 4.731 1.221a3.844 3.844 0 0 1 1.455 3.071 3.438 3.438 0 0 1-.591 1.958 5.008 5.008 0 0 1-1.782 1.538 5.184 5.184 0 0 1 1.48.562 3.617 3.617 0 0 1 1.294 1.35 4.1 4.1 0 0 1 .464 2 5.62 5.62 0 0 1-.762 2.808 5.15 5.15 0 0 1-2.2 2.065 8.417 8.417 0 0 1-3.779.723 9.772 9.772 0 0 1-3.594-.537 5.325 5.325 0 0 1-2.168-1.572 7.2 7.2 0 0 1-1.3-2.6l4.653-.62a3.685 3.685 0 0 0 .85 1.958 2.045 2.045 0 0 0 1.465.547 2.019 2.019 0 0 0 1.553-.684 2.6 2.6 0 0 0 .62-1.826 2.546 2.546 0 0 0-.6-1.8 2.111 2.111 0 0 0-1.621-.64 5.7 5.7 0 0 0-1.5.273l.239-3.325a4.2 4.2 0 0 0 .6.059 2.086 2.086 0 0 0 1.5-.581 1.842 1.842 0 0 0 .605-1.372 1.628 1.628 0 0 0-.45-1.217 1.69 1.69 0 0 0-1.245-.454 1.838 1.838 0 0 0-1.328.493 2.913 2.913 0 0 0-.7 1.724Zm23.77-1.084-4.629.562a2.446 2.446 0 0 0-.605-1.357 1.505 1.505 0 0 0-1.055-.4 1.921 1.921 0 0 0-1.753 1.138 10.275 10.275 0 0 0-.674 3.519 5.9 5.9 0 0 1 1.709-1.25 4.834 4.834 0 0 1 2.031-.405 5.061 5.061 0 0 1 3.784 1.6 5.613 5.613 0 0 1 1.548 4.055 6 6 0 0 1-.781 3.027 5.134 5.134 0 0 1-2.169 2.073 7.673 7.673 0 0 1-3.481.7 8.115 8.115 0 0 1-4.028-.859 6.026 6.026 0 0 1-2.427-2.73 11.488 11.488 0 0 1-.908-4.971q0-4.526 1.909-6.636A6.743 6.743 0 0 1 30.45 14a8.738 8.738 0 0 1 3.152.459 4.919 4.919 0 0 1 1.924 1.348 5.949 5.949 0 0 1 1.167 2.231Zm-8.56 7.462a3.1 3.1 0 0 0 .684 2.129 2.168 2.168 0 0 0 1.68.771 1.968 1.968 0 0 0 1.533-.7 3.059 3.059 0 0 0 .615-2.08 3.226 3.226 0 0 0-.64-2.158 2.027 2.027 0 0 0-1.592-.742 2.119 2.119 0 0 0-1.631.723 2.95 2.95 0 0 0-.649 2.056Zm9.907-2.92q0-4.768 1.719-6.675A6.694 6.694 0 0 1 44.992 14a7.923 7.923 0 0 1 2.773.415 5.123 5.123 0 0 1 1.768 1.085 5.651 5.651 0 0 1 1.079 1.4 7.374 7.374 0 0 1 .635 1.719 16 16 0 0 1 .469 3.906q0 4.556-1.543 6.66T44.865 31.3a7.443 7.443 0 0 1-3.413-.674 5.518 5.518 0 0 1-2.134-1.978 7.467 7.467 0 0 1-.942-2.524 17.556 17.556 0 0 1-.332-3.54Zm4.6.01a11.269 11.269 0 0 0 .566 4.365 1.77 1.77 0 0 0 1.641 1.167 1.724 1.724 0 0 0 1.226-.493 3.08 3.08 0 0 0 .762-1.572 16.305 16.305 0 0 0 .244-3.345 11.626 11.626 0 0 0-.566-4.482 1.814 1.814 0 0 0-1.694-1.147 1.736 1.736 0 0 0-1.67 1.167 12.513 12.513 0 0 0-.508 4.341Zm12.114-4.527a4.539 4.539 0 0 0 .225 1.748.713.713 0 0 0 .659.469.678.678 0 0 0 .493-.2 1.267 1.267 0 0 0 .308-.63 6.748 6.748 0 0 0 .1-1.338 4.7 4.7 0 0 0-.225-1.8.722.722 0 0 0-.679-.459.694.694 0 0 0-.669.469 4.8 4.8 0 0 0-.21 1.738Zm-1.387 10.7c5.156 2.622 3.926 6.343-5.205 8.6a52.388 52.388 0 0 1-8.721 1.46v2.954a58.444 58.444 0 0 0 9.8-1.772c13.731-3.325 13.252-9.263 4.57-12.612a10.933 10.933 0 0 1-.439 1.367Z"
          fill-rule="evenodd" />
      </g>
    </symbol>
    <symbol id="hyv_add-thick" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_add-thick-a)">
        <path d="M24.115 59.642V36.213H0V24.428h24.115V1h11.778v23.429H60v11.785H35.893v23.429Z" />
      </g>
    </symbol>
    <symbol id="hyv_add" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_add-a)">
        <path d="M25.385 60V34.615H0v-9.23h25.385V0h9.23v25.385H60v9.23H34.615V60Z" />
      </g>
    </symbol>
    <symbol id="hyv_addon" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_addon-a)">
        <path
          d="M7 60V44.812c0-3.288 2.5-4.158 4.532-2.118 1 1 1.825 2.305 4.27 2.305 2.717 0 6.2-2.77 6.2-7.5S18.52 30 15.8 30c-2.448 0-3.275 1.31-4.27 2.3C9.5 34.345 7 33.477 7 30.188V15h15.187c3.288 0 4.158-2.5 2.118-4.533C23.31 9.47 22 8.642 22 6.2 22 3.48 24.77 0 29.5 0S37 3.48 37 6.2c0 2.445-1.31 3.273-2.305 4.27-2.04 2.03-1.173 4.53 2.118 4.53H52v45Z" />
      </g>
    </symbol>
    <symbol id="hyv_align-center" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_align-center-a)">
        <path d="M27 0h6v15h24v9H33v12h15v9H33v15h-6V45H12v-9h15V24H3v-9h24Z" />
      </g>
    </symbol>
    <symbol id="hyv_align-left" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_align-left-a)">
        <path d="M6 60H0V0h6Zm54-45H12v9h48ZM42 36H12v9h30Z" />
      </g>
    </symbol>
    <symbol id="hyv_align-right" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_align-right-a)">
        <path d="M54 60h6V0h-6ZM0 15h48v9H0Zm18 21h30v9H18Z" />
      </g>
    </symbol>
    <symbol id="hyv_arrow-down" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_arrow-down-a)">
        <path d="m29.348 49.063 1.9-2.25L60 12.711 56.293 10 29.43 41.852 3.773 10.036 0 12.676Z" fill="#282828" />
      </g>
    </symbol>
    <symbol id="hyv_arrow-up" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_arrow-up-a)">
        <path d="m29.348 10 1.9 2.25 28.754 34.1-3.707 2.711-26.863-31.852L3.776 49.025.003 46.384Z" fill="#282828" />
      </g>
    </symbol>
    <symbol id="hyv_bold" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_bold-a)">
        <path
          d="M43.857 29.1c4.157-2.871 7.071-7.586 7.071-11.957A16.9 16.9 0 0 0 33.786 0H7v60h30.171a16.254 16.254 0 0 0 6.686-30.9Zm-24-18.386h12.857a6.429 6.429 0 1 1 0 12.857H19.857Zm15 38.571h-15V36.429h15a6.429 6.429 0 1 1 0 12.857Z" />
      </g>
    </symbol>
    <symbol id="hyv_browse" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_browse-a)">
        <path data-name="Path 499"
          d="M20.327 58.819a1.161 1.161 0 0 1-1.159-1.159V51.3h24.377a1.144 1.144 0 0 0 1.144-1.144V22.391l4.66 1.382a1.161 1.161 0 0 0 .329.047 1.135 1.135 0 0 0 1.015-.627l2.78-5.484 5.854 2.7a1.175 1.175 0 0 1 .608.671 1.139 1.139 0 0 1-.06.906l-4.246 8.376A1.144 1.144 0 0 1 54.6 31a1.2 1.2 0 0 1-.332-.047l-4.716-1.4V57.66a1.161 1.161 0 0 1-1.159 1.159ZM11.609 46.61a1.164 1.164 0 0 1-1.163-1.159V17.346l-4.713 1.4a1.109 1.109 0 0 1-.332.05 1.149 1.149 0 0 1-1.031-.636L.127 9.784a1.142 1.142 0 0 1-.062-.906 1.16 1.16 0 0 1 .608-.674l9.774-4.512a47.489 47.489 0 0 1 6.343-1.67A1.089 1.089 0 0 1 17.012 2a1.15 1.15 0 0 1 1.122.9 7.7 7.7 0 0 0 7.505 6.013A7.7 7.7 0 0 0 33.144 2.9a1.151 1.151 0 0 1 1.122-.9 1.1 1.1 0 0 1 .225.022 47.15 47.15 0 0 1 6.337 1.667L50.6 8.2a1.166 1.166 0 0 1 .611.674 1.167 1.167 0 0 1-.06.906l-4.246 8.376a1.155 1.155 0 0 1-1.031.636 1.109 1.109 0 0 1-.332-.05l-4.719-1.4v28.109a1.158 1.158 0 0 1-1.156 1.159ZM9.773 23.816a1.215 1.215 0 0 0 .251-.047l.26 1.053Z" />
      </g>
    </symbol>
    <symbol id="hyv_close" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_close-a)">
        <path
          d="M4.785 59.966.443 55.658l25.249-25.452L0 4.306 4.342-.002l.01.01 25.649 25.854L55.658-.001 60 4.306l-25.69 25.9 25.247 25.451-4.342 4.307-.009-.009-25.2-25.406Z" />
      </g>
    </symbol>
    <symbol id="hyv_delete-outline" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_delete-outline-a)">
        <path
          d="M8.25 60V14.58h3.191v42.228h38.4V14.58h3.194V60Zm30.7-11.059V21.388h3.191v27.553Zm-9.9 0V21.388h3.191v27.553Zm-9.907 0V21.388h3.194v27.553ZM1 13.833v-3.191h16.359L20.5 0h19.424l.319 1.172 2.6 9.47h16.194v3.191Zm38.531-3.191-2.048-7.448h-14.6l-2.2 7.448Z" />
      </g>
    </symbol>
    <symbol id="hyv_delete" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_delete-a)">
        <path
          d="M7.875 60V16.783H3v-6.82h13.885L19.827 0H40.9l2.737 9.963h13.708v6.82h-3.7V60Zm6.82-6.82h32.124v-36.4H14.695ZM36.566 9.963 35.7 6.82H24.92l-.93 3.143Zm.057 37.765V21.94h6.82v25.789Zm-9.275 0V21.94h6.82v25.789Zm-9.275 0V21.94h6.82v25.789Z" />
      </g>
    </symbol>
    <symbol id="hyv_edit" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_edit-a)">
        <path
          d="M58.353 1.647a5.629 5.629 0 0 1 0 7.956L55.7 12.254l-7.956-7.956L50.4 1.647a5.629 5.629 0 0 1 7.953 0Zm-39.775 31.82-2.65 10.606 10.606-2.651 26.517-26.516-7.958-7.956ZM45 28.26V52.5H7.5V15h24.242l7.5-7.5H0V60h52.5V20.758Z" />
      </g>
    </symbol>
    <symbol id="hyv_fav" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_fav-a)">
        <path
          d="M5.848 32.824A15.512 15.512 0 0 1 0 20.73 15.955 15.955 0 0 1 16.164 5 16.228 16.228 0 0 1 30 12.625 16.252 16.252 0 0 1 43.855 5 15.948 15.948 0 0 1 60 20.73a15.479 15.479 0 0 1-5.254 11.6h.16L30 54.42Z" />
      </g>
    </symbol>
    <symbol id="hyv_image" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_image-a)">
        <path
          d="M0 58.471V2h60v56.471Zm3.529-3.529h52.942V5.529H3.529ZM30 47.884H7.058l17.207-35.3L35.921 36.5l5.551-10.673 11.47 22.06Z" />
      </g>
    </symbol>
    <symbol id="hyv_italic" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_italic-a)">
        <path d="M21.143 0v12.857h9.471L15.957 47.143H4V60h34.286V47.143h-9.472l14.657-34.286h11.958V0Z" />
      </g>
    </symbol>
    <symbol id="hyv_longarrow-left" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_longarrow-left-a)">
        <path data-name="Path 498"
          d="M22.114 57.403a3.389 3.389 0 0 1-1.174-.9L.859 32.632a3.43 3.43 0 0 1-.523-3.752v-.008a3.4 3.4 0 0 1 .572-.84l20.036-23.81a3.425 3.425 0 0 1 5.244 4.408l-15.4 18.308h45.787a3.425 3.425 0 0 1 0 6.85H10.783l15.4 18.308a3.424 3.424 0 0 1-4.069 5.308Z" />
      </g>
    </symbol>
    <symbol id="hyv_longarrow-right" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_longarrow-right-a)">
        <path
          d="M37.885 57.403a3.39 3.39 0 0 0 1.174-.9l20.085-23.871a3.43 3.43 0 0 0 .523-3.752v-.008a3.4 3.4 0 0 0-.572-.84L39.06 4.222a3.425 3.425 0 0 0-5.244 4.408l15.4 18.308H3.424a3.425 3.425 0 0 0 0 6.85h45.793l-15.4 18.308a3.424 3.424 0 0 0 4.069 5.308Z" />
      </g>
    </symbol>
    <symbol id="hyv_minus" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_minus-a)">
        <path d="M60 24v11.783H0V24Z" />
      </g>
    </symbol>
    <symbol id="hyv_reload" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_reload-a)">
        <path data-name="Path 497"
          d="M30.259 7.488V0l13.494 11.927-13.494 11.92V15.8A17.948 17.948 0 1 0 46.9 27.012l7.818-2.835a26.262 26.262 0 1 1-24.459-16.69Z" />
      </g>
    </symbol>
    <symbol id="hyv_save" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_save-a)">
        <path
          d="M46.667 0H0v60h60V13.333ZM30 53.332a10 10 0 1 1 10-10 9.987 9.987 0 0 1-10 10ZM40 20H6.667V6.667H40Z" />
      </g>
    </symbol>
    <symbol id="hyv_search" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_search-a)">
        <path data-name="Union 2"
          d="M54.608 58.7 41.059 45.474A25 25 0 0 1 25.1 51.2 25.1 25.1 0 0 1 0 26.1 25.1 25.1 0 0 1 25.1 1a25.1 25.1 0 0 1 25.1 25.1 24.994 24.994 0 0 1-4.79 14.754l13.634 13.31a3.181 3.181 0 0 1 .051 4.491 3.175 3.175 0 0 1-2.274.954 3.149 3.149 0 0 1-2.213-.909ZM6.348 26.1A18.771 18.771 0 0 0 25.1 44.855 18.776 18.776 0 0 0 43.855 26.1 18.775 18.775 0 0 0 25.1 7.348 18.77 18.77 0 0 0 6.348 26.1Z" />
      </g>
    </symbol>
    <symbol id="hyv_share" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_share-a)">
        <path
          d="M48.181 42.41a8.771 8.771 0 0 0-5.9 2.319L20.8 32.229a9.86 9.86 0 0 0 .271-2.108 9.86 9.86 0 0 0-.271-2.108l21.235-12.38a9.016 9.016 0 1 0-2.892-6.6 9.86 9.86 0 0 0 .271 2.108L18.181 23.524a9.036 9.036 0 1 0 0 13.193l21.446 12.53a8.5 8.5 0 0 0-.241 1.958 8.8 8.8 0 1 0 8.8-8.8Z" />
      </g>
    </symbol>
    <symbol id="hyv_text" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_text-a)">
        <path
          d="M0 58.471V2h60v56.471Zm3.529-3.529h52.942V5.529H3.529Zm16.076-7.061v-1.02h1.239a5.185 5.185 0 0 0 2.621-.578 2.631 2.631 0 0 0 1.135-1.325 11.288 11.288 0 0 0 .3-3.472V12.644h-2.7q-3.778 0-5.489 1.6a10.582 10.582 0 0 0-3.03 6.369H12.63V10.494h33.584v10.117h-.994a16.005 16.005 0 0 0-1.957-5.031 7.563 7.563 0 0 0-2.951-2.44 9.467 9.467 0 0 0-3.669-.5h-2.782v28.846a11.719 11.719 0 0 0 .318 3.583 2.837 2.837 0 0 0 1.239 1.255 5.018 5.018 0 0 0 2.522.537h1.242v1.02Z" />
      </g>
    </symbol>
    <symbol id="hyv_underline" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_underline-a)">
        <path
          d="M30.333 46.667a20.016 20.016 0 0 0 20-20V0H42v26.667a11.667 11.667 0 1 1-23.333 0V0h-8.334v26.667a20.016 20.016 0 0 0 20 20ZM7 53.333V60h46.667v-6.667Z" />
      </g>
    </symbol>
    <symbol id="hyv_upload" viewbox="0 0 60 60">
      <g clip-path="url(#hyv_upload-a)">
        <path
          d="M48.276 26.132a11.708 11.708 0 0 0-2.253.218 16.987 16.987 0 0 0-33.766-1.934 12.568 12.568 0 0 0 .311 25.132h10.515a1.44 1.44 0 1 0 0-2.879H12.568a9.689 9.689 0 0 1 0-19.378c.262 0 .54.013.848.04l1.459.126.1-1.461a14.107 14.107 0 0 1 28.179.992c0 .358-.017.74-.052 1.169l-.189 2.3 2.147-.838a8.845 8.845 0 1 1 3.216 17.084l-7.767-.015-7.677-.015a1.211 1.211 0 0 1-1.392-1.282V29.848L33 31.703a1.439 1.439 0 0 0 2.2-1.854l-3.462-4.113a2.221 2.221 0 0 0-3.439 0l-3.462 4.113a1.439 1.439 0 1 0 2.2 1.854l1.516-1.8v15.5a4.085 4.085 0 0 0 4.271 4.147l7.671.015 7.773.016a11.724 11.724 0 1 0 0-23.447Z"
          fill="#000002" />
      </g>
    </symbol>
    <symbol id="symbols" viewbox="0 0 0 0">
      <g style="position:absolute" />
    </symbol>
  </svg>
  <main>
    <div style="padding-bottom: 0 !important;" class="hyv-dyo-container has-fixed-total">
      <div class="hyv-dyo">
        <div class="hyv-preview">
          <div class="hyv-preview__container">
               <figure style="justify-content: center;
align-items: center;
display: flex;border: 1px solid white;" class="hyv-preview__figure">

<?php /*
if ( wp_is_mobile() ) { ?>

                <div style="justify-content: center;
display: flex;background: #fff;width: 100% !important;position: absolute;height: 100%;" id="threedoverlay">
                  <img style="width: 200px;margin: auto;" src="<?php echo _3D_CONFIG_URL.'/assets/';?>Settings.gif">

</div>

        <?php }
else
{ ?>
                <div style="justify-content: center;
display: flex;background: #fff;height: 100%;position: absolute;
width: 100%;" id="threedoverlay">
                  <img style="width: 200px;margin: auto;" src="<?php echo _3D_CONFIG_URL.'/assets/';?>Settings.gif">

</div>
  <?php } */
?>
    


<?php 
if ( wp_is_mobile() ) { ?>
 <div id="3d2" class="" style="width: 100% !important">
      
      <div id="3d-product-view" class="3d-product-view" style="width: 100% !important">
                          <div style="justify-content: center;
display: flex;background: #fff;width: 100% !important;position: absolute;height: 100%;" id="threedoverlay">
                  <img style="margin: auto;" src="<?php echo _3D_CONFIG_URL.'/assets/';?>Settings1.gif">

</div>
          
        <?php }
else
{ ?>
           <div id="3d2" class="" style="width: 100% !important; height: 100% !important">
      
      <div id="3d-product-view" class="3d-product-view" style="width: 100% !important; height: 100% !important">
                <div style="justify-content: center;
display: flex;background: #fff;height: 100%;position: absolute;
width: 100%;" id="threedoverlay">
                  <img style="margin: auto;" src="<?php echo _3D_CONFIG_URL.'/assets/';?>Settings1.gif">

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
      
      <canvas id="canvas" width="1024" height="1024"></canvas>
      
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
      
       <canvas id="canvasimg" width="1024" height="1024"></canvas>
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
       <!--      <div class="hyv-preview__btns d-none d-lg-flex">
              <button class="hyv-btn active" type="button">Front</button>
              <button class="hyv-btn" type="button">Back</button>
              <button class="hyv-btn" type="button">Left</button>
              <button class="hyv-btn" type="button">Right</button>
            </div>
 -->
  <!--<label class="hyv-icon hyv-icon--btn hyv-icon--28 hyv-preview__mobiletools__360" aria-label="Edit">-->
  <!--              <svg role="presentation">-->
  <!--                <use xlink:href="#hyv_360"></use>-->
  <!--              </svg>-->
  <!--            </label> -->
            <div class="hyv-preview__mobiletools d-lg-none">
             <!--  <label ata-bs-toggle="modal"
        data-bs-target="#hyvAddteam2" class="hyv-p hyv-preview__mobiletools__create" href="">+Create for Team</label> -->
          <?php if(!isset($_GET['admin'])) { ?>
        <label data-bs-target="#hyvAddteam2" href="#!" class="hyv-p hyv-preview__mobiletools__create" data-bs-toggle="modal">+Create for Team</label>
          <?php } ?>


              <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--18 hyv-preview__mobiletools__back"
                aria-label="Edit">
                <svg role="presentation">
                  <use xlink:href="#hyv_longarrow-left"></use>
                </svg>
              </button>
            <!--   <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--18 hyv-preview__mobiletools__reload"
                aria-label="Edit">
                <svg role="presentation">
                  <use xlink:href="#hyv_reload"></use>
                </svg>
              </button> -->
  <!--<label class="hyv-icon hyv-icon--btn hyv-icon--28 hyv-preview__mobiletools__360"-->
  <!--              aria-label="Edit">-->
  <!--              <svg role="presentation">-->
  <!--                <use xlink:href="#hyv_360"></use>-->
  <!--              </svg>-->
  <!--           </label> -->
              <div class="hyv-preview__mobiletools__menu">
                <button type="button" class="hyv-btn" data-bs-target="#hyvProductListing" data-bs-toggle="modal">
                  <svg class="hyv-icon hyv-icon--16" role="presentation">
                    <use xlink:href="#hyv_browse"></use>
                  </svg>
                  <span class="hyv-tiny">Browse</span>
                </button>
                <button id="sharemydesign"  type="button" class="hyv-btn">
                  <svg class="hyv-icon hyv-icon--16" role="presentation">
                    <use xlink:href="#hyv_share"></use>
                  </svg>
                  <span class="hyv-tiny">Share</span>
                </button>
                 <?php if ( is_user_logged_in() ) { ?>
                 <?php if(!isset($_GET['admin'])) { ?>
                <button type="button" class="hyv-btn" data-bs-toggle="modal"
        data-bs-target="#hyvMydesign">
                  <svg class="hyv-icon hyv-icon--16" role="presentation">
                    <use xlink:href="#hyv_fav"></use>
                  </svg>
                  <span class="hyv-tiny">Drafts</span>
                </button>
                 <?php } } ?>
              </div>
            </div>
          </div>
        </div>
        <!-- hyv-preview -->

        <div class="hyv-details">
          <header class="hyv-details__header">
            <div class="hyv-details__header__left">
    <h1 class="hyv-h1 mt-2"><strong>
                
<?php
$product = wc_get_product( $productID );

echo $product->get_title();
 ?>

              </strong></h1>
            <!--   <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvProductListing">Product Listing</button> -->
              <label data-bs-target="#hyvProductListing" href="#!" class="hyv-lead hyv-link " data-bs-toggle="modal"
        data-bs-target="#hyvProductListing">Change Product</label>
            </div>
            <div class="hyv-details__header__right">
                <?php if(!isset($_GET['admin'])) { ?>
              <button class="hyv-btn hyv-btn--md hyv-btn--outline" data-bs-toggle="modal"
        data-bs-target="#hyvAddteam2" type="button">
                <svg class="hyv-icon hyv-icon--12" role="presentation">
                  <use xlink:href="#hyv_add"></use>
                </svg>
                Create for team
              </button>
              <?php } ?>
        
            </div>
          </header>
          <!-- Header -->

          <div class="hyv-details__body">
            <ul class="nav hyv-tab hyv-tab--dyo" role="tablist">
              <li role="presentation">
                <button class="hyv-tab__link active" id="hyv-color-tab" data-bs-toggle="tab" data-bs-target="#hyv-color"
                  type="button" role="tab" aria-controls="hyv-color" aria-selected="true">
                  <img class="hyv-icon hyv-icon--16" src="<?php echo _3D_CONFIG_URL; ?>assets/images/hyv_color.svg" alt="Choose color icon" />
                  <span class="d-none d-lg-inline">CHOOSE&nbsp;</span>COLOR</button>
              </li>
              <li role="presentation">
                <button class="hyv-tab__link" id="hyv-addons-tab" data-bs-toggle="tab" data-bs-target="#hyv-addons"
                  type="button" role="tab" aria-controls="hyv-addons" aria-selected="false">
                  <svg class="hyv-icon hyv-icon--16" role="presentation">
                    <use xlink:href="#hyv_addon"></use>
                  </svg>
                  ADDONS</button>
              </li>
              <li role="presentation">
                <button class="hyv-tab__link" id="hyv-image-tab" data-bs-toggle="tab" data-bs-target="#hyv-image"
                  type="button" role="tab" aria-controls="hyv-image" aria-selected="false">
                  <svg class="hyv-icon hyv-icon--16" role="presentation">
                    <use xlink:href="#hyv_image"></use>
                  </svg>
                  IMAGE<span class="d-none d-lg-inline">&nbsp;OR LOGO</span></button>
              </li>
              <li role="presentation">
                <button class="hyv-tab__link" id="hyv-text-tab" data-bs-toggle="tab" data-bs-target="#hyv-text"
                  type="button" role="tab" aria-controls="hyv-text" aria-selected="false">
                  <svg class="hyv-icon hyv-icon--16" role="presentation">
                    <use xlink:href="#hyv_text"></use>
                  </svg>
                  ADD TEXT</button>
              </li>
            </ul>

            <div class="tab-content hyv-tab--content">
              <div class="hyv-tab__pane fade show active" id="hyv-color" role="tabpanel"
                aria-labelledby="hyv-color-tab">
                <div class="hyv-color-customize">
                  <div class="hyv-group">
                    <h4 class="hyv-label hyv-w-medium">Choose from presets</h4>
                       <div class="hyv-row">


<!-- <button class="inner_circle1">clickme</button> -->
<style>
/*.outer_circle {*/
/*position: relative;*/
/*margin: 40px;*/
/*width: 5px;*/
/*height: 5px;*/
/*border-radius: 50%;*/
/*}*/

/*.inner_circle {*/

/*content: '';*/
/*position: absolute;*/
/*top: -20px;*/
/*bottom: -20px;*/
/*right: -20px;*/
/*left: -20px;*/
/*z-index: -1;*/
/*border-radius: inherit;*/
/*}*/
.inner_circle {

content: '';

position: absolute;

top: -15px;

bottom: -15px;

right: -15px;

left: -15px;

z-index: -1;

border-radius: inherit;

}



.outer_circle {

position: relative;

margin: 19px;

width: 0px;

height: 0px;

border-radius: 50%;

}
</style>
<?php
// var_dump($preset);

if(!empty($preset)) {
// echo $preset[0][0];
$count = count($preset);  //4


 $data3 = $wpdb->get_results( "SELECT pattern_count FROM {$wpdb->prefix}3dconfig_models WHERE m_id = $m_id" );

  foreach( $data3 as $key3 => $row3) { 
    $countt = $row3->pattern_count;
   }


for($x=0; $x < $count; $x++) {

  //print_r($preset[$x]);

  $string = implode(', ', $preset[$x]); ?>
<a onclick="myColor(this)" class="ps-col hyv-addons-block preset" alt="<?php echo $string ?>">
<div  class="outer_circle">
<div  class="inner_circle" style="background-image: linear-gradient(
to bottom, <?php echo $string ?>);"></div>
</div></a>

<?php

}

}
else
{
  echo '<h1>Preset colors not set</h1>';
}

?>



                      <!-- <button type="button" class="hyv-btn hyv-colorpreset-block">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/color-preset-1.png" alt="Color preset 1" />
                      </button>
                      <button type="button" class="hyv-btn hyv-colorpreset-block">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/color-preset-1.png" alt="Color preset 2" />
                      </button>
                      <button type="button" class="hyv-btn hyv-colorpreset-block">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/color-preset-1.png" alt="Color preset 3" />
                      </button>
                      <button type="button" class="hyv-btn hyv-colorpreset-block">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/color-preset-1.png" alt="Color preset 4" />
                      </button> -->



                    </div>
                    <script>
                  
            
             function myColor(color)
             {
              var lta = color.attributes['alt'].value;

              jQuery('#pset-col').val(lta);

              
             }
                    </script>
                    
                  </div>

                  <div class="hyv-group">
                    <h4 class="hyv-label hyv-w-medium">Make your own combination</h4>
                    <p class="hyv-tiny hyv-mute">Click on each color to change</p>
                     <div class="hyv-color-row">


                      <!-- <input class="hyv-color-block" type="color" name="favcolor1" value="#585C9C"
                        aria-label="Selected Color #585C9C">
                      <input class="hyv-color-block" type="color" name="favcolor2" value="#ECEFF8"
                        aria-label="Selected Color #ECEFF8">
                      <input class="hyv-color-block" type="color" name="favcolor3" value="#3A8454"
                        aria-label="Selected Color #3A8454">
                      <input class="hyv-color-block" type="color" name="favcolor4" value="#DC6B5A"
                        aria-label="Selected Color #DC6B5A"> -->

<?php


if(isset($_GET['unique_id']))
{

  
$pset_col3 = implode(', ', $pset_col2);

foreach ( $pset_col2 as $hex )
{
   ?>
   
 <!--  <div style="background-color: #<?php echo $hex; ?>" class="single-circle"></div> -->
  <input class="hyv-color-block s-color" type="color" name="favcolor1" value="<?php echo preg_replace('/\s+/', '', $hex); ?>"
                        aria-label="Selected Color <?php echo preg_replace('/\s+/', '', $hex); ?>">

<?php  

}

}
else
{

//var_dump($svgfilecol);
$svgfilecol1 = explode(',', $svgfilecol);
//var_dump($svgfilecol);

  foreach ( $svgfilecol1 as $hex)
{ 
   ?>
   
 <!--  <div style="background-color: #<?php echo $hex; ?>" class="single-circle"></div> -->
  <input class="hyv-color-block s-color" type="color" name="favcolor1" value="<?php echo $hex; ?>"
                        aria-label="Selected Color <?php echo $hex; ?>">

<?php 

$colt[] = '#'.$hex;


}

$pset_col3 = $svgfilecol;
}






?>


                      <a class="hyv-link">
                        <svg class="hyv-icon hyv-icon--10 me-1" role="presentation">
                          <use xlink:href="#hyv_reload"></use>
                        </svg>

<?php

if(isset($_GET['unique_id']))
{
  ?>
<span onclick="reSet(this)" alt="<?php echo $pset_col3; ?>" class="hyv-tiny hyv-w-medium reset-col">Reset to default</span>
  <?php

}
else
{ ?>


<span onclick="reSet(this)" alt="<?php echo $pset_col3; ?>" class="hyv-tiny hyv-w-medium reset-col">Reset to default</span>

<?php }

 ?>

                        
                      </a>
                    </div>

<script>

function reSet(data)

{

var alt = data.attributes['alt'].value;

jQuery('#pset-col').val(alt);

var alt = alt.replace(/\s+/g, "");

var alt = alt.split(",");
//console.log(alt[1])
var prei = 0;
var pres = "";
     $('.s-color').each(function() {

                 // console.log(alt[1]);
                  
                  

                 pres = alt[prei];
                 $(this).val(pres); 
                 // console.log($(this).val(pres));
                 prei++;

                 
 

});


 
}




  jQuery('.s-color').change(function(){
    
    var sset = [];
            $('.s-color').each(function() {

                 
                  var scolor = $(this).val();
                  
                  
                  sset.push(scolor);

                 
 

});

jQuery('#s-set').val(sset);

  });
</script>

                  </div>

                  <input type="hidden" id="s-set" name="s-set">

                </div>
              </div>

              <div class="hyv-tab__pane fade" id="hyv-addons" role="tabpanel" aria-labelledby="hyv-addons-tab">
                <div class="hyv-addons-customize">
                  <div class="hyv-accordion hyv-accordion--addon" id="accordionAddons">






<?php 

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

if($assets_default != null) {

$ss = array_flatten($assets_default);

$ass_str = implode(', ', $as_name);



$max =  count($assets_string);
$i = 0;
while($i < $max){    ?>
<?php $alll = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_assets WHERE assets_id = $assets_string[$i]" ); 
foreach ($alll as $currr) { ?>
<?php $assets[] = $currr->assets_type_id; ?>
<?php } ?>
<?php $i++; }

$assets = array_unique($assets);
$k = 0;

$assets = array_values($assets);

$max2 =  count($assets);
$collapse = 0;
while($k < $max2){    ?>
<?php $alll2 = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_assets_type WHERE `assets_type_id` = $assets[$k]" ); 

foreach ($alll2 as $currr2) { ?>

<div class="hyv-accordion__item">
                      <h2 class="hyv-accordion__header" id="headingOne">
                        <button class="hyv-btn hyv-accordion__header__btn" type="button" data-bs-toggle="collapse"
                          data-bs-target="#collapse<?php echo $collapse; ?>" aria-expanded="true" aria-controls="collapseOne">
                          <span class="hyv-p hyv-w-medium">Select <?php echo $currr2->assets_type; ?></span>
                          <svg class="hyv-icon hyv-icon--12 me-2" role="presentation">
                            <use xlink:href="#hyv_arrow-up"></use>
                          </svg>
                        </button>
                      </h2>
                      <div id="collapse<?php echo $collapse; ?>" class="hyv-accordion__collapse collapse show" aria-labelledby="headingOne">
                        <div class="hyv-row">

<?php 
$s = 0;
while($s < $max){  
$az = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_assets WHERE assets_id = $assets_string[$s] AND `assets_type_id` = $assets[$k]" ); 
foreach ($az as $cz) { ?>

   


<?php 


if(isset($_GET['unique_id']))
{
$tshirtStyle = $thr_array['tshirtStyle'];
$ss2 = array_flatten($tshirtStyle);
//var_dump($ss2);
//var_dump($ss);
$ss = array_diff($as_name, $ss2);

}

// print_r($assets_default);
if(in_array($cz->assets_name, $ss)) {

$assets_array[] = $cz->assets_id;
 ?>

  <a onclick="myAssets(this)" id="<?php echo $cz->assets_id; ?>" alt="<?php echo $cz->assets_name; ?>" dir="<?php echo $cz->assets_price; ?>" class="hyv-addons-block active h_asts"><?php


} else { ?>

   <a onclick="myAssets(this)" id="<?php echo $cz->assets_id; ?>" alt="<?php echo $cz->assets_name; ?>" dir="<?php echo $cz->assets_price; ?>" class="hyv-addons-block h_asts"> <?php

} ?>


                  

                              <figure class="hyv-figure hyv-figure--addons">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/img/<?php echo $cz->assets_file; ?>" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <center><span class="hyv-tiny hyv-mute"><?php echo ucwords(str_replace("_", " ",$cz->assets_name)); ?><span class="hyv-tiny hyv-mute"><?php echo wc_price($cz->assets_price); ?></span></span></center>
                          </a> 


<?php } $s++; } ?>
                    
                       

                          
                          


                        </div>
                      </div>
                    </div>

<?php $collapse++; } ?>
<?php $k++; }


?>









                    <!-- accordion item -->

                  <!--   <div class="hyv-accordion__item">
                      <h2 class="hyv-accordion__header" id="headingTwo">
                        <button class="hyv-btn hyv-accordion__header__btn" type="button" data-bs-toggle="collapse"
                          data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                          <span class="hyv-p hyv-w-medium">Select zipper type</span>
                          <svg class="hyv-icon hyv-icon--12 me-2" role="presentation">
                            <use xlink:href="#hyv_arrow-up"></use>
                          </svg>
                        </button>
                      </h2>
                      <div id="collapseTwo" class="hyv-accordion__collapse collapse show" aria-labelledby="headingTwo">
                        <div class="hyv-row">
                          <a class="hyv-addons-block active">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">Quarter - ₹50</span>
                          </a>
                          <a class="hyv-addons-block">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">Full - ₹100</span>
                          </a>
                          <a class="hyv-addons-block">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">No Zipper - ₹0</span>
                          </a>
                        </div>
                      </div>
                    </div> -->
                    <!-- accordion item -->

                   <!--  <div class="hyv-accordion__item">
                      <h2 class="hyv-accordion__header" id="headingThree">
                        <button class="hyv-btn hyv-accordion__header__btn" type="button" data-bs-toggle="collapse"
                          data-bs-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                          <span class="hyv-p hyv-w-medium">Select collar type</span>
                          <svg class="hyv-icon hyv-icon--12 me-2" role="presentation">
                            <use xlink:href="#hyv_arrow-up"></use>
                          </svg>
                        </button>
                      </h2>
                      <div id="collapseThree" class="hyv-accordion__collapse collapse show"
                        aria-labelledby="headingThree">
                        <div class="hyv-row">
                          <a class="hyv-addons-block active">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">Round - ₹0</span>
                          </a>
                          <a class="hyv-addons-block">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">V neck- ₹0</span>
                          </a>
                          <a class="hyv-addons-block">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">Standup - ₹25</span>
                          </a>
                          <a class="hyv-addons-block">
                            <figure class="hyv-addons-figure">
                              <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/zipper-q.png" alt="zipper-q">
                              <figcaption class="visually-hidden">zipper-q</figcaption>
                            </figure>
                            <span class="hyv-tiny hyv-mute">Polo - ₹50</span>
                          </a>
                        </div>
                      </div>
                    </div> -->
                    <!-- accordion item -->
                   <?php } else
                  {
                    $ass_str = null;
                  } ?>
                  </div>


                </div>
              </div>
<input type="hidden" id="hide_assets" name="hide_assets" value="0">
              <script>

                <?php
                  $ass_str = str_replace(' ', '', $ass_str);
                 echo 'var as_str = "'.$ass_str.'"'; ?>
           
                function myAssets(data)
                {



if(!jQuery(data).hasClass( "active" ))

                {
                  
      

                  var quotations = [];

                var alt = data.attributes['alt'].value;
                // var dir = data.attributes['dir'].value;

                jQuery(data).toggleClass("active");



                jQuery(data).siblings().removeClass("active");
              

                 var temp = new Array();
                 y = temp = as_str.split(",");
                $('.h_asts.active').each(function() {

                 
                  var alt = $(this).attr('alt');
                  var id = $(this).attr('id');
                  
                  quotations.push(id);

                  y = $.grep(y, function(value) {
                  return value != alt;
                  });
 

});

                
                jQuery('#hide_assets').val(y);
                jQuery('#addons-data').val(quotations);

                roWreplace();


}
      

                }

                function roWreplace() {

                $('.pricetr').remove();

                 $('.h_asts.active').each(function() {

                var alt = $(this).attr('alt');
                alt = alt.replace(/_/g, ' ');
                alt = alt.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
                });
                var dir = $(this).attr('dir');
                 
                $('#price-bkd').append('<tr class="pricetr"><td scope="row"><span class="hyv-lead">'+alt+'</span></td><td scope="row"><?php echo get_woocommerce_currency_symbol(); ?><span class="hyv-lead">'+dir+'</span></td><td scope="row"><span class="hyv-lead allqty">'+c_ount+'</span></td><td scope="row"><?php echo get_woocommerce_currency_symbol(); ?><span class="hyv-lead sum">'+dir*c_ount+'</span></td></tr>');

              })

              $('#p-qty').val(c_ount);


                window.setTimeout(  
      function() {  
      var totval = 0;    
      $('#price-bkd .sum').each(function() {
    
      totval = (totval + parseInt($(this).html()));

                 });

      // console.log(totval);

    $('.total').html(totval);
      },  
      100
  );

 // console.log(totval);
                

             






                 $('#shirtqty').html(c_ount);

                 var p = $('#defshirtqty').html();

                  $('#shirtprice').html(c_ount*p);

                  }
              </script>

              <div class="hyv-tab__pane fade" id="hyv-image" role="tabpanel" aria-labelledby="hyv-image-tab">
                <div class="hyv-image-customize">

                  <div class="hyv-group">
                    <h4 class="hyv-label hyv-w-medium">Upload an image or logo</h4>
                    <p class="hyv-tiny hyv-mute">Use previously uploaded</p>
                   <div id="img-up" class="hyv-row hyv-logo-container">
                      <?php
                    if(isset($_GET['unique_id'])) {

                    if($thr_array['TCimages'])
                    {
                      $imgthree = $thr_array['TCimages'];

                       foreach($imgthree as $row)
                      { ?>
                     <div style="display:flex"><a href="javascript:void(0);" class="hyv-logo-block mt-2"><img src="<?php echo $row['imageUrl']; ?>" alt="ASC logo 1" /></a><a alt="<?php echo $row['imageUrl']; ?>" class="updelimgcl" onclick="updelimg(this)" ><img style="width:20px;height:20px;" src="<?php  echo _3D_CONFIG_URL.'/assets/x-icon.png'; ?>"></a></div>
                   <?php } }
                  } ?>
                  
                      <!-- <a href="javascript:void(0);" class="hyv-logo-block mt-2">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/asc-logo.gjpg" alt="ASC logo 1" />
                      </a> -->
                      <!-- <a href="javascript:void(0);" class="hyv-logo-block mt-2">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/usa-logo.jpg" alt="ASC logo 2" />
                      </a>
                      <a href="javascript:void(0);" class="hyv-logo-block mt-2">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/asc-logo.jpg" alt="ASC logo 3" />
                      </a>
                      <a href="javascript:void(0);" class="hyv-logo-block mt-2">
                        <img src="<?php echo _3D_CONFIG_URL; ?>assets/images/usa-logo.jpg" alt="ASC logo 4" />
                      </a> -->
                    </div>


                               <div class="hyv-upload">
                      <button class="hyv-btn">
                        <svg class="hyv-icon hyv-icon--24 me-2" role="presentation">
                          <use xlink:href="#hyv_upload"></use>
                        </svg>
                        <label class="hyv-p hyv-w-medium" for="hyvLogoupload">Upload file (svg, png, jpg etc)</label>
                      </button>
                   <form method="post" action="" enctype="multipart/form-data" id="myform">
                      <input type="file" id="hyvLogoupload" accept="image/*" name="hyvLogoupload" />
                    </form>
                    <input type="hidden" value="0" name="uploadflag" id="uploadflag">
                    </div>

                    <script type="text/javascript">
    jQuery(document).on('click', '#hyvLogoupload', function() {

 if($("#flexCheckDefault").prop('checked') == false){
 alert("Accept Terms & Condition");

$("#hyvLogoupload").val('');
return false;
  }


});                
                    
  $(document).ready(function(){

   $("#hyvLogoupload").change(function (){




 if($("#flexCheckDefault").prop('checked') == true){
     
    // console.log("logged");

//  jQuery('#threedoverlay').show();

jQuery('#blackOverlay').show();
 

        var fd = new FormData();
        var files = $('#hyvLogoupload')[0].files;
        
        // Check file selected or not
        if(files.length > 0 ){
           fd.append('file',files[0]);
           // console.log(fd);

           $.ajax({ 
              url: '<?php  echo _3D_CONFIG_URL.'/form.php'; ?>',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){
                 if(response != 0){
                    // $("#img").attr("src",response); 
                    // $("#img").show(); // Display image element

                    $('#img-up').append('<div style="display:flex"><a href="javascript:void(0);" class="hyv-logo-block mt-2"><img src="'+response+'" alt="ASC logo 1" /></a><a alt="'+response+'" class="updelimgcl" onclick="updelimg(this)" ><img style="width:20px;height:20px;" src="<?php  echo _3D_CONFIG_URL.'/assets/x-icon.png'; ?>"></a></div>');
                     $("#hyvLogoupload").val(''); 
                 
                     //localStorage.setItem("uploadimage", response);

                    jQuery('#uploadflag').val(response);

                     $('#uploadflag').click();
                  //   jQuery('#threedoverlay').hide();
                  
                   jQuery('#blackOverlay').hide();




                    

                 } else {
                      alert("File extension not supported!, please upload image");
                      
                      $("#hyvLogoupload").val('');
                      jQuery('#blackOverlay').hide();
                 }
              },
           });
        }
        }

else { alert("Accept Terms & Condition"); 
$("#hyvLogoupload").val('');  }

    });
});
 function updelimg(data)
  {
    var s = data.attributes['alt'].value;
    jQuery('#updelimg00').val(s);
    // data.closest("a").remove();
    data.previousElementSibling.remove();
    data.remove();
    //console.log(data);

    
    

  }
</script>

  <input type="hidden" id="updelimg00" name="" value="">
                    <div class="form-check hyv-check hyv-check--sm hyv-mt-3">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                      <label class="form-check-label" style="margin-left: 14px !important;" for="flexCheckDefault">Please read and agree to the <a
                          class="hyv-link" href="">copyright terms.</a></label>
                    </div>
                  </div>
                  <!-- hyv-group -->

                  <div class="hyv-group">
                    <h4 class="hyv-label hyv-w-medium">Use cliparts</h4>
                    <div class="hyv-filter my-3">
                         <div style="display: flex;" id="slectedCp"></div>
                      <div class="hyv-filter__right">
                        <div class="dropdown">
                          <button class="hyv-btn hyv-btn--dropdown" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>Category: <span id="cpart" class="hyv-w-medium">All cliparts</span></span>
                            <svg class="hyv-icon hyv-icon--8 ms-1" role="presentation">
                              <use xlink:href="#hyv_arrow-down"></use>
                            </svg>
                          </button>
                          <div style="height: 200px;
overflow: hidden scroll;" class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">

                            <?php $cliparts_cat= $wpdb->get_results( "SELECT DISTINCT * FROM {$wpdb->prefix}3dconfig_cliparts_category" ); ?>
                                <?php $cliparts= $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_cliparts" ); ?>
                                <a onclick="clip(this)" class="dropdown-item" alt="0" value="0" href="#">All cliparts</a>
                           <?php  foreach( $cliparts_cat as $key => $row) { ?>

                             <a onclick="clip(this)" class="dropdown-item" alt="<?php echo $row->id; ?>" value="<?php echo $row->id; ?>" href="#"><?php echo $row->clipart_category; ?></a>

                              <?php } ?>


                          </div>
                        </div>
                      </div>
                    </div>
                  <div id="clipdata2" class="hyv-clipart-slider">
                      <div id="clipdata" class="hyv-clipart-slider__list">

<?php

  
    foreach( $cliparts as $key => $row) { ?>

 <button  class="hyv-clipart-block hyv-btn"><img class="vv" onclick="myClip(this.src)" src="<?php echo _3D_CONFIG_URL; ?>assets/cliparts/<?php echo $row->clipart_file; ?>" alt="ASC logo 4" /></button>



  <?php }
 ?>
                       
                       



                      </div>


<script>


function clip(id) {
    var conceptName = id.innerHTML;
    jQuery("#cpart").html(conceptName);
    var clip = id.attributes['alt'].value;
  var xmlhttp9=new XMLHttpRequest();
  xmlhttp9.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
               
               if(this.responseText == "")
               {
                document.getElementById("clipdata2").innerHTML="No Cliparts in this Category<div id=\"clipdata\" class=\"hyv-clipart-slider__list\"></div>";
               }else
               {
                document.getElementById("clipdata2").innerHTML="<div id=\"clipdata\" class=\"hyv-clipart-slider__list\">"+this.responseText+"</div>";
               }

    }
  }
  xmlhttp9.open("GET","<?php echo _3D_CONFIG_URL ?>form.php?clip_id="+clip);
  xmlhttp9.send();

}

//   jQuery(".bbclass").click(function(){
//     debugger
// alert(1);
//    var imgsrc = jQuery('.bbclass1').attr('src');
//                 alert(imgsrc);
// });

function myClip(value) {

let mathrand = Math.floor((Math.random() * 100) + 1);
document.getElementById('clipart-url').value = value+'?'+mathrand;

$('#slectedCp').append('<div style="display:flex"><a href="javascript:void(0);" class="hyv-logo-block mt-2"><img src="'+value+'" alt="ASC logo 1" /></a><a alt="'+document.getElementById('clipart-url').value+'" class="updelimgcl" onclick="updelimg(this)" ><img style="width:20px;height:20px;" src="<?php  echo _3D_CONFIG_URL.'/assets/x-icon.png'; ?>"></a></div>');
}

</script>

                    </div>
                  </div>
                  <input type="hidden" id="clipart-url" value="" name="clipart-url">
       

<p><input type="hidden" placeholder="Type something..." id="myInput"></p>




                </div>
              </div>
              <style>
                .actv 
                {
                  border: 2px solid black;
                }
              </style>
              <script>
                document.addEventListener('mouseup', function(e) {
    var container = document.getElementById('dropdown-menu1');
         jQuery('#textStyleButton4').hide();
    if (!container.contains(e.target)) {
        container.style.display = 'none';
    }
});
              </script>
              <div class="hyv-tab__pane fade" id="hyv-text" role="tabpanel" aria-labelledby="hyv-text-tab">
                <div class="hyv-text-customize">
                  <div class="dropdown hyv-text-customize__dropdown">
                    

                     <button class="hyv-btn hyv-btn--md hyv-btn--outline" type="button" id="addContentMenuButton"
                      data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                      <svg class="hyv-icon hyv-icon--8" role="presentation">
                        <use xlink:href="#hyv_add"></use>
                      </svg>
                      Add your name, number or any text
                    </button>
                    <div class="dropdown-menu hyv-dropdown-menu mt-2" id="dropdown-menu1" aria-labelledby="addContentMenuButton">
                      <div class="hyv-dropdown-menu__addtext">
                        <div class="hyv-form-group">
                          <label class="hyv-label" for="hyvEnterName">Enter your name, number or anything</label>
                          <input autocomplete="off" list="autocompleteOff" type="text" class="form-control hyv-form-control" id="hyvEnterName"
                            placeholder="eg: HELLO">
                        </div>

                 

                        <div class="d-flex align-items-center">
                          <div class="hyv-form-group flex-fill">
                            <label class="hyv-label" for="customRange">Size of the text</label>
                            <div class="hyv-form-range">
                              <input autocomplete="off" id="txt_size" min="0" max="500" type="range" value="12" class="hyv-form-range__input" id="customRange">
                              <span class="hyv-p txt_size_range">12pt</span>
                            </div>
                          </div>
                          <script>
                            jQuery('#txt_size').change(function(){
                 jQuery('.txt_size_range').html(jQuery(this).val()+"pt")
               });
                          </script>
                          <div class="hyv-form-group ms-4">
                            <label class="hyv-label" for="hyvTextSize">Color</label>
                            <input id="colorPicker" class="hyv-color-block hyv-color-block--rangepreview" type="color" name="favcolor1"
                              value="#fbfbfb" aria-label="Selected Color #fbfbfb">
                          </div>
                        </div>
                        <input autocomplete="off" type="hidden" id="txt_angle" name="txt_angle">
                        <!--<div class="hyv-form-group">-->
                        <!--  <label class="hyv-label" for="hyvTextSize">Style of the text</label>-->
                        <!--  <select class="hyv-form-select form-select" id="selection-box" aria-label="Default select example">-->
                          <!--   <option selected="Arial" value="Arial">Open this select menu</option> -->
                        <!--   <option value="Arial">Arial</option>-->
                        <!--    <option value="Source Sans Pro">Source Sans Pro</option>-->
                        <!--    <option value="Roboto">Roboto</option>-->
                            
                        <!--  </select>-->
                        <!--</div>-->
                        
                        
                        <div class="hyv-form-group">
                          <label class="hyv-label" for="hyvTextSize">Style of the text</label>
                          <!-- <select class="hyv-form-select form-select" aria-label="Default select example">
                            <option selected="">Open this select menu</option>
                            <option value="1">Source Sans Pro</option>
                            <option value="2">Roboto</option>
                          </select> -->
                          <div class="dropdown">
                            <button class="hyv-form-select form-select" type="button" id="textStyleButton3" data-bs-toggle="dropdown" aria-expanded="false">
                             <label style="">Arial</label>
                            </button>
                            <ul style="display: none;overflow-y: scroll;height: 200px;overflow-x: hidden;" id="textStyleButton4" class="dropdown-menu" aria-labelledby="textStyleButton3">
                             <li><a onclick="selectFont(this)" alt="Arial" class="dropdown-item" href="#" style="font-family: 'Arial';">Arial</a></li>
                              <li><a onclick="selectFont(this)" alt="Maximum Impact" class="dropdown-item" href="#" style="font-family: 'Maximum Impact';">Maximum Impact</a></li>
                              <li><a onclick="selectFont(this)" alt="Amavos" class="dropdown-item" href="#" style="font-family: 'Amavos';">Amavos</a></li>
                               <li><a onclick="selectFont(this)" alt="Betinya Sans" class="dropdown-item" href="#" style="font-family: 'Betinya Sans';">Betinya Sans</a></li>
                                <li><a onclick="selectFont(this)" alt="Schoolbell" class="dropdown-item" href="#" style="font-family: 'Schoolbell';">Schoolbell</a></li>
                                <li><a onclick="selectFont(this)" alt="INFECTED" class="dropdown-item" href="#" style="font-family: 'INFECTED';">INFECTED</a></li>
                                
                                 <?php
                               $fontsarr = ["Isometric","Isometric Black","AgreementSignature","AlloyInk","AngkanyaSebelas","AnnyeongHaseyo","ArigatouGozaimasu","AwalRamadhan","BauhausSketch","BebasNeue","Blaxie","BlenderPro","Bands","Cloister","Emilio","EnglishGothic","Excluded","Facon","FargoFaroNf","Fortzilla","Frankfurt","Freshman","Graduate","Hacked","Halberdia","Jerseyletters","Letterblocks","Loja","Mechanical","MonbijouxClownpiece","Moonhouse","Ontel","Print","ProcrastinatingPixie","Redpixel","RemainsZombie","Royal","Satella","SenjaSantuy","SketchMe","Sportsball","StarJedi","Staubach","SundiaryDemo","TeamMvp","Teamspirit","Timeburner","Typewriter","Vasta","WagnerModern","Wolski","ZukaDoodle"];
                               foreach($fontsarr as $font)
                               { ?>

                              <li><a onclick="selectFont(this)" alt="<?php echo $font; ?>" class="dropdown-item" href="#" style="font-family: '<?php echo $font; ?>';"><?php echo $font; ?></a></li>

                            <?php } ?>

                            </ul>
                          </div>
                        </div>
                    <input type="hidden" id="selection-box" value="Arial" name="">
                        <script>
                          jQuery('#textStyleButton3').click(function(){
                            
                            jQuery('#textStyleButton4').show();

                          });
                     

                           function selectFont(font)
                           {
                            let fontname = font.attributes['alt'].value;
                            jQuery('#selection-box').val(fontname);
                           jQuery('#textStyleButton4').hide();
                           jQuery('#textStyleButton3').html(' <label style="font-family: '+fontname+'">'+fontname+'</label>')
                           }
                        </script>
                        
                        

                        <div class="hyv-form-group d-flex flex-row">
                          <div style="display:none" class="hyv-format-icons">
                            <button  id="align-left" type="button" class="hyv-icon hyv-icon--btn hyv-icon--18 me-3" aria-label="Edit">
                              <svg role="presentation">
                                <use xlink:href="#hyv_align-left"></use>
                              </svg>
                            </button>
                            <button id="align-center" type="button" class="hyv-icon hyv-icon--btn hyv-icon--18 me-3" aria-label="Edit">
                              <svg role="presentation">
                                <use xlink:href="#hyv_align-center"></use>
                              </svg>
                            </button>
                            <button id="align-right" type="button" class="hyv-icon hyv-icon--btn hyv-icon--18" aria-label="Edit">
                              <svg role="presentation">
                                <use xlink:href="#hyv_align-right"></use>
                              </svg>
                            </button>
                            <input type="hidden" id="align-value" value="" name="">
                            <script>

                              $("#align-left").click(function(){
  
                                    if(jQuery(this).hasClass( "actv" )) 
                                    {

                                      

                                      $("#align-value").val('left');

                                    }
                                    else
                                    {
                                      $("#align-value").val('left');
                                      jQuery(this).toggleClass("actv");
                                      jQuery(this).siblings().removeClass("actv");
                                      

                                    }
});
                              $("#align-center").click(function(){
  
                                    if(jQuery(this).hasClass( "actv" )) 
                                    {

                                       

                                     $("#align-value").val('center');

                                    }
                                    else
                                    {
                                      $("#align-value").val('center');
                                      jQuery(this).toggleClass("actv");
                                      jQuery(this).siblings().removeClass("actv");
                                      

                                    }
});
                              $("#align-right").click(function(){
  
                                     if(jQuery(this).hasClass( "actv" )) 
                                    {

                                       

                                     $("#align-value").val('right');

                                    }
                                    else
                                    {
                                      $("#align-value").val('right');
                                      jQuery(this).toggleClass("actv");
                                      jQuery(this).siblings().removeClass("actv");
                                      

                                    }
});
                                  
                                </script>
                          </div>
                          <div class="hyv-format-icons">
                            <button type="button" id="bold-button" class="hyv-icon hyv-icon--btn hyv-icon--18 me-3" aria-label="Edit">
                              <svg role="presentation">
                                <use xlink:href="#hyv_bold"></use>
                                <input value="" type="hidden" id="bold" name="">
                                <script>
                                  $("#bold-button").click(function(){

                                    if(jQuery(this).hasClass( "actv" )) 
                                    {

                                       jQuery(this).toggleClass("actv");

                                       $("#bold").val('');

                                    }
                                    else
                                    {
                                      $("#bold").val('bold');
                                      jQuery(this).toggleClass("actv");
                                      

                                    }
  
}); 
                                </script>
                              </svg>
                            </button>
                            <button type="button" id="underline-button" class="hyv-icon hyv-icon--btn hyv-icon--18 me-3" aria-label="Edit">
                              <svg role="presentation">
                                <use xlink:href="#hyv_underline"></use>
                                <input type="hidden" name="" id="underline" value="">
                                <script>
                                  $("#underline-button").click(function(){

                                      if(jQuery(this).hasClass( "actv" )) 
                                    {

                                       jQuery(this).toggleClass("actv");

                                       $("#underline").val('false');

                                    }
                                    else
                                    {
                                      $("#underline").val('true');
                                      jQuery(this).toggleClass("actv");
                                      

                                    }
  
}); 
                                </script>
                              </svg>
                            </button>
                            <button type="button" id="italic-button" class="hyv-icon hyv-icon--btn hyv-icon--18" aria-label="Edit">
                              <svg role="presentation">
                                <use xlink:href="#hyv_italic"></use>
                                <input type="hidden" name="" value="" id="italic">
                                <script>
                                  $("#italic-button").click(function(){

                                       if(jQuery(this).hasClass( "actv" )) 
                                    {

                                       jQuery(this).toggleClass("actv");

                                       $("#italic").val('');

                                    }
                                    else
                                    {
                                      $("#italic").val('italic');
                                      jQuery(this).toggleClass("actv");
                                      

                                    }
  
}); 
                                </script>
                              </svg>
                            </button>
                          </div>
                        </div>

                        <div class="hyv-form-footer">
                          <button id="text_upload" class="hyv-btn hyv-btn--lg hyv-btn--fill" type="button">Save edit</button>
                        </div>

                                 <script>
//      let hello = 0;
      // jQuery("#text_upload").click(function (event) { 

// document.getElementById("config_assets").innerHTML
// if(hello == 0)
// {
//         $('#extra-team').html(''); 
//  hello++;
//  // alert(hello);
//       }





 // alert(txt);

   
// }); 

$("#addContentMenuButton").click(function () {
      jQuery('#hyvEnterName').val('');
      });

if(document.getElementById('td_c').value != 0)
{
var tb = document.getElementById('td_c').value;

} else
{ 

  var tb = 1;
}
 $("#addContentMenuButton").click(function () {
 //$(".dropdown-menu").addClass("show");
 $("#dropdown-menu1").show();
 $("#dropdown-menu1").attr("style","");
  });


let hello = 0;

     $("#text_upload").click(function () { 

if(document.getElementById('hyvEnterName').value != "") {
    
if(jQuery('#customsize').length) {
 jQuery('#customsize').hide();

}
if(jQuery('.defsize').length) {
 jQuery('.defsize').hide();

}
jQuery('#addmembersbtn').show();
jQuery('#addmembersnote').hide();



      $(".dropdown-menu").removeClass("show");

// alert(document.getElementById('hyvEnterName').value);
var font = document.getElementById('selection-box').value;
var txt = document.getElementById('hyvEnterName').value;
var ccolr = document.getElementById('colorPicker').value;
var txt_size = document.getElementById('txt_size').value;

 var bold = $("#bold").val();

 if(bold == "")
 {
  bold = 0;
 }
 else
 {
  bold = 1;
 }

var un = $("#underline").val();

if (un == "") { un = "false"; } if(un == 1) { un = "true";  }
var it = $("#italic").val();
 if(it == "")
 {
  it = 0;
 }
 else
 {
  it = 1;
 }





 // alert(txt);

if(hello == 0)
{
        // $('#innertd').html("");  
        $('.rmtd').remove();
        $('.rmth').remove();
 hello++;
 // alert(hello);
      }
if(document.getElementById('edit44').value != 1) {
      var textID = Math.random().toString(36).substr(2, 9);
document.getElementById('textid').value = textID;

       $('.innertd').before('<td alt="'+textID+'"><input name="text" id="text" value="'+txt+'" type=\"text\" class=\"form-control hyv-form-control hyv-form-control--text\" title=\"Size1\"></td>'); 
       // $('#innertd').before('<td><input name="text'+tb+'" id="text'+tb+'" value="'+txt+'" type=\"text\" class=\"form-control hyv-form-control hyv-form-control--text\" title=\"Size1\"></td>'); 
       $('#innerth').before('<th scope=\"col\" class=\"hyv-table-th-text\">Text '+tb+'</th>'); 
       tb++;



} else {
  var old = document.getElementById('edval33').value;
  var tableBody = jQuery('#tbl').find("tbody");
   var   trLast = tableBody.find("td");
// console.log(trLast);
            trLast.each(function() {

                 
                   //var scolor = $(this);
                   
                  var alt = $(this).attr('alt');
                  if(old == alt)
                  {
                    // console.log(old);
                    // console.log(txt);
                 
                    //$(this).attr('alt') = txt;
                     $(this).attr('alt', old);
                     $(this).html('<td alt="'+old+'"><input name="text" id="text" value="'+txt+'" type=\"text\" class=\"form-control hyv-form-control hyv-form-control--text\" title=\"Size1\"></td>');
                  }
                  
                  // sset.push(scolor);

                 
 

});
  document.getElementById('edit44').value = 0;
}
       jQuery('#td-count').val(tb);

       if(tb > 4)
       {
        // $('.hyv-sticky-fade').show();
          $(".hyv-table-scroll").removeClass("hyv-table-scroll--hidden");

       }
       $('.innertd').hide();
       $('#innerth').hide();
       


if(document.getElementById('edval22').value == 0) {
    // var textID = Math.random().toString(36).substr(2, 9);
    // document.getElementById('textid').value = textID;
jQuery("#extra-team").append("<li  class=\"hyv-text-list__item\">                         <span class=\"addtxt hyv-p hyv-w-medium me-3\">"+txt+"</span>                         <button type=\"button\" onclick=\'storeVar2(this)' font=\""+font+"\" color=\""+ccolr+"\" bold=\""+bold+"\" un=\""+un+"\" it=\""+it+"\" txtsize=\""+txt_size+"\" value=\""+txt+"\" textid=\""+textID+"\"  class=\"hyv-icon ed_text1 hyv-icon--btn hyv-icon--10 me-1\" aria-label=\"Edit\">                           <svg role=\"presentation\">                             <use xlink:href=\"#hyv_edit\"></use>                           </svg>                         </button>                         <button type=\"button\"   onclick=\'storeVar1(this)'   value=\""+txt+"\"  textid=\""+textID+"\"   class=\"hyv-icon del_text1 hyv-icon--btn hyv-icon--10\" aria-label=\"Delete\">                           <svg role=\"presentation\">                             <use xlink:href=\"#hyv_delete\"></use>                           </svg>                         </button>                       </li>");
} else {


// var old = document.getElementById('edval33').value;
var old = document.getElementById('textid').value;
// console.log(elems.value);

var slides = document.getElementsByClassName("ed_text1");
for (var i = 0; i < slides.length; i++) {
   // console.log(slides.item(i));
//if(old == slides.item(i).value) {
if(old == slides.item(i).attributes['textid'].value) {

slides.item(i).value = txt;
slides.item(i).attributes['color'].value = ccolr;
slides.item(i).attributes['bold'].value = bold;
slides.item(i).attributes['un'].value = un;
slides.item(i).attributes['it'].value = it;
slides.item(i).attributes['txtsize'].value = txt_size;
slides.item(i).attributes['font'].value = font;
slides.item(i).previousElementSibling.innerHTML = txt;

}

}

var slides = document.getElementsByClassName("del_text1");
for (var i = 0; i < slides.length; i++) {
   // console.log(slides.item(i));
if(old == slides.item(i).value) {

slides.item(i).value = txt;


}

}

}
$("#save_team").click();

} else 
{
  alert("Field can't empty");
}

 }); 


    </script>
                      </div>
                    </div>





                  </div>
                  <!-- dropdown -->
                  <div class="hyv-text-customize-preview">
                    <p class="hyv-tiny hyv-mute mb-3">Texts you have added</p>
                    <ul id="extra-team" class="hyv-text-list list-unstyled">



                    <?php
                    if(isset($_GET['unique_id'])) {

                    if($thr_array['TCtexts'])
                    {
                      $txtthree = $thr_array['TCtexts'];

                      
                      foreach($txtthree as $row)
                      { ?>

                        <li class="hyv-text-list__item"><span class="addtxt hyv-p hyv-w-medium me-3"><?php echo $row['text']; ?></span><button type="button" onclick="storeVar2(this)" value="<?php echo $row['text']; ?>"  txtsize="<?php echo $row['size']; ?>" it="<?php if($row['italics'] == "") { echo "0"; } else { echo "1"; } ?>" un="<?php if($row['underlined'] == true) { echo "true"; } else { echo "false"; } ?>" bold="<?php if(isset($row['bold'])) { if($row['bold'] == "") { echo "0"; } else { echo "1"; } } ?>" color="<?php echo $row['color']; ?>" font="<?php echo $row['font']; ?>" textid="<?php echo $row['id']; ?>" class="hyv-icon ed_text1 hyv-icon--btn hyv-icon--10 me-1" aria-label="Edit"><svg role="presentation"><use xlink:href="#hyv_edit"></use></svg></button><button type="button" onclick="storeVar1(this)" value="<?php echo $row['text']; ?>" textid="<?php echo $row['id']; ?>" class="hyv-icon del_text1 hyv-icon--btn hyv-icon--10" aria-label="Delete"><svg role="presentation"><use xlink:href="#hyv_delete"></use></svg></button></li>

                    <?php  }
                    } }
                    ?>
                     


                      
                <!--    <li class="hyv-text-list__item">
                        <span class="hyv-p hyv-w-medium me-3">7</span>
                        <button  type="button" class="hyv-icon hyv-icon--btn hyv-icon--10 me-1" aria-label="Edit">
                          <svg role="presentation">
                            <use xlink:href="#hyv_edit"></use>
                          </svg>
                        </button>
                        <button type="button" class="del_text1 hyv-icon hyv-icon--btn hyv-icon--10" aria-label="Delete">
                          <svg role="presentation">
                            <use xlink:href="#hyv_delete"></use>
                          </svg>
                        </button>
                      </li>  --><!--
                      <li class="hyv-text-list__item">
                        <span class="hyv-p hyv-w-medium me-3">Hyve sports</span>
                        <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--10 me-1" aria-label="Edit">
                          <svg role="presentation">
                            <use xlink:href="#hyv_edit"></use>
                          </svg>
                        </button>
                        <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--10" aria-label="Delete">
                          <svg role="presentation">
                            <use xlink:href="#hyv_delete"></use>
                          </svg>
                        </button>
                      </li> -->
                    </ul>
                  </div>
                  <input type="hidden" value="0" name="" id="delval22">
                  <input type="hidden" value="0" name="" id="edval22">
                  <input type="hidden" value="0" name="" id="edval33">
                  <input type="hidden" value="0" name="" id="edit44">
                  <input type="hidden" value="0" name="" id="textid">
                  <input type="hidden" value="0" name="" id="deltextid">
                </div>
                <!-- hyv-add-text -->


<script>
  function storeVar1(text)
  {
    jQuery('#td-count').val(jQuery('#td-count').val() - 1);
    document.getElementById('delval22').value = text.value;
    document.getElementById('deltextid').value = text.attributes['textid'].value;
 
text.parentElement.remove();

var tableBody = jQuery('#tbl').find("tbody");
 var   trLast = tableBody.find("td");
// console.log(trLast);
            trLast.each(function() {

                 
                   //var scolor = $(this);
                   
                  var alt = $(this).attr('alt');
                  if(text.attributes['textid'].value == alt)
                  {
                    $(this).remove();
                    
                  }
                  
                  // sset.push(scolor);

                 
 

});
            var t = 0;
            var u = 0
var tableHead = jQuery('#tbl').find("thead");
      thlast = tableHead.find("th");
      
   thlast.each(function() {
t++;
   })
   t = t - 2;

      thlast.each(function() {
u++;
        if(u == t)
        {
          $(this).remove();
          tb--;
        }



   })
     // thlast.remove();

$("#save_team").click();

console.log(c_ount);
if(jQuery('#td-count').val() == 1)
{

 if(jQuery('#customsize').length) {
  jQuery('#customsize').show();
 }
 if(jQuery('.defsize').length) {
  jQuery('.defsize').show();
 }
 
  // jQuery('#p-qty').val(1);
  c_ount = "1";
  $('#innerth').before('<th scope="col" class="rmth hyv-table-th-text">Text 1</th><th scope="col" class="rmth hyv-table-th-text">Text 2</th><th scope="col" class="rmth hyv-table-th-text">Text 3</th><th scope="col" class="rmth hyv-table-th-text">Text 4</th>'); 

  var $tableBody = $('#tbl').find("tbody");
    $trLast = $tableBody.find("tr:gt(0)").remove();
      jQuery('.innertd').before('<td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size1"></td><td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size2"></td><td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size"></td> <td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size"></td>');
  roWreplace();
  hello = "0";
  jQuery('#addmembersbtn').hide();
  jQuery('#addmembersnote').show();


}

  }
  function storeVar2(text)
  {
    document.getElementById('edval22').value = text.value;
    document.getElementById('edval33').value = text.attributes['textid'].value;
    document.getElementById('textid').value = text.attributes['textid'].value;

   $(".dropdown-menu").addClass("show");
   $("#dropdown-menu1").show();
   $("#dropdown-menu1").attr("style","");


  document.getElementById('hyvEnterName').value = text.value;
  
  document.getElementById('colorPicker').value = text.attributes['color'].value;

  // document.getElementById('txt_size').value = text.attributes['txtsize'].value;
    var fontname = text.attributes['font'].value;

  jQuery('#textStyleButton3').html(' <label style="font-family: '+fontname+'">'+fontname+'</label>');

  // jQuery('.txt_size_range').html(text.attributes['txtsize'].value+"pt")

  if(text.attributes['bold'].value == 0)
  {
    jQuery('#bold-button').removeClass("actv");
    jQuery('#bold').val('');
  }
  else
  {
    jQuery('#bold-button').addClass("actv");
    jQuery('#bold').val('bold');
  }
  if(text.attributes['un'].value == "false")
  {
jQuery('#underline-button').removeClass("actv");
 $("#underline").val('false');
  }
  else
  {
    
    jQuery('#underline-button').addClass("actv");
     $("#underline").val('true');
  }
// text.parentElement.remove();
 if(text.attributes['it'].value == 0)
  {
    jQuery('#italic-button').removeClass("actv");
    jQuery('#italic').val('');
  }
  else
  {
    jQuery('#italic-button').addClass("actv");
    jQuery('#italic').val('italic');
  }

document.getElementById('edit44').value = 1;
  }

  // ed_text1
   
//    $(".del_text1").click(function (event) { 

// alert(1);



//   });

 

//    if(document.getElementById('del_text1').clicked == true)
// {
//    alert("button was clicked");
// }
</script>




              </div>
            </div>
<style>
  .addtxt {
    margin-right: 4rem !important;
  }
</style>












            <!-- hyv-tab -->
            <hr class="hyv-hr d-none d-lg-block" />
            <div class="hyv-row d-none d-lg-flex">

              

              <a  id="sharemydesign" data-toggle="tooltip" data-placement="top" title="url copied to your clipboard"  class="hyv-btn hyv-btn--link" href="javascript:void(0);">
                <svg class="hyv-icon hyv-icon--16 me-2" role="presentation">
                  <use xlink:href="#hyv_share"></use>
                </svg>
                Share my design</a>

<script>
  $(function () {
  $('[data-toggle="tooltip"]').tooltip('disable');
})
</script>

              <a style="display:none !important;" id="savemydesign" alt="1" class="hyv-btn hyv-btn--link d-none d-lg-inline-flex" href="javascript:void(0);"></a>
              <?php if(!isset($_GET['admin'])) { ?>
              <a class="hyv-btn hyv-btn--link d-none d-lg-inline-flex" id="saveaftlog" href="javascript:void(0);">   
                  
                <svg class="hyv-icon hyv-icon--16 me-2" role="presentation">
                  <use xlink:href="#hyv_save"></use>
                </svg>
                Save design</a>
                <?php } ?>
                  <script>
                  jQuery(document).on('click', '#saveaftlog', function()
                  {

                   if(getCookie("IS_LOGGED") == 1)
                   {
                      jQuery('#savemydesign').click();
                   }
                   else
                   {
                    jQuery('#login-button').click();
                   }

                  });

function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

</script>     <?php if ( is_user_logged_in() ) { ?>
              <?php if(!isset($_GET['admin'])) { ?>
              <a class="hyv-btn hyv-btn--link" href="javascript:void(0);" data-bs-toggle="modal"
        data-bs-target="#hyvMydesign">
                <svg class="hyv-icon hyv-icon--16 me-2" role="presentation">
                  <use xlink:href="#hyv_fav"></use>
                </svg>
                My designs</a>
                 <?php } } ?> 
            </div>

<!-- <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyveMydesign">Product Listing</button> -->


            <div class="hyv-total hyv-total--m-fixed">
              <div class="hyv-total__left">
                <span class="hyv-h3">TOTAL AMOUNT</span>
                <span class="hyv-total__count">
                  <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                 <strong class="total"><?php
                 echo $product->get_price();
// $product->get_regular_price();
// echo $product->get_sale_price();
// echo $product->get_price(); ?></strong>
                 <!--  <span class="hyv-lead hyv-w-medium ms-1 d-none d-lg-inline-block">(1200/Jersy)</span> -->
                </span>
                <!-- <a class="hyv-p hyv-link d-none d-lg-block">See price breakdown</a> -->
                <label class="hyv-p hyv-link d-none d-lg-block" data-bs-toggle="modal"
        data-bs-target="#hyvPricebreakdown">See price breakdown</label>
<!--         <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvPricebreakdown">PRICE BREAKDOWN</button> -->
              </div>
              <div class="hyv-total__right d-flex">
                  <?php if(!isset($_GET['admin'])) { ?>
                <button id="saveaftlog" alt="1" class="hyv-btn hyv-btn--lg hyv-btn--icon me-3 d-lg-none" type="button">
                  <svg class="hyv-icon hyv-icon--14" role="presentation">
                    <use xlink:href="#hyv_save"></use>
                  </svg>
                  <span style="display:none;" id="savemydesign" alt="1" class="hyv-tiny">Save</span>
                  <span class="hyv-tiny">Save</span>
                </button>
                 <?php } ?>
                <?php if(!isset($_GET['admin'])) { ?>
                <button data-bs-toggle="modal"
        data-bs-target="#hyvPricebreakdown" class="hyv-btn hyv-btn--lg hyv-btn--fill" type="button">ADD TO CART</button>
                 <?php } ?>
              </div>
            </div>
          </div>
        </div>
        <!-- hyv-details -->
      </div>

      <!-- Modal starts -->
      <br />
      <br />
      <br />
     <!--  <h3 class="hyv-p">Modals</h3> -->

      <!-- Modal -->
      <!-- <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-xxl-1" data-bs-toggle="modal"
        data-bs-target="#hyvAddteam1">Add team</button> -->
    

      <!-- Modal -->
     <!--  <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvAddteam2">Add team</button> -->
      <div class="modal fade" id="hyvAddteam2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hyvAddteam2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-xl">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <div class="hyv-modal-header__left">
                <h3 class="hyv-h3" id="hyvAddteam2Label">CREATE TEAM</h3>
                <p class="hyv-p hyv-mute" id="">All you have to do is add names, number and size of each member. For
                  each member, these details
                  Will be automatically replaced in your design. </p>
              </div>
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-modal-body hyv-modal-body--minheight">
              <!--<a class="hyv-p hyv-link mb-1 hyv-w-medium ms-auto" data-bs-toggle="modal" data-bs-target="#hyvSizechart"  href="javascript:void(0)">View Size chart</a>-->
              
               <!--<a class="hyv-p hyv-link mb-1 hyv-w-medium ms-auto" data-bs-toggle="collapse" data-bs-target="#collapse8" aria-expanded="true" aria-controls="collapseOne"  href="javascript:void(0)">View Size chart</a>-->
               <a class="hyv-p hyv-link mb-1 hyv-w-medium ms-auto" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"  href="javascript:void(0)">View Size chart</a>
              <a class="hyv-p hyv-link mb-1 hyv-w-medium ms-auto"  data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"  href="javascript:void(0)">How to measure</a>
              <style> #accordionExample th {text-align: center;} @media(max-width:600px) { #accordionExample .hyv-lead, #accordionExample .hyv-table thead th { font-size: 10px !important; } } </style>
               <div class="accordion" id="accordionExample">
     <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
     <div class="card-body">
     <?php  $content = $wpdb->get_var( "SELECT content FROM {$wpdb->prefix}3dconfig_product_meta_lookup WHERE product_id = $productID" ); 

                      if($content != null)
                      { ?>

                      <?php echo $content;
                      $content = stripslashes($content);
                      $xmlEl = simplexml_load_string($content);
                      $sizediv = $xmlEl->attributes()->{'data-size'};
                      $size1 = explode(',', $sizediv); 
                        
                   }   else
                      { 
                      $size1 = null;
                      ?>
                      <label>Size Chart not Available</label>
                      <?php }

                      ?>
      </div>
      </div>
      <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
      <div class="card-body">
      <img style="max-width: 100%;" src="<?php  echo _3D_CONFIG_URL.'/assets/how_to_measure.png'; ?>">
      </div>
      </div>  
      </div>
      

                      <?php /* ?>
                      <div class="hyv-accordion__item">
                      <div id="collapse8" class="hyv-accordion__collapse collapse " aria-labelledby="headingOne">
                      <div class="hyv-row">
                        <div class="imgsizec1" style="display: flex;justify-content: center;">
                      <?php  $content = $wpdb->get_var( "SELECT content FROM {$wpdb->prefix}3dconfig_product_meta_lookup WHERE product_id = $productID" ); 

                      if($content != null)
                      { ?>

                      <?php echo $content;
                      $content = stripslashes($content);
                      $xmlEl = simplexml_load_string($content);
                      $sizediv = $xmlEl->attributes()->{'size'};
                      $size1 = explode(',', $sizediv); 
                        
                   }   else
                      { ?>
                      <label>Size Chart not Available</label>
                      <?php }

                      ?>

                      </div>
                      </div></div></div> <?php */ ?>
              
              
              
              <div class="hyv-table-scroll hyv-table-scroll--hidden">
                 <form method="post" action="" enctype="multipart/form-data" id="teamform">
                <table id="tbl" class="hyv-table hyv-table--inputscroll">
                  <thead>
                    <tr>
                      <th scope="col" class="hyv-table-th-size">Size</th>




                       <?php
                    if(isset($_GET['unique_id'])) {
                    
                    if(count($thr_array['TCtexts']) > 0)
                    {
                      $txtthree = $thr_array['TCtexts'];
                      $it = 1;
                      foreach($txtthree as $row)
                      { ?>
                        <th scope="col" class="hyv-table-th-text">Text <?php echo $it; ?></th>
                    <?php $it++;  }
                    }
                    else
                    {
                      ?>
                      <th scope="col" class="rmth hyv-table-th-text">Text 1</th><th scope="col" class="rmth hyv-table-th-text">Text 2</th><th scope="col" class="rmth hyv-table-th-text">Text 3</th><th scope="col" class="rmth hyv-table-th-text">Text 4</th>
                   <?php 
                    }
                     } else { ?>
                      <th scope="col" class="rmth hyv-table-th-text">Text 1</th><th scope="col" class="rmth hyv-table-th-text">Text 2</th><th scope="col" class="rmth hyv-table-th-text">Text 3</th><th scope="col" class="rmth hyv-table-th-text">Text 4</th>
                   <?php }
                    ?>
<th id="innerth"></th>
                     


                      <th scope="col" class="hyv-sticky-right">&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>






  



                      




                 <?php   if(isset($_GET['unique_id'])) {



                      $uid4 = $_GET['unique_id'];
                      $team_data = $wpdb->get_var( "SELECT data FROM {$wpdb->prefix}3dconfig_team_members WHERE unique_id = '$uid4'" );
                      if($td_count != 1)
{

                      

                      $str = str_replace('\\', '', $team_data);


                      $teams_data = json_decode($str);
                      $array = json_decode(json_encode($teams_data), True);
                      $cc = 0;   
                                    foreach( $array as $key => $row) {  
                                    $ta[] = $row["value"];
                                    $team_arr[] = array($row["name"] => $row["value"]);
                                    $cc++;
                                    if($cc == $td_count) {
                                    $team_a[] = $ta;
                                    unset($ta);
                                    $cc = 0; }
                                            } }
else
{
  $team_a = null;
}
 ?>


                      <?php  
                      if($team_a != null) {
                       $c_ount = 0;
                        foreach($team_a as $value) {
                     
  
                          ?>
                       <tr class="multi-field2">

                        <td>
                        <?php // $size_table = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_sizechart WHERE gender = $gender ORDER BY chest ASC" ); ?>
                              <select name="text" id="text" class="hyv-form-select form-select hyv-form-control--size"
                          aria-label="Default select example">
                        
                        <?php
$incval = 0;
foreach($value as $key => $val3) { 
if($incval == 0)
{
  $si = $val3;
}
$incval++;

}

                         
                                               if($size1 != null) {
                          if(!in_array($si, $size1)) {
                          echo '<option selected >'.$si.'</option>';
                          }
                         foreach($size1 as $value2) { 
                         
                          ?>
                        <?php
                         
                         if($value2 == $si) { ?>
                        <option selected value="<?php echo $value2; ?>" ><?php echo $value2; ?></option>
                        <?php } else
                        { ?>
                             <option value="<?php echo $value2; ?>"><?php echo $value2; ?></option>
                      <?php  }
                         }
                         
                          } 
                         else
                         { ?>
                         <!--<option value="S">S</option>-->
                         <option selected value="<?php echo $si; ?>"><?php echo $si; ?></option>
                        <?php } ?>
                      </select>
                      </td>


                


                    
                    <?php /*
                    if($thr_array['TCtexts'])
                    {
                      $txtthree = $thr_array['TCtexts'];
                      foreach($txtthree as $row)
                      { ?>

                        <td alt="<?php echo $row['text']; ?>"><input name="text" id="text" value="<?php echo $row['text']; ?>" type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size1"></td>

                    <?php  }
                   
                    
                     

                    } */

                  




                     ?>

                    
                     

                    <?php //$counter = 0; 
                        $txtthree = $thr_array['TCtexts'];

                      
                        $rowct = 2;

                        foreach($value as $key => $val3) { 
                        if($rowct != 2) {
                        $loop = 0;
                        foreach($txtthree as $row)
                        {
                          if(($rowct-3) == $loop) {
                          $id = $row['id'];
                        }
                        $loop++;
                        }
                        ?>

                        

                       
                       <td alt="<?php echo $id; ?>"><input name="text" id="text" value="<?php echo $val3; ?>" type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size1"></td>
                      <?php } $rowct++;  }  ?>

<td  style="display: none;" id="innertd" class="innertd"></td>
            
                      
                      




                      <td class="hyv-sticky-right">
                        <span style="display: none;" class="hyv-sticky-fade">
                          <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--14" aria-label="Delete"
                            title="scroll">
                            <svg role="presentation">
                              <use xlink:href="#hyv_longarrow-right"></use>
                            </svg>
                          </button>
                        </span>
                        <button  type="button" style="<?php if($c_ount != 0) { echo 'display:block'; } else { echo 'display:none'; } ?>" onclick="DelRow(this)" class="hyv-icon remove-field hyv-icon--btn hyv-icon--18" aria-label="Delete"
                          title="Delete">
                          <svg role="presentation">
                            <use xlink:href="#hyv_delete-outline"></use>
                          </svg>
                        </button>
                      </td>



                    </tr> 
</tr>

                   <?php  $c_ount++;  } ?>

                   <input type="hidden" id="c_count" value="<?php echo $c_ount; ?>" name="">
                  <?php } else { ?>
                  
                    <?php echo '<div id="mfield">'; ?>

<td>
                                                  <select name="text" id="text" class="hyv-form-select form-select hyv-form-control--size"
                          aria-label="Default select example">
                        
                         <?php
                        //$c_ount = 0;
                        if($size1 != null) {
                          if(isset($_GET['size'])) {
                           if(!in_array($_GET['size'], $size1)) {
                          echo '<option selected>'.$_GET['size'].'</option>';
                        }
                          } 
                         foreach($size1 as $value2) { ?>
                        
                       
                        <option><?php echo $value2; ?></option>
                        <?php } }
                          else { ?>
                           
                         <?php if(isset($_GET['size'])) {
                          echo '<option selected>'.$_GET['size'].'</option>';
                          } else
                          {
                            echo '<option>S</option>';
                          } } ?>
                      </select>
</td>
                      <td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size1"></td><td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size2"></td><td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size"></td> <td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size"></td>


<td id="innertd" class="innertd"></td>
            
                      
                      




                      <td class="hyv-sticky-right">
                        <span style="display: none;" class="hyv-sticky-fade">
                          <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--14" aria-label="Delete"
                            title="scroll">
                            <svg role="presentation">
                              <use xlink:href="#hyv_longarrow-right"></use>
                            </svg>
                          </button>
                        </span>
                        <button  type="button" style="display:none;" onclick="DelRow(this)" class="hyv-icon remove-field hyv-icon--btn hyv-icon--18" aria-label="Delete"
                          title="Delete">
                          <svg role="presentation">
                            <use xlink:href="#hyv_delete-outline"></use>
                          </svg>
                        </button>
                      </td>

                   <input type="hidden" id="c_count" value="0" name="">

                    </tr> 
                  
                  
                  
           <?php   }    } else { ?>

                    <?php echo '<div id="mfield">'; ?>

<td>
                          <select name="text" id="text" class="hyv-form-select form-select hyv-form-control--size"
                          aria-label="Default select example">
                        
                        <?php
                        //$c_ount = 0;
                        if($size1 != null) {

                         if(isset($_GET['size'])) { 
                         
                         if(!in_array($_GET['size'], $size1)) {
                          echo '<option selected>'.$_GET['size'].'</option>';
                          }
                       }  
                         if(isset($_GET['size'])) { 

                         foreach($size1 as $value2) { 

                           

                            if($_GET['size'] == $value2) {
                            ?>

                         
                        <option selected ><?php echo $value2; ?></option>

                         <?php } else { ?>

                             <option><?php echo $value2; ?></option>

                        <?php } ?>


                        <?php }
                         } else { 
  foreach($size1 as $value2) { 

                           

                            if($_GET['size'] == $value2) {
                            ?>

                         
                        <option selected ><?php echo $value2; ?></option>

                         <?php } else { ?>

                             <option><?php echo $value2; ?></option>

                        <?php } ?>


                        <?php }

                       }


                          }
                         else { ?>
                           
                         <?php if(isset($_GET['size'])) {

                          echo '<option selected>'.$_GET['size'].'</option>';


                          }

                          else
                          {
                            echo '<option>S</option>';
                          }


                           } ?>
                      </select>
                      
</td>
                      <td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size1"></td><td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size2"></td><td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size"></td> <td class="rmtd"><input readonly type="text" class="form-control hyv-form-control hyv-form-control--text" title="Size"></td>


<td id="innertd" class="innertd"></td>
            
                      
                      




                      <td class="hyv-sticky-right">
                        <span style="display: none;" class="hyv-sticky-fade">
                          <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--14" aria-label="Delete"
                            title="scroll">
                            <svg role="presentation">
                              <use xlink:href="#hyv_longarrow-right"></use>
                            </svg>
                          </button>
                        </span>
                        <button  type="button" style="display:none;" onclick="DelRow(this)" class="hyv-icon remove-field hyv-icon--btn hyv-icon--18" aria-label="Delete"
                          title="Delete">
                          <svg role="presentation">
                            <use xlink:href="#hyv_delete-outline"></use>
                          </svg>
                        </button>
                      </td>

                   <input type="hidden" id="c_count" value="0" name="">

                    </tr> 

                  <?php  } 
                    ?>






<!-- onclick="DelRow(this)" -->

<!-- <button type="button" class="button-primary remove-field">Remove</button> <button type="button" class="button-primary add-field">Add field</button> -->

                   



<?php echo '</div>'; ?>





                  </tbody>
                </table>
              </form>
              </div>
               <?php if(!isset($_GET['unique_id'])) { ?>

              <label id="addmembersbtn" style="display: none;" class="add-field hyv-p hyv-link-black mt-4 hyv-w-medium me-auto" href="">+Add more members</label>
              <i style="font-style: italic;" id="addmembersnote">create Team members from "Add Text section"</i>
              <?php } else { ?>
                 
                 <?php if(count($thr_array['TCtexts']) > 0)
                    { ?>

                 <label id="addmembersbtn" class="add-field hyv-p hyv-link-black mt-4 hyv-w-medium me-auto" href="">+Add more members</label>
              <i style="display: none;" id="addmembersnote">create Team members from "Add Text section"</i>

                 <?php } else
                 { ?>
                  <label id="addmembersbtn" style="display: none;" class="add-field hyv-p hyv-link-black mt-4 hyv-w-medium me-auto" href="">+Add more members</label>
              <i id="addmembersnote">create Team members from "Add Text section"</i>
                <?php } }

                  ?>
              <script>

// jQuery('.innertd').before('');

// jQuery('#innerth').before('');





if(document.getElementById('c_count').value != 0)
{
var c_ount = document.getElementById('c_count').value;

} else
{
  var c_ount = 1;
}



    jQuery(".add-field").click(function (event) { 

c_ount++;

var $tableBody = $('#tbl').find("tbody"),
    $trLast = $tableBody.find("tr:last"),
    $trNew = $trLast.clone();

$trLast.after($trNew);

// $('#shirtqty').html(c_ount);
// $('.allqty').html(c_ount);
//  var p = $('#defshirtqty').html();
// $('#shirtprice').html(c_ount*p);
roWreplace();

  if(c_ount > 1)
{
  
  jQuery(".remove-field").show();
  var jj = jQuery(".remove-field")[0];
  jj.style.display = "none"; 

}


       });


  function DelRow(row)
  {

    if(c_ount > 1) {
    var whichtr = row.closest("tr");
    // alert('worked'); // Alert does not work
    whichtr.remove();  
c_ount--;
// $('#shirtqty').html(c_ount);
// $('.allqty').html(c_ount);
//  var p = $('#defshirtqty').html();
// $('#shirtprice').html(c_ount*p);
roWreplace();
} 
if(c_ount < 2)
{
  jQuery(".remove-field").hide();
}
  }



  </script>
            </div>
            <div class="hyv-modal-footer hyv-modal-footer--total">
              <div class="hyv-total">
                <div class="hyv-total__left">
                  <span class="hyv-total__count">
                    <span class="hyv-h3 me-2">TOTAL AMOUNT</span>
                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                 <strong class="total"><?php
                 echo $product->get_price();
// $product->get_regular_price();
// echo $product->get_sale_price();
// echo $product->get_price(); ?></strong>
                    <!-- <span class="hyv-lead hyv-w-medium ms-1 d-none d-lg-inline-block">(1200/Jersy)</span> -->
                  </span>
                  <!-- <a class="hyv-p hyv-link d-none d-lg-block">See price breakdown</a> -->
                   <label class="hyv-p hyv-link d-none d-lg-block" data-bs-toggle="modal"
        data-bs-target="#hyvPricebreakdown">See price breakdown</label>
                </div>
                <div class="hyv-total__right d-flex">
                  <!--<button class="hyv-btn hyv-btn--lg hyv-btn--icon me-3 d-lg-none" type="button">-->
                  <!--  <svg class="hyv-icon hyv-icon--14" role="presentation">-->
                  <!--    <use xlink:href="#hyv_save"></use>-->
                  <!--  </svg>-->
                  <!--  <span class="hyv-tiny">Save</span>-->
                  <!--</button>-->
                  <button id="save_team" class="hyv-btn hyv-btn--lg hyv-btn--outline" type="button">SAVE LIST</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



<script type="text/javascript">

 $(document).ready(function(){

    $("#save_team").click(function(){

       let uid = '<?php echo $u_id; ?>'

        // var fd = new FormData();

        var t_data = $('form#teamform').serializeArray();

        if(t_data.length > 0) {

        let myJSON = JSON.stringify(t_data);

        // console.log(myJSON);
      //  $('#t-data').val(myJSON);
                 $.ajax({
             url: "<?php echo _3D_CONFIG_URL ?>form.php",
            type: 'post',
            data: {teamdata : myJSON , uid : uid },
            success: function(data){
            //jQuery('#myds').html(data);
            }
            });

          }
       
          

          $('#hyvAddteam2').modal('hide');

  //        var $form = $('form#teamform').find("tbody")
  // var fieldSets = $form.find("tr");
 
  // var result = {
  //   items: []
  // };
  // fieldSets.each(function() {
  //   console.log($(this));
  //   var fields = {};
  //   $.each($(this).serializeArray(), function() {
  //     fields[this.name] = this.value;
  //   });
  //   result.items.push(fields);
  // });

  // console.log(result);

     
    });
});

  

//   $(document).ready(function(){

//     $("#save_team").click(function(){

//         var fd = new FormData();


//         // var files = $('#file')[0].files;
        
//         // Check file selected or not
//         // if(fd.length > 0 ){
//            // fd.append('file',files[0]);
//            console.log(fd);

//            $.ajax({ 
//               url: '<?php  echo _3D_CONFIG_URL.'/form.php'; ?>',
//               type: 'post',
//              data: $('form#myform').serialize(),
//               // contentType: false,
//               // processData: false,
//               success: function(response){
//                  if(response != 0){
//                     // $("#img").attr("src",response); 
//                     // $("#img").show(); // Display image element



//                     $('.popup4-b').append('<h1>'+response+'</h1>');
                
                    

//                  } else {
//                     alert('file not uploaded');
//                  }
//               },
//            });
//         // }else{
//         //    alert("Please select a file.");
//         // }
//     });
// });
</script>



      <!-- Modal -->
     <!--  <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvPricebreakdown">PRICE BREAKDOWN</button> -->
      <div class="modal fade" id="hyvPricebreakdown" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hyvPricebreakdownLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-lg">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <div class="hyv-modal-header__left">
                <h2 class="hyv-h2" id="hyvPricebreakdownLabel">PRICE BREAKDOWN</h2>
              </div>
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-modal-body">
              <div class="hyv-table-scroll">
                <div class="hyv-breakdown-wrap">
                  <table class="hyv-table">
                    <thead>
                      <tr>
                        <th scope="col" class="hyv-table-th-product">Products</th>
                        <th scope="col" class="hyv-table-th-ppu">Price per unit</th>
                        <th scope="col" class="hyv-table-th-qty">Qty</th>
                        <th scope="col" class="hyv-table-th-nt">Net total</th>
                      </tr>
                    </thead>
                    <tbody id="price-bkd">
                      <tr>
                        <td scope="row"><span class="hyv-lead"><?php $product = wc_get_product( $productID ); echo $product->get_title(); ?></span></td>
                        <td scope="row"><?php echo get_woocommerce_currency_symbol(); ?><span id="defshirtqty" class="hyv-lead"><?php echo $product->get_price(); ?></span></td>
                        <td scope="row"><span id="shirtqty" class="hyv-lead">1</span></td>
                        <td scope="row"><?php echo get_woocommerce_currency_symbol(); ?><span id="shirtprice" class="hyv-lead sum"><?php echo $product->get_price(); ?></span></td>
                      </tr>





                     <!--  <tr>
                        <td scope="row"><span class="hyv-lead">Full sleeve addon</span></td>
                        <td scope="row"><span class="hyv-lead">₹30</span></td>
                        <td scope="row"><span class="hyv-lead">20</span></td>
                        <td scope="row"><span class="hyv-lead">₹5000</span></td>
                      </tr>
                      <tr>
                        <td scope="row"><span class="hyv-lead">Full sleeve addon</span></td>
                        <td scope="row"><span class="hyv-lead">₹30</span></td>
                        <td scope="row"><span class="hyv-lead">20</span></td>
                        <td scope="row"><span class="hyv-lead">₹5000</span></td>
                      </tr>
                      <tr>
                        <td scope="row"><span class="hyv-lead">Full sleeve addon</span></td>
                        <td scope="row"><span class="hyv-lead">₹30</span></td>
                        <td scope="row"><span class="hyv-lead">20</span></td>
                        <td scope="row"><span class="hyv-lead">₹5000</span></td>
                      </tr> -->
                    </tbody>
                  </table>

                        <script>



  
                // var alt = data.attributes['alt'].value;
                // var dir = data.attributes['dir'].value;         
                 // var temp = new Array();
                 // y = temp = as_str.split(",");
              //   $('.h_asts.active').each(function() {

                 
              //     var alt = $(this).attr('alt');
              //     var dir = $(this).attr('dir');

              //     $('#price-bkd').append('<tr class="pricetr"><td scope="row"><span class="hyv-lead">'+alt+'</span></td><td scope="row"><span class="hyv-lead">'+dir+'</span></td><td scope="row"><span class="hyv-lead allqty">'+c_ount+'</span></td><td scope="row"><span class="hyv-lead">'+dir*c_ount+'</span></td></tr>');



              // })

roWreplace();
            


              </script>
                </div>
                <div class="hyv-breakdown-total-wrap">
                  <table class="hyv-table">
                    <tbody>
                      <tr>
                       <!--  <td scope="row" class="hyv-table-th-product"><span class="hyv-lead">Taxes and charges</span> -->
                        </td>
                        <td scope="row" class="hyv-table-th-ppu">&nbsp;</td>
                        <td scope="row" class="hyv-table-th-qty">&nbsp;</td>
                       <!--  <td scope="row" class="hyv-table-th-nt"><span class="hyv-lead">0</span></td> -->
                      </tr>
                      <tr>
                        <td scope="row"><strong class="hyv-xlead">Total Amount</strong></td>
                        <td scope="row">&nbsp;</td>
                        <td scope="row">&nbsp;</td>
                        <td scope="row"><strong><?php echo get_woocommerce_currency_symbol(); ?></strong><strong class="hyv-xlead total">10,000</strong></td>
                      </tr>
                       <?php if(isset($_GET['size'])) { ?>
                      <tr id="customsize">
                        <td scope="row"><strong class="hyv-xlead">Default Size Choosen :</strong></td>
                        <td scope="row">&nbsp;</td>
                        <td scope="row">&nbsp;</td>
                        <td scope="row"><strong><?php echo $_GET['size']; ?></strong></td>
                      </tr>
                       <?php } else { ?>
                        <tr class="defsize">
                        <td scope="row"><strong class="hyv-xlead">Size Choosen :</strong></td>
                        <td scope="row">&nbsp;</td>
                        <td scope="row">&nbsp;</td>
                        <td id="tblsize" scope="row"><strong>S</strong></td>
                      </tr><i class="defsize">Select size from 'Create Team' Option</i>
                       <?php } ?>
                    </tbody>
                  </table>
                   <script>
                     if(jQuery('#tblsize').length) {
                    jQuery('#tblsize').html('<strong>'+jQuery('#text').val()+'</strong>');
                  }
                    jQuery('#text').change(function(){
                    if(jQuery('#tblsize').length) {
                    jQuery('#tblsize').html('<strong>'+jQuery('#text').val()+'</strong>');
                  }
                    });
                  </script>
                </div>
              </div>
            </div>
            <div class="hyv-modal-footer hyv-modal-footer--total">
              <div class="hyv-total">
                <div class="hyv-total__left">
                  <span class="hyv-total__count">
                    <span class="hyv-h3 me-2">TOTAL AMOUNT</span>
                    <strong><?php echo get_woocommerce_currency_symbol(); ?></strong>
                    <strong class="total"><?php
                 echo wc_price($product->get_price());
// $product->get_regular_price();
// echo $product->get_sale_price();
// echo $product->get_price(); ?></strong>
                   <!--  <span class="hyv-lead hyv-w-medium ms-1 d-none d-lg-inline-block">(1200/Jersy)</span> -->
                  </span>
                </div>
                <div class="hyv-total__right d-flex">
                  <!--<button class="hyv-btn hyv-btn--lg hyv-btn--icon me-3 d-lg-none" type="button">-->
                  <!--  <svg class="hyv-icon hyv-icon--14" role="presentation">-->
                  <!--    <use xlink:href="#hyv_save"></use>-->
                  <!--  </svg>-->
                  <!--  <span id="savemydesign" alt="1" class="hyv-tiny">Save</span>-->
                  <!--</button>-->

                  <!-- <button class="hyv-btn hyv-btn--lg hyv-btn--fill" type="button">ADD TO CART</button> -->
                   <?php if(!isset($_GET['admin'])) { ?>
                   <button id="savemydesign" alt="2" name="add_to_cart2" class="hyv-btn hyv-btn--lg hyv-btn--fill savemybt2">VIEW CART</button>
                   <?php } ?>
                    <img style="width: 50px;display: none;" id="progicon" src="<?php echo _3D_CONFIG_URL; ?>assets/progress.gif">
                  <form id="add_to_cart2"  method="post">
                 
                  <input type="hidden" name="pid" value="<?php echo $productID; ?>">
                  <input type="hidden" id="p-qty" name="p-qty" value="1">
                  <!--<input type="hidden" id="t-data" value="" name="t-data">-->
                  <input autocomplete="off" type="hidden" id="addons-data" value="<?php if(isset($assets_array)) { echo preg_replace('/\s+/', '', implode(', ', $assets_array)); } ?>" name="addons-data">
                  <input autocomplete="off" type="hidden" id="td-count" value="<?php if(isset($_GET['unique_id'])) { echo $td_count; } else { echo "1"; } ?>" name="td-count">
                  <input type="hidden" name="unique_id" value="<?php echo $u_id; ?>">
                  <input type="hidden" autocomplete="off"  name="old_unique_id" value="<?php if(isset($old_cmz_id)) { echo $old_cmz_id; } else { echo 0; } ?>">
                  <input autocomplete="off" type="hidden" id="pset-col" value="<?php if(isset($_GET['unique_id'])) { echo implode(', ', $pset_col2); }else{ echo $svgfilecol; } ?>" name="pset-col">
                  <input type="hidden" id="setflag" name="setflag">
                  <input autocomplete="off" type="hidden" id="customsize" name="customsize" value="<?php if(isset($_GET['size'])) { echo $_GET['size']; } ?>">
                </form>
                
                  <script>
                  if(document.getElementById('c_count').value != 0)
                        {
                        var c_ount = document.getElementById('c_count').value;
                        document.getElementById('p-qty').value = c_ount;

                        }
                </script>
<!--                  <script>
  jQuery(document).on('click', '#savemydesign', function() {

    alert(1);

  });
</script> --> 

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <!-- <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvSizechart">Size Chart</button> -->
      <div class="modal fade" id="hyvSizechart" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-lg">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-filter">
              <div class="hyv-filter__left">
               <!--  <div class="d-flex">
                  <button class="hyv-btn hyv-btn--dropdown me-4" type="button" id="">
                    <span class="hyv-w-medium">View Size Chart</span>
                    <svg class="hyv-icon hyv-icon--8 ms-1" role="presentation">
                      <use xlink:href="#hyv_arrow-up"></use>
                    </svg>
                  </button>
                  <button class="hyv-btn hyv-btn--dropdown" type="button" id="">
                    <span class="hyv-w-medium">How to measure1</span>
                    <svg class="hyv-icon hyv-icon--8 ms-1" role="presentation">
                      <use xlink:href="#hyv_arrow-down"></use>
                    </svg>
                  </button>
                </div> -->
              </div>
              <div class="hyv-filter__right">
                <div class="dropdown">
                  <button class="hyv-btn hyv-btn--dropdown" type="button" id="dropdownMenuButton0"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>Gender <span class="hyv-w-medium"><?php if($gender == 1) { echo 'Male'; } else { echo 'Female'; } ?></span></span>
                    <svg class="hyv-icon hyv-icon--8 ms-1" role="presentation">
                      <!-- <use xlink:href="#hyv_arrow-down"></use> -->
                    </svg>
                  </button>
                 <!--  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton0">
                    <a class="dropdown-item" href="#">Male</a>
                    <a class="dropdown-item" href="#">Female</a>
                  </div> -->
                </div>
              </div>
            </div>
            <div class="hyv-modal-body">
              <div class="hyv-table-scroll mt-3">
              <div class="imgsizec1" style="display: flex;justify-content: center;">

<?php  //$size_table = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}3dconfig_sizechart WHERE gender = $gender ORDER BY chest ASC" ); ?>

<?php  $imgsizec = $wpdb->get_var( "SELECT file FROM {$wpdb->prefix}3dconfig_sizeimg WHERE product_id = $productID" ); 

if($imgsizec != null)
{ ?>

<img src="<?php echo _3D_CONFIG_URL.'assets/img/'.$imgsizec; ?>">
<?php }
else
{ ?>
<label>Size Chart not Available</label>
<?php }

?>

</div>

        <?php /* ?>        <table class="hyv-table hyv-table--bordered">
                  <thead>
                    <tr>
                     <!--  <th scope="col" class="text-center">Size</th>
                      <th scope="col" class="text-center">Chest (in)</th>
                      <th scope="col" class="text-center">Front length (in)</th>
                      <th scope="col" class="text-center">Across shoulder (in)</th> -->
                      <th>Size</th>
    <th>Chest</th>
    <th>Waist</th>
    <th>Hips</th>
    <th>Length</th>
                    </tr>
                  </thead>
                  <tbody>
                   <!--  <tr>
                      <td scope="row" class="text-center"><span class="hyv-lead">X</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">58</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">56</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">45</span></td>
                    </tr>
                    <tr>
                      <td scope="row" class="text-center"><span class="hyv-lead">X</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">58</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">56</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">45</span></td>
                    </tr>
                    <tr>
                      <td scope="row" class="text-center"><span class="hyv-lead">X</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">58</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">56</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">45</span></td>
                    </tr>
                    <tr>
                      <td scope="row" class="text-center"><span class="hyv-lead">X</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">58</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">56</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">45</span></td>
                    </tr>
                    <tr>
                      <td scope="row" class="text-center"><span class="hyv-lead">X</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">58</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">56</span></td>
                      <td scope="row" class="text-center"><span class="hyv-lead">45</span></td>
                    </tr> -->
                    <?php
   foreach( $size_table as $key => $row) { ?>

<tr>
    <!-- <td><?php echo $row->m_id;  ?></td> -->
    <td><?php echo $row->size;  ?></td>
        <td><?php echo $row->chest;  ?></td>
            <td><?php echo $row->waist;  ?></td>
                <td><?php echo $row->hips;  ?></td>
                    <td><?php echo $row->length;  ?></td>
  </tr>


<?php
}
 ?>
                  </tbody>
                </table> <?php */ ?>



              </div>
            </div>
          </div>
        </div>
      </div>








 <div class="modal fade" id="hyvMydesign" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hyvMydesignLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-bodyscroll hyv-modal-xmd">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <div class="hyv-modal-header__left">
                <h2 class="hyv-h3" id="hyvMydesignLabel">YOUR SAVED DESIGNS</h2>
              </div>
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-modal-body">
              <ul class="hyv-saved-list list-unstyled mt-2" id="myds">


<?php

$table_1 = $wpdb->prefix . '3dconfig_customization';
$table_2 = $wpdb->prefix . '3dconfig_screenshots';
$table_3 = $wpdb->prefix . '3dconfig_threejsdata';
$cookie = $_COOKIE['UNIQUE_USER_ID'];
//$myds = $wpdb->get_results( "SELECT * FROM $table_1 INNER JOIN $table_2 ON $table_1.unique_id = $table_2.unique_id INNER JOIN  $table_3 ON $table_1.unique_id = $table_3.unique_id WHERE $table_1.cmz_user_id = $cookie AND $table_1.flag = 1 AND $table_2.pos = 3" );
if ( is_user_logged_in() ) {
  $wp_user_id = get_current_user_id();
  $myds = $wpdb->get_results( "SELECT * FROM $table_1 INNER JOIN $table_2 ON $table_1.unique_id = $table_2.unique_id INNER JOIN  $table_3 ON $table_1.unique_id = $table_3.unique_id WHERE $table_1.wp_user_id = $wp_user_id AND $table_1.flag = 1 AND $table_2.pos = 3 ORDER BY $table_1.cmz_id DESC" );
}
else
{
  $myds = $wpdb->get_results( "SELECT * FROM $table_1 INNER JOIN $table_2 ON $table_1.unique_id = $table_2.unique_id INNER JOIN  $table_3 ON $table_1.unique_id = $table_3.unique_id WHERE $table_1.cmz_user_id = $cookie AND $table_1.flag = 1 AND $table_2.pos = 3 ORDER BY $table_1.cmz_id DESC" );
}
//print_r($myds);
if(count($myds) == 0)

{ ?>


<center>You Have No Design</center>

<?php
}

foreach ($myds as $des){
   //$product_url = $des->m_file;

?>



 
                <li class="hyv-saved-list__item">
                 
                  <figure style="cursor: pointer;" class="hyv-preview__figure" onclick="window.location.href='<?php echo get_permalink(); ?>?unique_id=<?php echo $des->unique_id; ?>'">
                    <img class="hyv-preview__img" src="<?php echo _3D_CONFIG_URL; ?>assets/screenshots/<?php echo $des->file; ?>" alt="diagram showing">
                    <figcaption class="hyv-preview__figcaption"><?php echo wc_get_product( $des->product_id )->get_title(); ?></figcaption>
                  </figure> 
                  <div style="cursor: pointer;" class="hyv-saved-list__content" onclick="window.location.href='<?php echo get_permalink(); ?>?unique_id=<?php echo $des->unique_id; ?>'">
                    <h3 class="hyv-h3"><?php echo $des->design_name;?>&nbsp|&nbsp<?php echo wc_get_product( $des->product_id )->get_title(); ?></h3>
                 <!--    <time class="hyv-p hyv-mute">Last edited on 15 Oct 2021, 11:40 PM</time> -->
                    <span class="hyv-small d-block mt-3">Current total price <?php echo get_woocommerce_currency_symbol(); ?><?php
                 echo wc_get_product( $des->product_id )->get_price(); ?></span>
                  </div>
                  



                 

<?php
if(isset($_GET['unique_id'])) {
if($_GET['unique_id'] == $des->unique_id)
{ ?>

<?php }

else
{ ?>
 <button onclick="deldesign(this)" alt="<?php echo $des->unique_id; ?>" value="<?php if(isset($_GET['unique_id'])) { echo $_GET['unique_id'];  } else { echo 0; } ?>" type="button" class="hyv-icon hyv-icon--btn hyv-icon--16"
                    aria-label="Delete MTB RC-V CUSTOM CYCLING TEE">
                    <svg role="presentation">
                      <use xlink:href="#hyv_delete-outline"></use>
                    </svg>
                  </button>
<?php } }


else { ?>

 <button onclick="deldesign(this)" alt="<?php echo $des->unique_id; ?>" value="<?php if(isset($_GET['unique_id'])) { echo $_GET['unique_id'];  } else { echo 0; } ?>" type="button" class="hyv-icon hyv-icon--btn hyv-icon--16"
                    aria-label="Delete MTB RC-V CUSTOM CYCLING TEE">
                    <svg role="presentation">
                      <use xlink:href="#hyv_delete-outline"></use>
                    </svg>
                  </button>
<?php }
?>


                </li>
              


<?php
   }


 ?>


              
              </ul>
            </div>
          </div>
        </div>
      </div>







<script>
 function deldesign(color)
             {
              var lta = color.attributes['alt'].value;

              var val = color.value;
              


                 $.ajax({
             url: "<?php echo _3D_CONFIG_URL ?>form.php",
            type: 'post',
            data: {deletedesign :lta, deletedesign1 :val  },
            success: function(data){


            jQuery('#myds').html(data);


            }
         });



 }
</script>



      <!-- Modal -->
      <!-- <button type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvProductListing">Product Listing</button> -->
 
      <div class="modal fade" id="hyvProductListing" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="hyvProductListingLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-bodyscroll hyv-modal-xl">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <div class="hyv-modal-header__left">
                <h2 class="hyv-h2" id="hyvProductListingLabel">Products</h2>
              </div>
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-filter">
              <div class="hyv-filter__left">
                <div class="d-flex">
                <div class="dropdown">
                          <button class="hyv-btn hyv-btn--dropdown" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span>Category: <span id="p_cat_val" class="hyv-w-medium">All Category</span></span>
                            <svg class="hyv-icon hyv-icon--8 ms-1" role="presentation">
                              <use xlink:href="#hyv_arrow-down"></use>
                            </svg>
                          </button>
                          <div style="height: 200px;
overflow: hidden scroll;" class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">

                             <?php $p_cat= $wpdb->get_results( "SELECT DISTINCT * FROM {$wpdb->prefix}3dconfig_assets_category" ); ?>
                                <a onclick="procat(this)" class="dropdown-item" alt="0" value="0" href="#">All Category</a>
                           <?php  foreach( $p_cat as $key => $row) { ?>
                             <a onclick="procat(this)" class="dropdown-item" alt="<?php echo $row->category_id; ?>" value="<?php echo $row->category_id; ?>" href="#"><?php echo $row->category_name; ?></a>

                              <?php } ?>


                          </div>
                        </div>

                       <script>

                  function procat(id) {
                  //  var p_id = jQuery(this).val();
                 var p_id = id.attributes['alt'].value;
                 var conceptName = id.innerHTML;
                 jQuery("#p_cat_val").html(conceptName);
                  //var clip = jQuery(this).val();

     $.ajax({
             url: "<?php echo _3D_CONFIG_URL ?>form.php",
            type: 'post',
            data: {productchange :p_id },
            success: function(data){
              if(data)
              {
               $('.productchge').html(data);
              }
              else
              {
                $('.productchge').html("<div style=\"margin: 10% auto;justify-content: center;display: flex;\">No Products in this Category</div>");
              }

            }
         });
}

// });
              </script>
                </div>
              </div>
           <!--   <div class="hyv-filter__right">
                <div class="dropdown">
                  <button class="hyv-btn hyv-btn--dropdown" type="button" id="dropdownMenuButton2"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span>Gender <span class="hyv-w-medium">Male</span></span>
                     <svg class="hyv-icon hyv-icon--8 ms-1" role="presentation">
                      <use xlink:href="#hyv_arrow-down"></use>
                    </svg> 
                  </button>
                 <div class="dropdown-menu" aria-labelledby="dropdownMenuButton2">
                    <a class="dropdown-item" href="#">Male</a>
                    <a class="dropdown-item" href="#">Female</a>
                  </div> 
                </div>
              </div> -->
            </div>
            <style>
              .practv
              {
                border: 1px solid #707070;
                padding: 5px;
              }
            </style>
            <div class="hyv-modal-body hyv-modal-body--minheight">              
              <div class="row productchge">

<?php



  $all_pd_table = $wpdb->get_results( "SELECT product_id FROM {$wpdb->prefix}3dconfig_product_meta_lookup" );
  foreach( $all_pd_table as $key => $row) { ?>

    



                <div class="col-sm-6 col-lg-4 col-xl-3 mt-3">
                  <a href="<?php echo get_permalink(); ?>?productID=<?php echo $row->product_id; ?>" class="hyv-product <?php if($productID == $row->product_id) { echo "practv"; } ?> ">
                    <figure  class="hyv-preview__figure">
                      <?php $image_links[0] = get_post_thumbnail_id( $row->product_id );
$gallery = wp_get_attachment_image_src($image_links[0], 'full' ); ?>
                      <img class="hyv-preview__img" src="<?php echo $gallery[0]; ?>" alt="diagram showing">
                      <figcaption class="hyv-preview__figcaption">MTB RC-V CUSTOM CYCLING TEE</figcaption>
                    </figure>
                    <header class="hyv-product__header">
                      <div class="flex-fill">
                        <h4 class="hyv-h3"><?php echo wc_get_product( $row->product_id )->get_title(); ?></h4>
                        <p class="hyv-tiny hyv-mute mt-1">Customizeable | Breathable | Multi-functional fabrics | Anti Chaffing</p>
                      </div>
                       <span class="hyv-product__price hyv-p"><?php echo get_woocommerce_currency_symbol(); ?><?php
                 echo wc_get_product( $row->product_id )->get_price(); ?></span>
                    </header>
                  </a>
                </div>
               

<?php  }

?>


              </div> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>


<script>
 
</script>
  <!-- Bootstrap core Script -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
  <script src="<?php echo _3D_CONFIG_URL; ?>assets/js/bootstrap.bundle.min.js"></script>
<!--   <object style="display: none;" id="emb" data="http://localhost/svgd/s.svg" width="90" height="90" type="image/svg+xml"></object> -->
<?php
$table_pattern = $wpdb->prefix . '3dconfig_pattern';
$pattern_tb = $wpdb->get_results( "SELECT * FROM $table_pattern  WHERE m_id = $m_id" );
foreach ($pattern_tb as $curr){
   $svg = $curr->pattern_file;
   } ?>
<iframe style="display: none;" id="emb" src="<?php echo _3D_CONFIG_URL; ?>assets/img/pattern/<?php echo $svg; ?>"></iframe>
 <!-- <iframe style="display: none;" id="emb" src="<?php echo _3D_CONFIG_URL; ?>assets/s.svg"></iframe> -->
  <script src="<?php echo _3D_CONFIG_URL; ?>assets/js/svgcolor.js"></script>

  <!--jQuery-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!--Plugin JavaScript file-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

  <script>
    $(".js-range-slider").ionRangeSlider({
      min: 1,
      max: 20,
      step: 1,
      skin: "square",
      hide_min_max: true,
      hide_from_to: true
    });



// $(window).bind('beforeunload', function() {
// if(document.getElementById('windowclose').value == 0) {

//   // var dialogText = 'information you’ve entered may not be saved. Are you realy sure you want to leave?';
//   // e.returnValue = dialogText;
//   // return dialogText;

//  return jQuery('#hyvesave1').click();

// }

// });
window.addEventListener('beforeunload', function (e) {
  if(document.getElementById('windowclose').value == 0) {
  // Cancel the event
  e.preventDefault(); // If you prevent default behavior in Mozilla Firefox prompt will always be shown
  // Chrome requires returnValue to be set
  e.returnValue = ''; }
});
// This default onbeforeunload event


// setTimeout(function() {window.onbeforeunload = null;},3000);
// A jQuery event (I think), which is triggered after "onbeforeunload"
// $(window).unload(function(){
//   jQuery('#hyvesave1').click();
// });
// window.addEventListener('beforeunload', function (e) {

//   if(document.getElementById('windowclose').value == 0) {
//     e.preventDefault();
//     jQuery('#hyvesave1').click();
//   }
    
// });

$("document").ready(function() {
    setTimeout(function() {
       $("#save_team").click();
    },10);
});

  </script>
   <button style="display: none;" type="button"  id="hyvesave1" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
       data-bs-target="#hyvSave">Save & exit</button>
       <div class="modal fade" id="hyvSave" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hyvSaveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-bodyscroll hyv-modal-confirm-drawer">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <h2 class="hyv-lead w-100" id="hyvSaveLabel">DO YOU WANT TO SAVE YOUR DESIGN ?</h2>
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-modal-body">
              <p class="hyv-lead hyv-mute">Your design will be lost if you exit editing without saving. You can save design and access later from 'My design'</p>
              <div class="hyv-modal-confirm-drawer__btns">
                <button  id="savemydesign" alt="3" class="hyv-btn hyv-btn--lg hyv-btn--fill me-2" type="button">SAVE & EXIT</button>
                <button onclick="window.location.href='<?php echo site_url(); ?>'" class="hyv-btn hyv-btn--lg hyv-btn--outline" type="button">DISCARD & EXIT</button>
              </div>
            </div>
          </div>
        </div>
      </div>


  <!--  <script>
    $(document).on('click',function(e){

      if($("#addContentMenuButton").hasClass("show"))
      {
        if($(".dropdown-menu").hasClass("show"))
      {
        $(".dropdown-menu").removeClass("show");
      }
    }
     
   });

   </script> -->
    
<button style="display: none;" type="button"  id="hyvSharepop" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
       data-bs-target="#hyvShareDesign">Save & exit</button>
       <div class="modal fade" id="hyvShareDesign" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hyvSaveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-bodyscroll hyv-modal-confirm-drawer">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <h2 class="hyv-lead w-100" id="hyvSaveLabel">SHARE YOUR DESIGN</h2>
              <button type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-modal-body">
             <!--  <p class="hyv-lead hyv-mute">Your design will be lost if you exit editing without saving. You can save design and access later from 'My design'</p> -->
              <div style="margin-top: 0;" class="hyv-modal-confirm-drawer__btns">
               <!--  <button alt="3" class="hyv-btn hyv-btn--lg hyv-btn--fill me-2" type="button">SAVE & EXIT</button> -->
                <input type="text" id="p1" value="">
                <button onclick="copyToClipboard(this)" id="shareMyDes1" class="hyv-btn hyv-btn--lg hyv-btn--fill me-2" type="button">COPY TO CLIPBOARD</button>
               <!--  <button class="hyv-btn hyv-btn--lg hyv-btn--outline" type="button">COPY TO CLIPBOARD</button>
                <button class="hyv-btn hyv-btn--lg hyv-btn--outline" type="button">COPY TO CLIPBOARD</button> -->
              </div>
            </div>
          </div>
        </div>
      </div>



<script>

function copyToClipboard(cpy) {
toCopy  = document.getElementById( 'p1' )
toCopy.select();
document.execCommand("copy");
cpy.innerHTML = "COPIED";
}
</script>

  <button style="display:none;" id="login-button" type="button" class="hyv-btn hyv-btn--lg hyv-btn--fill mb-1" data-bs-toggle="modal"
        data-bs-target="#hyvLogin">Login</button>
      <div class="modal fade" id="hyvLogin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="hyvLoginLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered hyv-modal-bodyscroll hyv-modal-login">
          <div class="modal-content hyv-modal-content">
            <div class="hyv-modal-header">
              <div class="hyv-modal-header__left">
                <h2 class="hyv-h2" id="hyvLoginLabel">GET. SET. HYVE</h2>
              </div>
              <button id="login-close" type="button" class="hyv-btn hyv-btn-close" data-bs-dismiss="modal" aria-label="Close">
                <svg class="hyv-icon hyv-icon--18" role="presentation">
                  <use xlink:href="#hyv_close"></use>
                </svg>
              </button>
            </div>
            <div class="hyv-modal-body">
              <div class="hyv-form-group mb-4">
                <label class="hyv-label" for="hyvLogin">Enter your registered username or email</label>
                <input id="hyvLogin-username" class="hyv-form-control w-100" type="text" />
              </div>
              <div class="hyv-form-group mb-3">
                <label class="hyv-label" for="hyvLogin">Enter your password</label>
                <div class="hyv-form-control-icon">
                  <input id="hyvLogin-password" class="hyv-form-control w-100" type="password" />
                  <button type="button" class="hyv-icon hyv-icon--btn hyv-icon--16 hyv-form-control-icon__view" aria-label="Delete">
                    <svg role="presentation">
                      <use xlink:href="#hyv_view"></use>
                    </svg>
                  </button>
                </div>
              </div>
              <p onclick="jQuery('#login-close').click()" class="hyv-small hyv-mute">Forgot password? <a target="_blank" class="hyv-link-black" href="<?php echo wp_lostpassword_url(); ?>">Reset password</a> or <a onclick="jQuery('#login-close').click()" class="hyv-link-black" target="_blank" href="<?php // echo wp_registration_url();
              echo get_permalink(woocommerce_get_page_id('myaccount')).'?action=register'; ?>">SignUp</a></p>
             <span id="login-error" style="color:red;display: none;">Username or Password is Invalid!</span>
             <img style="width: 50px;display: none;" id="progif" src="<?php echo _3D_CONFIG_URL; ?>assets/progress.gif">
              <button id="login-continue" class="hyv-btn hyv-btn--lg hyv-btn--fill align-self-start" type="button">CONTINUE</button>
              <p class="hyv-tiny hyv-mute mt-3">By signing in, I agree to <a class="hyv-link-blue hyv-w-medium" href="javascript:void(0)">Terms & Conditions</a></p>
            </div>
          </div>
        </div>
      </div>
      <script>
        jQuery(document).on('click', '#login-continue', function() {
           jQuery('#progif').show();
          jQuery('#login-continue').hide();
          jQuery('#login-error').hide();
var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
var username = jQuery('#hyvLogin-username').val();
var pass = jQuery('#hyvLogin-password').val();
var data = "user_login="+username+"&user_pass="+pass+"&action=custom_login&param=login_test";
jQuery.ajax({
  url:ajaxurl,
  data:data,
  type:"POST",
  success:function(response){
    var res = jQuery.parseJSON(response);
    if(res.status == "1")
    {
      jQuery('#progif').hide();
      jQuery('#login-continue').show();
      $('#hyvLogin').modal('hide');
     jQuery('#savemydesign').click();

    }
    else
    {
    jQuery('#progif').hide();
    jQuery('#login-continue').show();
    jQuery('#login-error').show().fadeIn();

    }


  }

});

});
      </script>


<div style="display:none;" id="blackOverlay" class="loading">Loading&#8230;</div>
</body>
</html>
<?php if ( !wp_is_mobile() ) {  get_footer(); } ?>