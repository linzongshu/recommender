<?php if (isset($error) && $error) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>

<?php if (isset($data) && $data) { ?>
<div>
    <div>
        <h4><?php echo htmlspecialchars($data['title']); ?></h4>
        <dl class="dl-horizontal">
            <dt>Key: </dt><dd><?php echo htmlspecialchars($data['key']); ?></dd>
            <dt>Secret: </dt><dd><?php echo htmlspecialchars($data['secret']); ?></dd>
            <dt>URL: </dt><dd><?php echo htmlspecialchars($data['url']); ?></dd>
            <dt>Business: </dt><dd><?php echo htmlspecialchars($data['name']); ?></dd>
            <dt>Time Created: </dt><dd><?php echo date('Y-m-d H:i:s', $data['time_created']); ?></dd>
        </dl>
    </div>
</div>
<?php } ?>
