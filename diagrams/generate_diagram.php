<?php
// Read the PlantUML content
$puml_content = file_get_contents('system_architecture.puml');

// Encode the PlantUML content for the URL
$encoded = urlencode($puml_content);
$encoded = str_replace('+', '%20', $encoded);

// Generate the URL for the PlantUML server
$url = "http://www.plantuml.com/plantuml/img/" . encode64($encoded);

// Display the image
echo '<img src="' . $url . '" alt="System Architecture Diagram">';

// Function to encode in base64 for PlantUML
function encode64($str) {
    $bytes = array();
    $bytes = unpack('C*', $str);
    $binary = '';
    foreach ($bytes as $byte) {
        $binary .= pack('c', $byte);
    }
    $binary = gzdeflate($binary);
    return encode64trim($binary);
}

function encode64trim($data) {
    $encode = base64_encode($data);
    $encode = str_replace('+', '-', $encode);
    $encode = str_replace('/', '_', $encode);
    $encode = str_replace('=', '', $encode);
    return $encode;
}
?> 