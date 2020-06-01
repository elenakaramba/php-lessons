<html>
<head>
    <title>The Home Page</title>
</head>
<body>
<h2>The frequency of words in a text</h2>
<form>
    <label for="homepage">Enter the URL:</label><br>
    <input type="url" id="url" name="url">
    <button type="submit">Submit</button>
</form>

<?php if ($wordFrequency!== null) : ?>
<table>
    <?php foreach ($wordFrequency as $word => $count) : ?>
        <tr>
            <td><?= htmlspecialchars($word) ?></td>
            <td><?= $count ?></td>

        </tr>
    <?php endforeach; ?>
</table>
<?php endif ?>

<?php if ($pageData !==null) : ?>
<?php if ($pageData['page'] != 1) : ?>
    <?php $prev = $pageData['page'] - 1 ?>
    <a href="?url=<?= $url ?>&page=<?= $prev ?>">Previous</a>
<?php endif; ?>
<?php if ($pageData['page'] < $pageData['pageCount']) : ?>
    <?php $nextPage = $pageData['page'] + 1 ?>
    <a href="?url=<?= $url ?>&page=<?= $nextPage ?>">Next</a>
<?php endif ?>
<?php endif ?>

</body>
</html>
