<?php
// Get parameters from URL
$tmdbid  = isset($_GET['tmdbid']) ? trim($_GET['tmdbid']) : '';
$season  = isset($_GET['season']) && is_numeric($_GET['season']) ? $_GET['season'] : 1;
$episode = isset($_GET['episode']) && is_numeric($_GET['episode']) ? $_GET['episode'] : 1;

// Validate required tmdbid
if (empty($tmdbid)) {
    http_response_code(400);
    exit("Missing required parameter: tmdbid");
}

// Prepare POST data
$postFields = http_build_query([
    'tmdbid'  => $tmdbid,
    'season'  => $season,
    'episode' => $episode
]);

// Prepare headers and cookies
$headers = [
    'Content-Type: application/x-www-form-urlencoded',
    'Referer: https://nono.autoembed.cc/tv/' . urlencode($tmdbid) . '/' . urlencode($season) . '/' . urlencode($episode),
    'Cookie: _ga=GA1.1.551506749.1743397173; cf_clearance=FbTlasbn7AWHXB9zznVUxIalcck8y2up_y8Cixl6sh4-1746217522-1.2.1.1-H.w.mF4x1SjytFHt8LZaocriFOth_EotuHvuSeh9m0hWsDe41czxw4JLmhMTAehqrwxm0W8U.oKPTIM.vlOVgSQG5U._FgLQZuVxfCEahaHphVnoGOq00z9fgMxmOVDh2C9cYRT7sPxG7w8SvHulg4dEbQaeT0rSNEKCqxk7LzKd5sPzI2Zi_eInaWJqQ01VGl6KauFLlRsofwCxi0WPcy8JY5gl5anP3jORDR9AX_RlbIPU5ugDw8p4qVAGl_2tRcUMeEDDPOhybcfqo37FWQqKgzIJWjIEJdAhAo_wxux4ztNf0yaXjv55zVAScXcLvkhWnoG58fFc6wOz.vpgQlpohC.c_BxHsh00C5KenDE; _ga_VKQEPNWV0G=GS1.1.1746217520.2.1.1746217941.0.0.0; _ga_JS6Z6479G1=GS1.1.1746217522.2.1.1746217941.0.0.0'
];

// Initialize cURL
$ch = curl_init('https://nono.autoembed.cc/api/decryptVideoSource');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $postFields,
    CURLOPT_HTTPHEADER     => $headers,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_SSL_VERIFYPEER => false, // Set to true if you want to validate SSL
]);

// Execute and output result
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Request Error: ' . curl_error($ch);
} else {
    header('Content-Type: application/json'); // assuming API returns JSON
    echo $response;
}

curl_close($ch);
?>
