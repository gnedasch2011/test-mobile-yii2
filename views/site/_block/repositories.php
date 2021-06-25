<?php if ($repositories): ?>
    <?php foreach ($repositories as $rep): ?>
        <p><?= $rep['full_name']; ?> : <a href="<?= $rep['html_url']; ?>"><?= $rep['name']; ?></a>
            (<?= $rep['updated_at'] ?? ''; ?> )</p>
    <?php endforeach; ?>
<?php endif; ?>