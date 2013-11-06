  <div class="mainbar_error">
    <div class="article">
      <h2>
      <img src="images/exclamanation.png" alt="exclamanation" class="fl" align="middle" height="100px" style="padding-right:30px; padding-bottom: 20px;" /><span>Oops, something went wrong! Error 404 (File Not Found)</span></h2>
      <div class="clr"></div>
      <p>The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
      <p>Please reffer to the following pages:
      	<ul>
			<?php foreach($pages as $item) { ?>
                <li><a href="<?php echo $item[0]; ?>"><?php echo $item[1]; ?></a></li>
            <?php } ?>
        </ul>
      </p>
      </div>
  </div>