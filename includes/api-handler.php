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

    $total_books_fetched = 0;
    $max_pages = 5;
    $items_per_page = 20;

    for ($page = 1; $page <= $max_pages; $page++) {
        $url = "https://webservices.amazon.com/paapi5/searchitems";
        $params = array(
            "Keywords" => $genre,
            "Resources" => ["Images.Primary.Medium", "ItemInfo.Title", "ItemInfo.ByLineInfo", "Offers.Listings.Price"],
            "PartnerTag" => $associate_tag,
            "PartnerType" => "Associates",
            "Marketplace" => "www.amazon.com",
            "ItemCount" => $items_per_page,
            "ItemPage" => $page
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
            continue;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!isset($body['SearchResult']['Items'])) {
            continue;
        }

        foreach ($body['SearchResult']['Items'] as $book) {
            abi_store_book($book);
            $total_books_fetched++;
        }

        // Stop fetching if no more results are available
        if (count($body['SearchResult']['Items']) < $items_per_page) {
            break;
        }
    }
}

function abi_store_book($book) {
    $title = $book['ItemInfo']['Title']['DisplayValue'];
    $author = $book['ItemInfo']['ByLineInfo']['Contributors'][0]['Name'];
    $cover = $book['Images']['Primary']['Medium']['URL'];
    $price = $book['Offers']['Listings'][0]['Price']['DisplayAmount'];
    $url = $book['DetailPageURL'];

    // Extract genre dynamically from API response
    $genre = !empty($book['ItemInfo']['Classifications']['Binding']) ? $book['ItemInfo']['Classifications']['Binding'] : 'Unknown';

    $post_data = array(
        'post_title'    => $title,
        'post_content'  => "$author - $price <br><img src='$cover'><br><a href='$url' target='_blank'>Buy on Amazon</a>",
        'post_status'   => 'publish',
        'post_type'     => 'amazon_books',
        'meta_input'    => array('book_genre' => $genre)
    );

    wp_insert_post($post_data);
}
