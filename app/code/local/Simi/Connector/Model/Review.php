<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category 	Magestore
 * @package 	Magestore_Connector
 * @copyright 	Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license 	http://www.magestore.com/license-agreement.html
 */

/**
 * Connector Model Catalog
 * 
 * @category 	Simi
 * @package 	Simi_Connector
 * @author  	Simi Developer
 */
class Simi_Connector_Model_Review extends Simi_Connector_Model_Abstract {

    function getRatingStar($productId) {
        $reviews = Mage::getModel('review/review')
                ->getResourceCollection()
                ->addStoreFilter(Mage::app()->getStore()->getId())
                ->addEntityFilter('product', $productId)
                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                ->setDateOrder()
                ->addRateVotes();
        /**
         * Getting numbers ratings/reviews
         */
        $star = array();
        $star[0] = 0;
        $star[1] = 0;
        $star[2] = 0;
        $star[3] = 0;
        $star[4] = 0;
        $star[5] = 0;
        if (count($reviews) > 0) {
            foreach ($reviews->getItems() as $review) {
                $star[5]++;
                $y = 0;
                foreach ($review->getRatingVotes() as $vote) {
                    $y += ($vote->getPercent() / 20);
                }
                $x = (int) ($y / count($review->getRatingVotes()));
                $z = $y % 3;
                $x = $z < 5 ? $x : $x + 1;
                if ($x == 1) {
                    $star[0]++;
                } elseif ($x == 2) {
                    $star[1]++;
                } elseif ($x == 3) {
                    $star[2]++;
                } elseif ($x == 4) {
                    $star[3]++;
                } elseif ($x == 5) {
                    $star[4]++;
                } elseif ($x == 0) {
                    $star[5]--;
                }
            }
        }
        return $star;
    }

    public function getProductReview($data) {
        $productId = $data->product_id;
        $offset = $data->offset;
        $limit = $data->limit;
        $starProduct = $data->star;

        $storeId = Mage::app()->getStore()->getId();
        $reviews = Mage::getModel('review/review')
                ->getResourceCollection()
                ->addStoreFilter($storeId)
                ->addEntityFilter('product', $productId)
                ->addStatusFilter(Mage_Review_Model_Review::STATUS_APPROVED)
                ->setDateOrder()
                ->addRateVotes();

        $list = array();
        $star = array();
        $count = null;
        $star[0] = 0;
        $star[1] = 0;
        $star[2] = 0;
        $star[3] = 0;
        $star[4] = 0;
        $star[5] = 0;

        if ($offset <= count($reviews) && count($reviews) > 0) {
            $check_limit = 0;
            $check_offset = 0;
            foreach ($reviews->getItems() as $review) {
                if (++$check_offset <= $offset) {
                    continue;
                }
                if (++$check_limit > $limit)
                    break;
                $star[5]++;
                $y = 0;
                foreach ($review->getRatingVotes() as $vote) {
                    $y += ($vote->getPercent() / 20);
                }
                $x = (int) ($y / count($review->getRatingVotes()));
                if (isset($starProduct) && $starProduct) {
                    if ($x == $starProduct) {
                        $list[] = array(
                            'review_id' => $review->getId(),
                            'customer_name' => $review->getNickname(),
                            'review_title' => $review->getTitle(),
                            'review_body' => $review->getDetail(),
                            'review_time' => $review->getCreatedAt(),
                            'rate_point' => $x,
                        );
                    }
                } else {
                    $list[] = array(
                        'review_id' => $review->getId(),
                        'customer_name' => $review->getNickname(),
                        'review_title' => $review->getTitle(),
                        'review_body' => $review->getDetail(),
                        'review_time' => $review->getCreatedAt(),
                        'rate_point' => $x,
                    );
                }
                $z = $y % 3;
                $x = $z < 5 ? $x : $x + 1;
                if ($x == 1) {
                    $star[0]++;
                } elseif ($x == 2) {
                    $star[1]++;
                } elseif ($x == 3) {
                    $star[2]++;
                } elseif ($x == 4) {
                    $star[3]++;
                } elseif ($x == 5) {
                    $star[4]++;
                } elseif ($x == 0) {
                    $star[5]--;
                }
            }
            $arr = array();
            $count = array(
                '1_star' => $star[0],
                '2_star' => $star[1],
                '3_star' => $star[2],
                '4_star' => $star[3],
                '5_star' => $star[4],
            );
            $array[] = $list;
            $array[] = $count;
        }
        $information = $this->statusSuccess();
        $information['data'] = $list;
        $information['count'] = $count;
        return $information;
    }

}

?>