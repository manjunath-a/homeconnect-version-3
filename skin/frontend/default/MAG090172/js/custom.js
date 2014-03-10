var widthClassOptions = [];
var widthClassOptions = ({
		bestseller       : 'bestseller_default_width',
		newproduct       : 'newproduct_default_width',
		featured         : 'featured_default_width',
		special          : 'special_default_width',
		additional       : 'additional_default_width',
		related          : 'related_default_width',
		upsell	         : 'upsell_default_width',
		crosssell        : 'crosssell_default_width',
		brand			 : 'brand_default_width'
});

var $k =jQuery.noConflict();
$k(document).ready(function(){
	
	$k('input[type="checkbox"]').tmMark(); 
	$k('input[type="radio"]').tmMark();	
	
	$k(".form-language select").selectbox();	
	$k(".tm_top_currency select").selectbox();
	$k(".limiter select").selectbox();
	$k(".sort-by select").selectbox(); 
	$k(".block-brand-nav select").selectbox();
	
	
	$k('.cart-label').click(function() {
     $k('#panel').slideToggle();
     $k("#panel").parent().toggleClass('active').parent().find().slideToggle();
  });  
 

	
	$k('.mega_menu h2.toggle_arrow').click(function() {
		$k('.mega_menu .block-content').slideToggle();
		$k('.mega_menu .mega_bkg').toggleClass('active');		
    });
	
	$k('.nav-responsive').click(function() {
		$k('#nav-mobile').slideToggle();
		$k('.nav-responsive div').toggleClass('active');		
    });
	
	$k("#category-treeview").treeview({
		animated: "slow",
		collapsed: true,
		unique: true		
	});
	
	$k(document).ready(function(){
	  $k(".tm_headerlinks_inner").click(function(){
		$k(".tm_headerlinkmenu .links").slideToggle('slow');
	  });
	}); 
	 
});

	 
 
function mobileToggleMenu(){
	
	if ($k(window).width() < 980)
	{
		$k("#footer .mobile_togglemenu,#footer .block .block-title .mobile_togglemenu").remove();
		$k("#footer h6 , #footer .block .block-title").append( "<a class='mobile_togglemenu'>&nbsp;</a>" );
		$k("#footer h6 , #footer .block .block-title").addClass('toggle');
		$k("#footer .mobile_togglemenu ,#footer .block .block-title .mobile_togglemenu ").click(function(){
			$k(this).parent().toggleClass('active').parent().find('ul').toggle('slow');
		});

	}else{
		$k("#footer h6 , #footer .block .block-title").parent().find('ul').removeAttr('style');
		$k("#footer h6 , #footer .block .block-title").removeClass('active');
		$k("#footer h6 , #footer .block .block-title").removeClass('toggle');
		$k("#footer .mobile_togglemenu ,#footer .block .block-title .mobile_togglemenu ").remove();
	}	
}
$k(document).ready(function(){mobileToggleMenu();});
$k(window).resize(function(){mobileToggleMenu();}); 

function mobileToggleColumn(){
	
	if ($k(window).width() < 768){	 
		$k('.sidebar .mobile_togglecolumn').remove();
		$k(".sidebar .block-title" ).append( "<span class='mobile_togglecolumn'>&nbsp;</span>" );		 
		$k(".sidebar .block-title" ).addClass('toggle');	 
		$k(".sidebar .mobile_togglecolumn").click(function(){
			$k(this).parent().toggleClass('active').parent().find('.block-content').toggle('slow');
		});
	}
	else{ 
		$k(".sidebar .block-title" ).parent().find('.block-content').removeAttr('style');		 
		$k(".sidebar .block-title" ).removeClass('toggle');		 
		$k(".sidebar .block-title" ).removeClass('active');
		$k('.sidebar .mobile_togglecolumn').remove();		 
	}
}
$k(document).ready(function(){mobileToggleColumn();});
$k(window).resize(function(){mobileToggleColumn();});


function menuResponsive(){
	 
	if ($k(window).width() < 980){ 
		$k('#advancedmenu').css('display','none');
		$k('.advanced_nav').css('display','none');
		$k('.nav-responsive').css('display','block');
		 var elem = document.getElementById("nav");
				 if(typeof elem !== 'undefined' && elem !== null) {
					document.getElementById("nav").id= "nav-mobile";
		
		if($k('.main-navigation').hasClass('treeview')!=true){
			$k(".nav-inner").addClass('responsive-menu');			 
			$k(".nav-inner #nav-mobile").treeview({
				animated: "slow",
				collapsed: true,
				unique: true		
			});
			$k('.nav-inner #nav-mobile a.active').parent().removeClass('expandable');
			$k('.nav-inner #nav-mobile a.active').parent().addClass('collapsable');
			$k('.nav-inner #nav-mobile .collapsable ul').css('display','block');	
			
		}
	  } 	 
	}else{
		$k('#advancedmenu').css('display','block');
		$k('.advanced_nav').css('display','block');
		$k('.nav-responsive').css('display','none');		
		$k(".nav-inner .hitarea").remove();
		$k(".nav-inner").removeClass('responsive-menu');	 
		$k("#nav-mobile").removeClass('treeview');
		$k(".nav-inner ul").removeAttr('style');
		$k('.nav-inner li').removeClass('expandable');
		$k('.nav-inner li').removeClass('collapsable');
		$k('.nav-responsive').css('display','none');		 
		 var elem = document.getElementById("nav-mobile");
			 if(typeof elem !== 'undefined' && elem !== null) {
				document.getElementById("nav-mobile").id= "nav";
		}
	
	}

}
$k(document).ready(function(){menuResponsive();});
$k(window).resize(function(){menuResponsive();});
 
 
function productCarouselAutoSet() { 
	$k(".main .product-carousel, .footer .product-carousel , .additional-carousel .product-carousel").each(function() {
		var objectID = $k(this).attr('id');
		var myObject = objectID.replace('-carousel','');
		if(myObject.indexOf("-") >= 0)
			myObject = myObject.substring(0,myObject.indexOf("-"));		
		if(widthClassOptions[myObject])
			var myDefClass = widthClassOptions[myObject];			
		else
			var myDefClass= 'grid_default_width';
		var slider = $k(".main #" + objectID + ", .footer #" + objectID + " , .additional-carousel #"+ objectID );		
		slider.sliderCarousel({
			defWidthClss : myDefClass,
			subElement   : '.slider-item',
			subClass     : 'product-block',
			firstClass   : 'first_item_tm',
			lastClass    : 'last_item_tm',
			slideSpeed : 200,
			paginationSpeed : 800,
			autoPlay : false,
			stopOnHover : false,
			goToFirst : true,
			goToFirstSpeed : 1000,
			goToFirstNav : true,
			pagination : false,
			paginationNumbers: false,
			responsive: true,
			responsiveRefreshRate : 200,
			baseClass : "slider-carousel",
			theme : "slider-theme",
			autoHeight : true
		});
		
		var nextButton = $k(this).parent().find('.next');
		var prevButton = $k(this).parent().find('.prev');
		nextButton.click(function(){
			slider.trigger('slider.next');
		})
		prevButton.click(function(){
			slider.trigger('slider.prev');
		})
	});
}
//$(window).load(function(){productCarouselAutoSet();});
$k(document).ready(function(){productCarouselAutoSet();});


function productListAutoSet() { 
	$k("ul.products-grid").each(function(){	
	var objectID = $k(this).attr('id');
		if(objectID.length >0){		
			if(widthClassOptions[objectID.replace('-grid','')])
				var myDefClass= widthClassOptions[objectID.replace('-grid','')];
			}else{ 
			var myDefClass= 'grid_default_width';			
		}	
		$k(this).smartColumnsRows({
			defWidthClss : myDefClass,
			subElement   : 'li',
			subClass     : 'product-block'
		});
	});		
}
$k(window).load(function(){productListAutoSet();});
$k(window).resize(function(){productListAutoSet();}); 

 
function tableMakeResponsive(){
	 if ($k(window).width() < 640){
		// SHOPPING CART TABLE
		if($k("table#shopping-cart-table").length != 0) {
			if($k("#cart-shopping-table").length == 0) {
				$k('<div id="cart-shopping-table"></div>').insertBefore('.cart-table');
			}
			$k('table#shopping-cart-table').addClass("table-responsive");
			$k('table#shopping-cart-table thead').addClass("table-head");
			$k('table#shopping-cart-table tfoot').addClass("table-foot");
			$k('table#shopping-cart-table tr').addClass("row-responsive");
			$k('table#shopping-cart-table td').addClass("column-responsive clearfix");
			$k("table#shopping-cart-table").responsiveTable({prefix:'tm_responsive',target:'#cart-shopping-table'});
		}		
	}else{
		// SHOPPING CART TABLE
		if($k("table#shopping-cart-table").length != 0) {
			$k('table#shopping-cart-table').removeClass("table-responsive");
			$k('table#shopping-cart-table thead').removeClass("table-head");
			$k('table#shopping-cart-table tfoot').removeClass("table-foot");
			$k('table#shopping-cart-table tr').removeClass("row-responsive");
			$k('table#shopping-cart-table td').removeClass("column-responsive");
			$k("#cart-shopping-table").remove();
		}
	}
	
	
	 if ($k(window).width() < 640){
		// MULTIPLE ADDRESS TABLE
		if($k("table#multiship-addresses-table").length != 0) {
			if($k("#multiship-shopping-table").length == 0) {
				$k('<div id="multiship-shopping-table"></div>').insertBefore('#multiship-addresses-table');
			}
			$k('table#multiship-addresses-table').addClass("table-responsive");
			$k('table#multiship-addresses-table thead').addClass("table-head");
			$k('table#multiship-addresses-table tfoot').addClass("table-foot");
			$k('table#multiship-addresses-table tr').addClass("row-responsive");
			$k('table#multiship-addresses-table td').addClass("column-responsive clearfix");
			$k("table#multiship-addresses-table").responsiveTable({prefix:'tm_responsive',target:'#multiship-shopping-table'});
		}		
	}else{
		// MULTIPLE ADDRESS TABLE
		if($k("table#multiship-addresses-table").length != 0) {
			$k('table#multiship-addresses-table').removeClass("table-responsive");
			$k('table#multiship-addresses-table thead').removeClass("table-head");
			$k('table#multiship-addresses-table tfoot').removeClass("table-foot");
			$k('table#multiship-addresses-table tr').removeClass("row-responsive");
			$k('table#multiship-addresses-table td').removeClass("column-responsive");
			$k("#multiship-shopping-table").remove();
		}
	}

	 if ($k(window).width() < 640){
		// CHECKOUT TABLE
		if($k("table#checkout-review-table").length != 0) {
			if($k("#review-checkout-table").length == 0) {
				$k('<div id="review-checkout-table"></div>').insertBefore('#checkout-review-table-wrapper');
			}
			$k('table#checkout-review-table').addClass("table-responsive");
			$k('table#checkout-review-table thead').addClass("table-head");
			$k('table#checkout-review-table tfoot').addClass("table-foot");
			$k('table#checkout-review-table tr').addClass("row-responsive");
			$k('table#checkout-review-table td').addClass("column-responsive clearfix");
			$k("table#checkout-review-table").responsiveTable({prefix:'tm_responsive',target:'#review-checkout-table'});
		}		
	}else{
		// CHECKOUT TABLE
		if($k("table#checkout-review-table").length != 0) {
			$k('table#checkout-review-table').removeClass("table-responsive");
			$k('table#checkout-review-table thead').removeClass("table-head");
			$k('table#checkout-review-table tfoot').removeClass("table-foot");
			$k('table#checkout-review-table tr').removeClass("row-responsive");
			$k('table#checkout-review-table td').removeClass("column-responsive");
			$k("#review-checkout-table").remove();
		}
	}
	
	 if ($k(window).width() < 640){
		// OREDER TABLE
		if($k("table#my-orders-table").length != 0) {
			if($k("#order-table").length == 0) {
				$k('<div id="order-table"></div>').insertBefore('#my-orders-table');
			}
			$k('table#my-orders-table').addClass("table-responsive");
			$k('table#my-orders-table thead').addClass("table-head");
			$k('table#my-orders-table tfoot').addClass("table-foot");
			$k('table#my-orders-table tr').addClass("row-responsive");
			$k('table#my-orders-table td').addClass("column-responsive clearfix");
			$k("table#my-orders-table").responsiveTable({prefix:'tm_responsive',target:'#order-table'});
		}		
	}else{
		// OREDER TABLE
		if($k("table#my-orders-table").length != 0) {
			$k('table#my-orders-table').removeClass("table-responsive");
			$k('table#my-orders-table thead').removeClass("table-head");
			$k('table#my-orders-table tfoot').removeClass("table-foot");
			$k('table#my-orders-table tr').removeClass("row-responsive");
			$k('table#my-orders-table td').removeClass("column-responsive");
			$k("#order-table").remove();
		}
	}
	
	 if ($k(window).width() < 640){
		// SUPER PRODUCT TABLE
		if($k("table#super-product-table").length != 0) {
			if($k("#super-table").length == 0) {
				$k('<div id="super-table"></div>').insertBefore('#super-product-table');
			}
			$k('table#super-product-table').addClass("table-responsive");
			$k('table#super-product-table thead').addClass("table-head");
			$k('table#super-product-table tfoot').addClass("table-foot");
			$k('table#super-product-table tr').addClass("row-responsive");
			$k('table#super-product-table td').addClass("column-responsive clearfix");
			$k("table#super-product-table").responsiveTable({prefix:'tm_responsive',target:'#super-table'});
		}		
	}else{
		// SUPER PRODUCT TABLE
		if($k("table#super-product-table").length != 0) {
			$k('table#super-product-table').removeClass("table-responsive");
			$k('table#super-product-table thead').removeClass("table-head");
			$k('table#super-product-table tfoot').removeClass("table-foot");
			$k('table#super-product-table tr').removeClass("row-responsive");
			$k('table#super-product-table td').removeClass("column-responsive");
			$k("#super-table").remove();
		}
	}
	
	 if ($k(window).width() < 640){
		// WISHLIST TABLE
		if($k("table#wishlist-table").length != 0) {
			if($k("#new-wishlist-table").length == 0) {
				$k('<div id="new-wishlist-table"></div>').insertBefore('#wishlist-table');
			}
			$k('table#wishlist-table').addClass("table-responsive");
			$k('table#wishlist-table thead').addClass("table-head");
			$k('table#wishlist-table tfoot').addClass("table-foot");
			$k('table#wishlist-table tr').addClass("row-responsive");
			$k('table#wishlist-table td').addClass("column-responsive clearfix");
			$k("table#wishlist-table").responsiveTable({prefix:'tm_responsive',target:'#new-wishlist-table'});
		}		
	}else{
		// WISHLIST TABLE
		if($k("table#wishlist-table").length != 0) {
			$k('table#wishlist-table').removeClass("table-responsive");
			$k('table#wishlist-table thead').removeClass("table-head");
			$k('table#wishlist-table tfoot').removeClass("table-foot");
			$k('table#wishlist-table tr').removeClass("row-responsive");
			$k('table#wishlist-table td').removeClass("column-responsive");
			$k("#new-wishlist-table").remove();
		}
	}
	
	
	if ($k(window).width() < 640){
		// DOWNLOADABLE TABLE
		if($k("table#my-downloadable-products-table").length != 0) {
			if($k("#downloadable-products-table").length == 0) {
				$k('<div id="downloadable-products-table"></div>').insertBefore('#my-downloadable-products-table');
			}			 
			 
			$k('table#my-downloadable-products-table').addClass("table-responsive");
			$k('table#my-downloadable-products-table thead').addClass("table-head");
			$k('table#my-downloadable-products-table tfoot').addClass("table-foot");
			$k('table#my-downloadable-products-table tr').addClass("row-responsive");
			$k('table#my-downloadable-products-table td').addClass("column-responsive clearfix");
			$k("table#my-downloadable-products-table").responsiveTable({prefix:'tm_responsive',target:'#downloadable-products-table'});
		}		
	}else{
		// DOWNLOADABLE TABLE
		if($k("table#my-downloadable-products-table").length != 0) {
			$k('table#my-downloadable-products-table').removeClass("table-responsive");
			$k('table#my-downloadable-products-table thead').removeClass("table-head");
			$k('table#my-downloadable-products-table tfoot').removeClass("table-foot");
			$k('table#my-downloadable-products-table tr').removeClass("row-responsive");
			$k('table#my-downloadable-products-table td').removeClass("column-responsive");
			$k("#downloadable-products-table").remove();
		}
	}
	
}
$k(document).ready(function(){tableMakeResponsive();});
$k(window).resize(function(){tableMakeResponsive();});	


function mobileTabToggle(){
	//alert($(window).width());
	if ($k(window).width() < 980)
	{	
		
		$k(".padder .mobile_togglemenu").remove();
		$k(".padder h6").append( "<h5 class='mobile_togglemenu'>&nbsp;</h5>" );
		$k(".padder h6").addClass('toggle');
		$k(".padder .mobile_togglemenu").click(function(){
			$k(this).parent().toggleClass('active').parent().find('ol').toggle('fast');
		});

	}else{
		$k(".padder h6").parent().find('ol').removeAttr('style');
		$k(".padder h6").removeClass('active');
		$k(".padder h6").removeClass('toggle');
		$k(".padder .mobile_togglemenu").remove();
	}	
}
$k(document).ready(function(){mobileTabToggle();});
$k(window).resize(function(){mobileTabToggle();});