<?php
    if($_REQUEST)
        $parameters=$_REQUEST;
    else
        die('No parameters');
    $arraycount=1;
    $masterProductList=array();
    
    $masterProductList[0]=array('result'=>'none');
    try
    {
        $proxy = new SoapClient('http://www.homeconnectonline.com/index.php/api/soap/?wsdl'); 
        $sessionId = $proxy->login($_REQUEST['user'], $_REQUEST['key']);
    
        $categoryId = $_REQUEST['catid']; // Put here your category id (offers)
        $storeId = 0;

        $listOfferProducts = $proxy->call($sessionId, 'catalog_category.assignedProducts' ,array($categoryId, $storeId));
        /*echo "<pre>";
        print_r($listOfferProducts);
        echo "</pre>";*/
        foreach($listOfferProducts as $productList)
        {
            $productInfo = $proxy->call($sessionId,'catalog_product.info',$productList['product_id']);
            /* echo "<pre>";
            //print_r($productInfo);
            echo "--------------<br/><br/>";
            echo "</pre>";*/
    
            foreach($productInfo['categories'] as $cat)
            {
            $catValue=$cat;
            //print_r($categoryName);
            }
            $categoryName = $proxy->call($sessionId,'catalog_category.info',$catValue);       
            $productImage = $proxy->call($sessionId, 'catalog_product_attribute_media.list',  $productInfo['product_id']);
            //print_r($productImage);
            $productId = $productInfo['product_id'];
            $productSku = $productInfo['sku'];
            $productName = $productInfo['name'];
            $productDescription = $productInfo['short_description'];
            $productPrice = $productInfo['price'];
            $productImageUrl = $productImage[0]['url'];
            $productPageUrl = $productInfo['url_path'];
            $productCategory=$productInfo['categories'];
            foreach($productCategory as $category)
            {
                if($category!=$categoryId)            
                {
                    $productDisplayCategory=$category;
                    $categoryName = $proxy->call($sessionId,'catalog_category.info',$productDisplayCategory);
                    $displayCatName=$categoryName['name'];
                }
            }
       
            $productDetail['productId']=$productId;
            $productDetail['sku']=$productSku;
            $productDetail['name']=$productName;
            $productDetail['short_description']=$productDescription;
            $productDetail['price']=$productPrice;
            $productDetail['image_url']=$productImageUrl;
            $productDetail['url_path']="http://www.homeconnectonline.com/index.php/" . $productPageUrl;
            $productDetail['category']=$displayCatName;
            $masterProductList[$arraycount]=$productDetail;
            $arraycount++;
        }
  
        /*echo "<pre>";
        print_r($masterProductList);
        echo "</pre>";*/
        $masterProductList[0]=array('result'=>'success');
         /*echo "------------------<br/><br/><br/>";*/
        //$decodedValue=json_decode($encodedValue);
        //echo "<pre>";
        //print_r($decodedValue);
        //echo "</pre>";
    }
    catch (SoapFault $e)
    {
        $masterProductList[0]=array('result'=>'failure');
    }
    
    $encodedValue=json_encode($masterProductList);
    echo $encodedValue;
?>