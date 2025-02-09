<?php 

if (!defined('ABSPATH')) {
    exit;
}

function abi_fetch_books_from_amazon() {
    $api_key = ABI_API_KEY;
    $api_secret = ABI_API_SECRET;
    $associate_tag = ABI_ASSOCIATE_TAG;
    $genre = get_option('abi_genre', 'fiction');
    
    if (!$api_key || !$api_secret || !$associate_tag) {
        return;
    }

    $url = "https://webservices.amazon.com/paapi5/searchitems";
    $params = array(
        "Keywords" => $genre,
        "Resources" => ["Images.Primary.Medium", "ItemInfo.Title", "ItemInfo.ByLineInfo", "Offers.Listings.Price"],
        "PartnerTag" => $associate_tag,
        "PartnerType" => "Associates",
        "Marketplace" => "www.amazon.com"
    );
    
    $args = array(
        'body'    => json_encode($params),
        'headers' => array(
            'Content-Type'  => 'application/json',
            'X-Amz-Date'    => gmdate('Ymd\THis\Z'),
            'Authorization' => "AWS4-HMAC-SHA256 Credential=$api_key"
        )
    );

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        return;
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if (!isset($body['SearchResult']['Items'])) {
        return;
    }
    
    foreach ($body['SearchResult']['Items'] as $book) {
        abi_store_book($book);
    }
}

function abi_store_book($book) {
    $title = $book['ItemInfo']['Title']['DisplayValue'];
    $author = $book['ItemInfo']['ByLineInfo']['Contributors'][0]['Name'];
    $cover = $book['Images']['Primary']['Medium']['URL'];
    $price = $book['Offers']['Listings'][0]['Price']['DisplayAmount'];
    $url = $book['DetailPageURL'];

    $post_data = array(
        'post_title'    => $title,
        'post_content'  => "$author - $price <br><img src='$cover'><br><a href='$url' target='_blank'>Buy on Amazon</a>",
        'post_status'   => 'publish',
        'post_type'     => 'amazon_books'
    );

    wp_insert_post($post_data);
}
