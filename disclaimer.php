<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Disclaimer</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      /* background-color: #f5f5f5; */
    }
    .container {
      width: 100%;
      max-width: 90%;
      margin: 20px auto;
      padding: 20px;
      /* background-color:#87CEEB; */
      /* border: 1px solid #ddd; */
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      padding:0px !important
    }
    h1 {
      text-align: center;
      color: #4a4a4a;
    }
    p {
      /* line-height: 1.6; */
      /* color: #333; */
    }
    .highlight {
      font-weight: bold;
    }
    #img{
      background-color: #ffff;
      height: 300px ;
      width: 100%;
      background-image: url('assets/images/disclaimer.png');
      background-size: cover;
      background-position: center;  
      background-repeat: no-repeat;
      opacity: 3;
      /* border-radius: 10px; */
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <div id="img"> 

    <!-- <img src="assets/images/disclaimer.jpg" alt="Disclaimer" class="img-fluid"> -->
  </div>
  <div class="container">
    <a href="index.php" style="text-decoration: none; color: black; font-size:10px;">Home</a>
    <a href="disclaimer.php" style="text-decoration: none; color: black; font-size:10px;">Disclaimer</a>
    <!-- <a href=".php"> Us</a> -->
    <h1>Disclaimer</h1>
    <p>
      The information contained in this website is for general information purposes only.
      The information is provided by <span class="highlight">Ultrakey</span>, and while we endeavor to keep
      the information up-to-date and correct, we make no representations or warranties
      of any kind, express or implied, about the completeness, accuracy, reliability,
      suitability, or availability with respect to the website or the information, products,
      services, or related graphics contained on the website for any purpose. Any reliance
      you place on such information is therefore strictly at your own risk.
    </p>
    <p>
      In no event will we be liable for any loss or damage, including without limitation,
      indirect or consequential loss or damage, or any loss or damage whatsoever arising
      from loss of data or profits, arising out of, or in connection with, the use of this
      website.
    </p>
    <p>
      Through this website, you can link to other websites which are not under the control
      of <span class="highlight">Ultrakey</span>. We have no control over the nature, content, and availability
      of those sites. The inclusion of any links does not necessarily imply a recommendation
      or endorse the views expressed within them.
    </p>
    <p>
      Every effort is made to keep the website up and running smoothly. However,
      <span class="highlight">Ultrakey</span> takes no responsibility for, and will not be liable for, the website being
      temporarily unavailable due to technical issues beyond our control.
    </p>
    <p>
      <strong>Ultrakey</strong> is located at:
      <br> Address: 2nd Floor, Above Indian Bank, 2th Block, Indira Nagar Gachibowli , Hydearabd, 500082
      <br> Email: <a href="mailto:support@gmail.com">support@gmail.com</a>
      <br> Website: <a href="https://www.ultrakey.com" target="_blank">www.ultrakey.com</a>
    </p>
  </div>
  
<?php include 'footer.php'; ?>
  </body>

</html>
