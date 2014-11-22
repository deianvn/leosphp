<html>
    <head>
        <title>LeosPHP Test Application</title>
    </head>
    <body>
        <h1>LeosPHP Test Application</h1>
        <?php if (whas('Error')) { ?>
        <h2>Error retrieving object!</h2>
        <?php } else { ?>
        <h2>Object</h2>
        <ul>
            <li><strong>ID: </strong><?php echo wget('Object')->getId(); ?></li>
            <li><strong>Name: </strong><?php echo wget('Object')->getName(); ?></li>
        </ul>
        <?php } ?>
    </body>
</html>
