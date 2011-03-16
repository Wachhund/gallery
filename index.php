<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" type="text/css" href="../style/style.css">
<link rel="shortcut icon" type="image/ico" href="../img/favicon.ico">
<title>toastwaffle.</title>
<script src="../javascripts/prototype.js" type="text/javascript"></script>
<script src="../javascripts/scriptaculous.js" type="text/javascript"></script>
</head>
<body onLoad="$('navbar').hide(); return false;">
<script type="text/javascript" language="javascript">
  // <![CDATA[
  function navbar()
  {
  if (document.getElementById('navbar').style.display == "none")
    {
    Effect.SlideDown('navbar', { duration: 0.5 });
    return false;
    }
  else
    {
    Effect.SlideUp('navbar', { duration: 0.5 });
    return false;
    }
  }
  // ]]>
</script>
<div class="postit">
<?php include('../style/postittext.html'); ?>
</div>
<div class="content">
<div class="uppercontent">
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam faucibus, sem sit amet egestas pharetra, metus erat consequat quam, molestie porta turpis augue ac massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean aliquet nisi sed justo egestas faucibus. Curabitur sit amet orci a nisi interdum rutrum. Mauris ac arcu a sapien luctus varius a non tellus. Suspendisse potenti. Aliquam erat volutpat. Nulla facilisi. Fusce in accumsan nunc. Vestibulum semper ultricies aliquet. Nunc sed ipsum dolor, sed venenatis metus. Sed dignissim mattis velit, id semper tortor aliquet ut. Integer fermentum ligula id ante semper pulvinar a non arcu. Nulla erat erat, hendrerit ac sagittis at, cursus at dui. In hac habitasse platea dictumst. Duis quis sem nibh, ac semper diam. Integer porttitor auctor dui at pretium. Praesent at molestie diam.</p>
</div>
<div class="lowercontent">
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam faucibus, sem sit amet egestas pharetra, metus erat consequat quam, molestie porta turpis augue ac massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aenean aliquet nisi sed justo egestas faucibus. Curabitur sit amet orci a nisi interdum rutrum. Mauris ac arcu a sapien luctus varius a non tellus. Suspendisse potenti. Aliquam erat volutpat. Nulla facilisi. Fusce in accumsan nunc. Vestibulum semper ultricies aliquet. Nunc sed ipsum dolor, sed venenatis metus. Sed dignissim mattis velit, id semper tortor aliquet ut. Integer fermentum ligula id ante semper pulvinar a non arcu. Nulla erat erat, hendrerit ac sagittis at, cursus at dui. In hac habitasse platea dictumst. Duis quis sem nibh, ac semper diam. Integer porttitor auctor dui at pretium. Praesent at molestie diam.</p>
</div>
</div>
<div class="navcontainer">
<div class="nav">
<div class="navhead">
<p class="center"><a href="#" onclick="navbar();"> Navigation </a></p>
</div>
<div id="navbar" class="navcontent">
<div>
<?php include('navigation.html'); ?>
</div>
</div>
</div>
</div>
</body>
</html>
