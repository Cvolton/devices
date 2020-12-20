<?php include "incl/connection.php"; 
if(!isset($_POST['api_key']) || !isset($_POST['version_tag']) || !isset($_POST['packages_count']) || !isset($_POST['id']) || $_POST['api_key'] != $apiKey)
  exit(-1); //inspired by robert

$query = $db->prepare("UPDATE operating_system SET version_tag = :version_tag, packages_count = :packages_count, updated_on = :updated_on WHERE id = :id");
$query->execute(['version_tag' => $_POST['version_tag'], 'packages_count' => $_POST['packages_count'], 'id' => $_POST['id'], 'updated_on' => date('Y-m-d H:i:s')]);
echo "Success";