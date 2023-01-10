<?php
/**
 * Neve functions.php file
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      17/08/2018
 *
 * @package Neve
 */

define( 'NEVE_VERSION', '3.4.4' );
define( 'NEVE_INC_DIR', trailingslashit( get_template_directory() ) . 'inc/' );
define( 'NEVE_ASSETS_URL', trailingslashit( get_template_directory_uri() ) . 'assets/' );
define( 'NEVE_MAIN_DIR', get_template_directory() . '/' );
define( 'NEVE_BASENAME', basename( NEVE_MAIN_DIR ) );
if ( ! defined( 'NEVE_DEBUG' ) ) {
	define( 'NEVE_DEBUG', false );
}
define( 'NEVE_NEW_DYNAMIC_STYLE', true );
/**
 * Buffer which holds errors during theme inititalization.
 *
 * @var WP_Error $_neve_bootstrap_errors
 */
global $_neve_bootstrap_errors;

$_neve_bootstrap_errors = new WP_Error();

if ( version_compare( PHP_VERSION, '7.0' ) < 0 ) {
	$_neve_bootstrap_errors->add(
		'minimum_php_version',
		sprintf(
		/* translators: %s message to upgrade PHP to the latest version */
			__( "Hey, we've noticed that you're running an outdated version of PHP which is no longer supported. Make sure your site is fast and secure, by %1\$s. Neve's minimal requirement is PHP%2\$s.", 'neve' ),
			sprintf(
			/* translators: %s message to upgrade PHP to the latest version */
				'<a href="https://wordpress.org/support/upgrade-php/">%s</a>',
				__( 'upgrading PHP to the latest version', 'neve' )
			),
			'7.0'
		)
	);
}
/**
 * A list of files to check for existance before bootstraping.
 *
 * @var array Files to check for existance.
 */

$_files_to_check = defined( 'NEVE_IGNORE_SOURCE_CHECK' ) ? [] : [
	NEVE_MAIN_DIR . 'vendor/autoload.php',
	NEVE_MAIN_DIR . 'style-main-new.css',
	NEVE_MAIN_DIR . 'assets/js/build/modern/frontend.js',
	NEVE_MAIN_DIR . 'assets/apps/dashboard/build/dashboard.js',
	NEVE_MAIN_DIR . 'assets/apps/customizer-controls/build/controls.js',
];
foreach ( $_files_to_check as $_file_to_check ) {
	if ( ! is_file( $_file_to_check ) ) {
		$_neve_bootstrap_errors->add(
			'build_missing',
			sprintf(
			/* translators: %s: commands to run the theme */
				__( 'You appear to be running the Neve theme from source code. Please finish installation by running %s.', 'neve' ), // phpcs:ignore WordPress.Security.EscapeOutput
				'<code>composer install --no-dev &amp;&amp; yarn install --frozen-lockfile &amp;&amp; yarn run build</code>'
			)
		);
		break;
	}
}
/**
 * Adds notice bootstraping errors.
 *
 * @internal
 * @global WP_Error $_neve_bootstrap_errors
 */
function _neve_bootstrap_errors() {
	global $_neve_bootstrap_errors;
	printf( '<div class="notice notice-error"><p>%1$s</p></div>', $_neve_bootstrap_errors->get_error_message() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

if ( $_neve_bootstrap_errors->has_errors() ) {
	/**
	 * Add notice for PHP upgrade.
	 */
	add_filter( 'template_include', '__return_null', 99 );
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	add_action( 'admin_notices', '_neve_bootstrap_errors' );

	return;
}

/**
 * Themeisle SDK filter.
 *
 * @param array $products products array.
 *
 * @return array
 */
function neve_filter_sdk( $products ) {
	$products[] = get_template_directory() . '/style.css';

	return $products;
}

add_filter( 'themeisle_sdk_products', 'neve_filter_sdk' );
add_filter(
	'themeisle_sdk_compatibilities/' . NEVE_BASENAME,
	function ( $compatibilities ) {

		$compatibilities['NevePro'] = [
			'basefile'  => defined( 'NEVE_PRO_BASEFILE' ) ? NEVE_PRO_BASEFILE : '',
			'required'  => '2.1',
			'tested_up' => '2.4',
		];

		return $compatibilities;
	} 
);
require_once 'globals/migrations.php';
require_once 'globals/utilities.php';
require_once 'globals/hooks.php';
require_once 'globals/sanitize-functions.php';
require_once get_template_directory() . '/start.php';

/**
 * If the new widget editor is available,
 * we re-assign the widgets to hfg_footer
 */
if ( neve_is_new_widget_editor() ) {
	/**
	 * Re-assign the widgets to hfg_footer
	 *
	 * @param array  $section_args The section arguments.
	 * @param string $section_id The section ID.
	 * @param string $sidebar_id The sidebar ID.
	 *
	 * @return mixed
	 */
	function neve_customizer_custom_widget_areas( $section_args, $section_id, $sidebar_id ) {
		if ( strpos( $section_id, 'widgets-footer' ) ) {
			$section_args['panel'] = 'hfg_footer';
		}

		return $section_args;
	}

	add_filter( 'customizer_widgets_section_args', 'neve_customizer_custom_widget_areas', 10, 3 );
}

require_once get_template_directory() . '/header-footer-grid/loader.php';


add_action('wp_head', 'ajaxurl');

function ajaxurl() {

    echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action('wp_ajax_your_action_name', 'prefix_ajax_your_action_name');
add_action('wp_ajax_nopriv_your_action_name', 'prefix_ajax_your_action_name');

function prefix_ajax_your_action_name() {
    
    global $wpdb;

   // Please put you PHP code here
    $propertyAdd = $_POST['propertyAdd'];
    $purchasePrice = $_POST['purchasePrice'];
    $estimateAmount = $_POST['estimateAmount'];
    $estimateArv = $_POST['estimateArv'];
    $desiredNetCash = $_POST['desiredNetCash'];
    $income = $_POST['income'];
    $expenses = $_POST['expenses'];
    $equityAmt = $_POST['equityAmt'];
    $downPaymentPercentt = $_POST['downPaymentPercent'];
    $closingCostPercent = $_POST['closingCostPercent'];
    $percent = $_POST['percent'];
    $downPaymentPercent = $_POST['downPaymentPercent'];
    $closingCostPercent = $_POST['closingCostPercent'];
    $closingCost = $_POST['closingCost'];
    $totalPocket = $_POST['totalPocket'];
    $loanAmt = $_POST['loanAmt'];
    $estimateNetCash = $_POST['estimateNetCash'];
    $IspocketCost = $_POST['IspocketCost'];
    $rightLocation = $_POST['rightLocation'];
    $stabilized = $_POST['stabilized'];
    
    
	$estimareNetCashFlow = $income - $expenses;

	$ARV = $estimateArv;

	$maxInvestment = $ARV * $percent/100;
    
    $equityAmt = $maxInvestment;
    
    $purchaseRehab = $purchasePrice + $estimateAmount;
    
    $equityFundAvail = $equityAmt - $purchaseRehab; 
    
    $purchaseplusrehab = $purchaseRehab; 
    
    $downPayment = $purchaseplusrehab * $downPaymentPercent/100;  
    
    $closingCost = $purchaseplusrehab * $closingCostPercent/100;
    
    $totalPocket = $downPayment + $closingCost; 
    
    $loanAmt = $purchaseplusrehab - $downPayment;
    
    $estimateNetCash = $estimareNetCashFlow;
    
    if($equityFundAvail >= $totalPocket )
    {
        $IspocketCost = 'Yes';
    }
    else{
        $IspocketCost = 'No';        
    }  

    if($estimareNetCashFlow >= $estimateNetCash){
        $estval = 'Yes';
    }
    
    if($purchaseRehab <= $equityAmt){
        $eqtval = 'Yes';    
    }else{
        $eqtval = 'No';        
    }    
    
    $finalpropertyadd = str_replace(' ', '%20', $propertyAdd);
    //echo $finalpropertyadd; exit('yes');
    //echo "https://api.bridgedataoutput.com/api/v2/zestimates_v2/zestimates?access_token=6eefa4e051a54b917cbb84bc1864c96f&near=$finalpropertyadd";
    
   //$table_name = $wpdb->prefix . "7n21_mortgage_calc"; 
   
   $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.bridgedataoutput.com/api/v2/zestimates_v2/zestimates?access_token=6eefa4e051a54b917cbb84bc1864c96f&radius=1&near=$finalpropertyadd",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: cbb72c7f-11cf-0c82-5756-5f999db31c53"
          ),
        ));
    
    $response1 = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
    
     $result = json_decode($response1, true);
      //echo '<pre>';
      //print_r($result);
      //echo '</pre>'; exit('Yes');
       $out = '';
      
        foreach ($result['bundle'] as $getresult){
            $city = $getresult['city'];
            $state = $getresult['state'];
            $streetName = $getresult['streetName'];
            $address = $getresult['address'];
            $Latitude = $getresult['Latitude'];
            $Longitude = $getresult['Longitude'];
            $zestimate = $getresult['zestimate'];
            $distanceFrom = $getresult['distanceFrom'];
            $houseNumber = $getresult['houseNumber'];
            $highPercent = $getresult['highPercent'];
            $postalCode = $getresult['postalCode'];
            $rentalZestimate = $getresult['rentalZestimate'];
            $zillowUrl = $getresult['zillowUrl'];
            //print_r($getresult); 
            
           
           $out .= '<div class="nearby">' ;
            $out .= '<ul class="nearby-table home-section-1 home-custom">';
           // $out .= '<li class="parent">City: '.$city.'</li>';
           // $out .= '<li class="parent">State: '.$state.'</li>';
           // $out .= '<li class="parent">Street Name: '.$streetName.'</li>';
            $out .= '<li class="parent address-div width-100">'.$address.'</li>';
            $out .= '<li class="parent price-div width-100">Zestimate: '.$zestimate.'</li>';
            $out .= '<li class="parent width-100">House number: '.$houseNumber.'</li>';
            $out .= '<li class="parent width-50">Latitude: '.$Latitude.'</li>';
            $out .= '<li class="parent width-50">Longitude: '.$Longitude.'</li>';     
         //   $out .= '<li class="parent">DistanceFrom: '.$distanceFrom.'</li>';
           // $out .= '<li class="parent">High Percent: '.$highPercent.'</li>';
            $out .= '<li class="parent width-50">Postal Code: '.$postalCode.'</li>';
            $out .= '<li class="parent width-50">Rental Zestimate: '.$rentalZestimate.'</li>';
          //  $out .= '<li class="parent">Zillow URL: '.$zillowUrl.'</li><br/>';
        $out .= '</ul>';
         $out .= '</div>';
         
        
            
        }
        
        $loc = '';
        $unilat = '';
        $unilog = '';
        foreach ($result['bundle'] as $getresult1){
            //print_r($result['bundle']);
            
            
            
            $address = $getresult1['address'];
            $Latitude = $getresult1['Latitude'];
            $Longitude = $getresult1['Longitude'];
            $loc .= '["'.$address.'", '.$Latitude.', '.$Longitude.'],';
            
        }
        //echo $loc.'<br/>';
        
        
        //echo $city.'<br/>';
      // exit('yes');
      $unilat .= $result['bundle'][0]['Latitude'];
      $unilog .= $result['bundle'][0]['Longitude'];
      
     
    }
    //echo $unilat;
    //echo $unilog;
   
   
   $map = '';
   $map .="<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCm8MmklJLI0UUQrI5zWB95nup7z35Ky8Y&callback=initialize&libraries=places&v=weekly&channel=2' async></script>";
   $map .="<script>
    var locations = [
            $loc
        ];
        function InitMap() {
            var map = new google.maps.Map(document.getElementById('map_canvas'), {
                zoom: 15,
                center: new google.maps.LatLng($unilat, $unilog),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            var infowindow = new google.maps.InfoWindow();
            var marker, i;
            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map
                });
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
        }
   </script>";
  $map .="<div id='map_canvas' style='width:750px; height: 800px; border: 0px solid #3872ac;'></div>";
   
   
   $wpdb->insert( 
	'7n21_mortgage_calc', 
    	array( 
        	'estimareNetCashFlow' => $estimareNetCashFlow, 
        	'ARV' => $ARV, 
    		'maxInvestment' => $maxInvestment, 
    		'equityAmt' => $equityAmt, 
    		'purchaseRehab' => $purchaseRehab,
    		'equityFundAvail' => $equityFundAvail,
    		'purchaseplusrehab' => $purchaseplusrehab,
    		'downPayment' => $downPayment,
    		'closingCost' => $closingCost,
    		'closingCostPercent' => $closingCostPercent,
    		'totalPocket' => $totalPocket,
    		'loanAmt' => $loanAmt,
    		'estimateNetCash' => $estimateNetCash,
    		'estval' => $estval,
    		'eqtval' => $eqtval,
    		'IspocketCost' => $IspocketCost,
    		'rightLocation' => $rightLocation,
    		'stabilized' => $stabilized,
    		'propertyAdd' => $propertyAdd
    		
    		
    	) 
    );
	$response = array(
		'estimareNetCashFlow' => number_format($estimareNetCashFlow), 
		'ARV' => number_format($ARV), 
		'maxInvestment' => number_format($maxInvestment), 
		'equityAmt' => number_format($equityAmt), 
		'purchaseRehab' => number_format($purchaseRehab),
		'equityFundAvail' => number_format($equityFundAvail),
		'purchaseplusrehab' => number_format($purchaseplusrehab),
		'downPayment' => number_format($downPayment),
		'closingCost' => number_format($closingCost),
		'closingCostPercent' => number_format($closingCostPercent),
		'totalPocket' => number_format($totalPocket),
		'loanAmt' => number_format($loanAmt),
		'estimateNetCash' => number_format($estimateNetCash), 
		'nearbyproperty' => $out,
		'map' => $map
		//'getresult' => ($getresult)
	);
	echo json_encode($response); 
	exit();
	
}

add_action('wp_head', 'ajaxurl1');

function ajaxurl1() {

    echo '<script type="text/javascript">
           var ajaxurl1 = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action('wp_ajax_your_action_name1', 'prefix_ajax_your_action_name1');
add_action('wp_ajax_nopriv_your_action_name1', 'prefix_ajax_your_action_name1');

function prefix_ajax_your_action_name1() {

   // Please put you PHP code here
    $propertyAdd = $_POST['propertyAdd'];
    $purchasePrice = $_POST['purchasePrice'];
    $estimateAmount = $_POST['estimateAmount'];
    $estimateArv = $_POST['estimateArv'];
    $desiredNetCash = $_POST['desiredNetCash'];
    $income = $_POST['income'];
    $expenses = $_POST['expenses'];
    $equityAmt = $_POST['equityAmt'];
    $downPaymentPercentt = $_POST['downPaymentPercent'];
    $closingCostPercent = $_POST['closingCostPercent'];
    $percent = $_POST['percent'];
    $downPaymentPercent = $_POST['downPaymentPercent'];
    $closingCostPercent = $_POST['closingCostPercent'];
    $closingCost = $_POST['closingCost'];
    $totalPocket = $_POST['totalPocket'];
    $loanAmt = $_POST['loanAmt'];
    $estimateNetCash = $_POST['estimateNetCash'];
    $IspocketCost = $_POST['IspocketCost'];
    $rightLocation = $_POST['rightLocation'];
    $stabilized = $_POST['stabilized'];
    
    
	$estimareNetCashFlow = $income - $expenses;

	$ARV = $estimateArv;

	$maxInvestment = $ARV * $percent/100;
    
    $equityAmt = $maxInvestment;
    
    $purchaseRehab = $purchasePrice + $estimateAmount;
    
    $equityFundAvail = $equityAmt - $purchaseRehab; 
    
    $purchaseplusrehab = $purchaseRehab; 
    
    $downPayment = $purchaseplusrehab * $downPaymentPercent/100;  
    
    $closingCost = $purchaseplusrehab * $closingCostPercent/100;
    
    $totalPocket = $downPayment + $closingCost; 
    
    $loanAmt = $purchaseplusrehab - $downPayment;
    
    $estimateNetCash = $estimareNetCashFlow;
    
    if($equityFundAvail >= $totalPocket )
    {
        $IspocketCost = 'Yes';
    }
    else{
        $IspocketCost = 'No';        
    }  

    if($estimareNetCashFlow >= $estimateNetCash){
        $estval = 'Yes';
    }
    
    if($purchaseRehab <= $equityAmt){
        $eqtval = 'Yes';    
    }else{
        $eqtval = 'No';        
    }    
    
    $finalpropertyadd = str_replace(' ', '%20', $propertyAdd);
    //echo $finalpropertyadd; exit('yes');
    //echo "https://api.bridgedataoutput.com/api/v2/zestimates_v2/zestimates?access_token=6eefa4e051a54b917cbb84bc1864c96f&near=$finalpropertyadd";
    
   //$table_name = $wpdb->prefix . "7n21_mortgage_calc"; 
   
   $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.bridgedataoutput.com/api/v2/zestimates_v2/zestimates?access_token=6eefa4e051a54b917cbb84bc1864c96f&radius=1&near=$finalpropertyadd",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",
            "postman-token: cbb72c7f-11cf-0c82-5756-5f999db31c53"
          ),
        ));
    
    $response2 = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
    
     $result = json_decode($response2, true);
      //echo '<pre>';
      //print_r($result);
      //echo '</pre>'; exit('Yes');
       $out = '';
      
        foreach ($result['bundle'] as $getresult){
            $city = $getresult['city'];
            $state = $getresult['state'];
            $streetName = $getresult['streetName'];
            $address = $getresult['address'];
            $Latitude = $getresult['Latitude'];
            $Longitude = $getresult['Longitude'];
            $zestimate = $getresult['zestimate'];
            $distanceFrom = $getresult['distanceFrom'];
            $houseNumber = $getresult['houseNumber'];
            $highPercent = $getresult['highPercent'];
            $postalCode = $getresult['postalCode'];
            $rentalZestimate = $getresult['rentalZestimate'];
            $zillowUrl = $getresult['zillowUrl'];
            //print_r($getresult); 
            
           
           $out .= '<div class="nearby">' ;
            $out .= '<ul class="nearby-table home-section-1 home-custom">';
           // $out .= '<li class="parent-menu-cust">City: '.$city.'</li>';
           // $out .= '<li class="parent-menu-cust">State: '.$state.'</li>';
           // $out .= '<li class="parent-menu-cust">Street Name: '.$streetName.'</li>';
            $out .= '<li class="parent-menu-cust address-div width-100">'.$address.'</li>';
            $out .= '<li class="parent-menu-cust price-div width-100">Zestimate: '.$zestimate.'</li>';
            $out .= '<li class="parent-menu-cust width-100">House number: '.$houseNumber.'</li>';
            $out .= '<li class="parent-menu-cust width-50">Latitude: '.$Latitude.'</li>';
            $out .= '<li class="parent-menu-cust width-50">Longitude: '.$Longitude.'</li>';     
         //   $out .= '<li class="parent-menu-cust">DistanceFrom: '.$distanceFrom.'</li>';
           // $out .= '<li class="parent-menu-cust">High Percent: '.$highPercent.'</li>';
            $out .= '<li class="parent-menu-cust width-50">Postal Code: '.$postalCode.'</li>';
            $out .= '<li class="parent-menu-cust width-50">Rental Zestimate: '.$rentalZestimate.'</li>';
          //  $out .= '<li class="parent-menu-cust">Zillow URL: '.$zillowUrl.'</li><br/>';
        $out .= '</ul>';
         $out .= '</div>';
         
        
            
        }
        
        $loc = '';
        $unilat = '';
        $unilog = '';
        foreach ($result['bundle'] as $getresult1){
            //print_r($result['bundle']);
            
            
            
            $address = $getresult1['address'];
            $Latitude = $getresult1['Latitude'];
            $Longitude = $getresult1['Longitude'];
            $zestimate = $getresult1['zestimate'];
            $zillowUrl = $getresult1['zillowUrl'];
            $loc .= '["'.$address.'", '.$Latitude.', '.$Longitude.', '.$zestimate.', "'.$zillowUrl.'"],';
            
        }
        //echo $loc.'<br/>';
        
        
        //echo $city.'<br/>';
      // exit('yes');
      $unilat .= $result['bundle'][0]['Latitude'];
      $unilog .= $result['bundle'][0]['Longitude'];
      
     
    }
    //echo $unilat;
    //echo $unilog;
   
   
   $map = '';
   $map .="<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyCm8MmklJLI0UUQrI5zWB95nup7z35Ky8Y&callback=initialize&libraries=places&v=weekly&channel=2' async></script>";
   $map .="<script>
    var locations = [
            $loc
        ];
        function InitMap() {
            var map = new google.maps.Map(document.getElementById('map_canvas'), {
                zoom: 15,
                center: new google.maps.LatLng($unilat, $unilog),
                mapTypeId: google.maps.MapTypeId.SATELLITE
            });
            var infowindow = new google.maps.InfoWindow();
            var iconBase =
    'http://maps.google.com/mapfiles/ms/icons/blue.png'; 
            var marker, i;
            for (i = 0; i < locations.length; i++) {
                var zestimate = locations[i][3];
                var first2Str = String(zestimate).slice(0, 3); 
                var first2Num = Number(first2Str);
                //console.log(first2Num);
                var lab = ' $'+first2Num+'K ';
                
                //console.log(zestimate);
                marker = new google.maps.Marker({
                    //icon: iconBase,
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    animation: google.maps.Animation.DROP,
                    icon: {
                     url: iconBase,
                     labelOrigin: { x: 15, y: 30}
                   },
                   label: {
                     text: lab,
                     color: '#fff',
                     fontSize: '12px',
                     className: 'maplabel',
                     //labelClass: 'label123',
                     //labelInBackground: true,
                     //labelClass: 'labels'
                   },
                   //labelClass: 'label123',
                   //labelInBackground: true,
                    /*label: {
                      text: 'ABCD',
                      color: '#000',
                      fontSize: '10px',
                      fontWeight: 'bold',
                      fontFamily: 'custom-label'
                    },*/
                });
                google.maps.event.addListener(marker, 'mouseover', (function (marker, i) {
                    
                    return function () {
                        var address = locations[i][0];
                        var zestimate = locations[i][3];
                        var url = locations[i][4];
                        var num = new Intl.NumberFormat('en-IN', { maximumSignificantDigits: 3 }).format(zestimate);
                        var html = '<div><h5>'+address+'</h5><p>Zestimate: $'+addCommas(zestimate)+'</p><a href='+url+'>View location</a></div>';
                        infowindow.setContent(html);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
                
                
            }
        }
        
        function addCommas(nStr)
        {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }
   </script>";
   
   
   /*$map .="<script>
        var locations = [
            $loc
        ];
        
        var geocoder;
        var map;
        var bounds = new google.maps.LatLngBounds();
        
        function InitMap() {
            map = new google.maps.Map(
            document.getElementById('map_canvas'), {
                center: new google.maps.LatLng($unilat, $unilog),
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });
            geocoder = new google.maps.Geocoder();
        
            for (i = 0; i < locations.length; i++) {
        
        
                geocodeAddress(locations, i);
            }
        }
        google.maps.event.addDomListener(window, 'load', InitMap);
        
        function geocodeAddress(locations, i) {
            var title = locations[i][1];
            var address = locations[i][0];
            var url = locations[i][2];
            geocoder.geocode({
                'address': locations[i][0]
            },
        
            function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var marker = new google.maps.Marker({
                        icon: 'http://maps.google.com/mapfiles/ms/icons/blue.png',
                        map: map,
                        position: results[0].geometry.location,
                        title: title,
                        animation: google.maps.Animation.DROP,
                        address: address,
                        url: url
                    })
                    infoWindow(marker, map, title, address, url);
                    bounds.extend(marker.getPosition());
                    map.fitBounds(bounds);
                } else {
                    alert('');
                }
            });
        }
        
        function infoWindow(marker, map, title, address, url) {
            google.maps.event.addListener(marker, 'click', function () {
                var html = '<div><h3>" + title + "</h3><p>" + address + "<br></div><a href='" + url + "'>View location</a></p></div>';
                iw = new google.maps.InfoWindow({
                    content: html,
                    maxWidth: 350
                });
                iw.open(map, marker);
            });
        }
        
        function createMarker(results) {
            var marker = new google.maps.Marker({
                icon: 'http://maps.google.com/mapfiles/ms/icons/blue.png',
                map: map,
                position: results[0].geometry.location,
                title: title,
                animation: google.maps.Animation.DROP,
                address: address,
                url: url
            })
            bounds.extend(marker.getPosition());
            map.fitBounds(bounds);
            infoWindow(marker, map, title, address, url);
            return marker;
        }
   </script>";*/
   
   
  $map .="<div id='map_canvas' style='width:750px; height: 800px; border: 0px solid #3872ac;'></div>";
   
  
    $response1 = array(
		'estimareNetCashFlow' => number_format($estimareNetCashFlow), 
		'ARV' => number_format($ARV), 
		'maxInvestment' => number_format($maxInvestment), 
		'equityAmt' => number_format($equityAmt), 
		'purchaseRehab' => number_format($purchaseRehab),
		'equityFundAvail' => number_format($equityFundAvail),
		'purchaseplusrehab' => number_format($purchaseplusrehab),
		'downPayment' => number_format($downPayment),
		'closingCost' => number_format($closingCost),
		'closingCostPercent' => number_format($closingCostPercent),
		'totalPocket' => number_format($totalPocket),
		'loanAmt' => number_format($loanAmt),
		'estimateNetCash' => number_format($estimateNetCash), 
		'nearbyproperty' => $out,
		'map' => $map
		//'getresult' => ($getresult)
	);
	echo json_encode($response1); 
	exit();

}