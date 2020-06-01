<html>
<head>
    <title>Home page</title>
</head>
<body>
<form>
    <input type="text" name="name">
    <button type="submit">Submit</button>
</form>

<?php if (!empty($name)): ?>
    <?php
    /*сокращенный синтаксиси для php echo <?= $name; ?>
    полный вариант <?php echo $name ?>
    */ ?>

    <h1>Hello <?= $name; ?></h1>
<?php endif; ?>
</body>


</html>