<?php include __DIR__.'/_partials/header.php'; ?>
<div class="row">
    <div class="column text-center">
        <h3>Workers are started</h3>
        <h4>Please note this app started <?php echo getEnvOrException('PROCESS_COUNT'); ?> workers in order to reach rate limit</h4>
        <h3>In order to continue please go to the terminal and run the following command</h3>
        <pre id="copyText">docker-compose exec web php /app/src/console/example.php</pre>
        <button class="button-primary" id="copyBtn">Copy</button>
    </div>
</div>
<script type="application/javascript" src="/js/copy.js?<?php echo filemtime('./js/copy.js'); ?>"></script>
<?php include __DIR__.'/_partials/footer.php'; ?>
