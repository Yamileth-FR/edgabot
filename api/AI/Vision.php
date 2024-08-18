<?php
if(isset($_GET['image'])) {
  $imageUrl = $_GET['image'];
  $apiUrl = "https://api.kastg.xyz/api/ai/object-detector?url=" . urlencode($imageUrl);
  $response = file_get_contents($apiUrl);
  $data = json_decode($response, true);
  $result = $data['result'];
  
  if(count($result) > 0) {
    $labels = array();
    foreach ($result as $item) {
      $label = key($item);
      $labels[] = $label;
    }
    $counts = array_count_values($labels);
    $responseText = "In the image I see: ";
    foreach ($counts as $label => $count) {
      $responseText .= $count . " " . $label . ", ";
    }
    $responseText = rtrim($responseText, ", ");
    $response = array("response" => $responseText);
  } else {
    $response = array("response" => "No puedo identificar lo que veo en la imagen.");
  }
  
  echo json_encode($response);
  exit;
}
?>