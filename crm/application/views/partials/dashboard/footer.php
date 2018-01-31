<footer class="main-footer">
    <!-- <div class="pull-right no-print">
        <span class="hidden-xs">Env: <?= ENVIRONMENT; ?></span>
    </div> -->
    &copy; <?= date('Y') ?> <a href="http://www.mark3.in">Trucrm</a>. All rights reserved.
</footer>

<?php
$whitelist = ['127.0.0.1', '::1'];
if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) :
?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-105882546-1', 'auto');
  ga('send', 'pageview');
</script>
<?php endif; ?>