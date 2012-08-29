<?php
$signed_request = ($_REQUEST['signed_request']) ? $_REQUEST['signed_request'] : null; 
$secret = "2964bd2b7bac1bbcf046316d24e29aa0";

function parse_signed_request($signed_request, $secret) {
  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

  // decode the data
  $sig = base64_url_decode($encoded_sig);
  $data = json_decode(base64_url_decode($payload), true);

  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
    error_log('Unknown algorithm. Expected HMAC-SHA256');
    return null;
  }

  // check sig
  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
  if ($sig !== $expected_sig) {
    error_log('Bad Signed JSON signature!');
    return null;
  }

  return $data;
}

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

$userdata = parse_signed_request($signed_request, $secret);

$userlike = $userdata['page']['liked']; 
$useradmin = $userdata['page']['admin'];
?>

<!DOCTYPE HTML>
<html lang="en" xmlns:og="http://ogp.me/ns#" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>GetRest</title>
<meta name="description" content="">
<meta name="keywords" content="">
<style>
BODY { width: 810px; height: 851px; background: #ffffff; margin: 0; padding: 0; overflow: hidden; }
img { margin: 0; padding: 0; }
</style>
</head>
<body> 
<!-- 
<div id="fb-root"></div>
<script type="text/javascript">
window.fbAsyncInit = function(){
    FB.init({
    appId   : "470388522984924",
    status  : true, 
    cookie  : true,   
    oauth   : true,   
    xfbml   : true    
    });
    FB.Canvas.setSize({ width: 810, height: 910 }); 
};  
(function(d){
    var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));
</script> 
--> 

<?php if ($userlike == 1) { ?>
<script>
window.parent.location = "//www.facebook.com/GetRest?sk=wall";
</script>
<?php } else { ?>
<img src="img/fbwellpage.jpg" width="810" height="907" />
<?php } ?>

</body>
</html>

