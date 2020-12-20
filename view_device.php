<?php include "incl/connection.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <!-- Basic Page Needs
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta charset="utf-8">
  <title>Device Info</title>
  <meta name="description" content="">
  <meta name="author" content="">

  <!-- Mobile Specific Metas
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- FONT
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">

  <!-- CSS
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="stylesheet" href="<?= $documentRoot ?>/css/normalize.css">
  <link rel="stylesheet" href="<?= $documentRoot ?>/css/skeleton.css">

  <!-- Favicon
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <link rel="icon" type="image/png" href="<?= $documentRoot ?>/images/favicon.png">

  <style type="text/css">
      .item-name
      {
        font-weight: bold;
      }

      .red
      {
        color: red;
      }

      .green
      {
        color: green;
      }
    </style>

</head>
<body>

  <!-- Primary Page Layout
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
  <div class="container"style="margin-top: 2em">
    <div class="row">
      <div class="twelve columns">
        <?php if(empty($_GET['id'])): ?>
        <h1>Error</h1>
        <p>You have not specified a device ID.</p>
        <?php 
          exit();
          endif;
          $device = $db->prepare("SELECT id,display_name,photo_filename FROM computer WHERE id = ?");
          $device->execute([$_GET['id']]);
          if($device->rowCount() == 0): ?>
        <h1>Error</h1>
        <p>Invalid device ID specified.</p>
          <?php
          exit();
          endif;
          $device = $device->fetch(PDO::FETCH_ASSOC);

          $operatingSystems = $db->prepare("SELECT id,display_name,type,version_tag,packages_count,about_filename,updated_on,computer_id FROM operating_system WHERE computer_id = ? ORDER BY updated_on DESC");
          $operatingSystems->execute([$_GET['id']]);
        ?>
        <h1><?= $device['display_name'] ?></h1>
        <?php
          if($operatingSystems->rowCount() != 0): 
          $operatingSystems = $operatingSystems->fetchAll();
        ?>
        <p><span class="item-name">Status: </span><?= time() - strtotime($operatingSystems[0]['updated_on']) > 600 ? '<span class="item-content red">Offline</span>' : '<span class="item-content green">Online</span>'; ?></p>
        <p><span class="item-name">Last seen: </span><span class="item-content"><?= $operatingSystems[0]['updated_on'] ?></span></p>
        <p><span class="item-name">Last OS: </span><span class="item-content green"><?= $operatingSystems[0]['display_name'] ?></span></p>
        <h2>Operating Systems</h2>
        <?php
          foreach($operatingSystems as $system):
        ?>
        <h3><?= $system['display_name'] ?></h3>
        <?php
            switch($system['type']){
              case 0:
        ?>
        <p><span class="item-name">Kernel version: </span><span class="item-content"><?= $system['version_tag'] ?></span></p>
        <p><span class="item-name">Packages installed: </span><span class="item-content"><?= $system['packages_count'] ?></span></p>
        <p><span class="item-name">Last seen: </span><span class="item-content"><?= $system['updated_on'] ?></span></p>
        <p><span class="item-name">Latest neofetch:</span></p>
        <p><img class="u-max-full-width" src="<?= $imagesRoot . "/about/" . $system['about_filename'] ?>"></p>
        <p><span class="item-name">Neofetch updated on: </span><span class="item-content"><?= date('Y-m-d H:i:s', filemtime("${imagesRootLocal}images/about/${system['about_filename']}")) ?></span></p>
        <?php
              break;
              case 1:
        ?>
        <p><span class="item-name">Build tag: </span><span class="item-content"><?= $system['version_tag'] ?></span></p>
        <p><span class="item-name">Last seen: </span><span class="item-content"><?= $system['updated_on'] ?></span></p>
        <p><span class="item-name">Latest winver:</span></p>
        <p><img class="u-max-full-width" src="<?= $imagesRoot . "/about/" . $system['about_filename'] ?>"></p>
        <p><span class="item-name">Winver updated on: </span><span class="item-content"><?= date('Y-m-d H:i:s', filemtime("${imagesRootLocal}images/about/${system['about_filename']}")) ?></span></p>
        <?php
              break;
            }
          endforeach;
          endif;
        ?>
        <h2>Additional information</h2>
        <p><span class="item-name">Photo:</span></p>
        <p><img class="u-max-full-width" src="<?= $imagesRoot . "/photos/" . $device['photo_filename'] ?>"></p>
      </div>
    </div>
  </div>

<!-- End Document
  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
</body>
</html>
