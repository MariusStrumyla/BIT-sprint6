<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIT-sprint6</title>
</head>
<body>
    <?php
    // Current directory path
    $path = isset($_GET["path"]) ? './' . $_GET["path"] : './' ;
    $files_and_dirs = scandir($path);
    
    print('<h2>Current directory: ' . str_replace('?path=/','',$_SERVER['REQUEST_URI']) . '</h2>');

    // READ files and directories
    print('<table><th>Type</th><th>Name</th><th>Actions</th>');
    foreach ($files_and_dirs as $fnd){
        if ($fnd != ".." and $fnd != ".") {
            print('<tr>');
            print('<td>' . (is_dir($path . $fnd) ? "Directory" : "File") . '</td>');
            print('<td>' . (is_dir($path . $fnd) 
                        ? '<a href="' . (isset($_GET['path']) 
                                ? $_SERVER['REQUEST_URI'] . $fnd . '/' 
                                : $_SERVER['REQUEST_URI'] . '?path=' . $fnd . '/') . '">' . $fnd . '</a>'
                        : $fnd)
                . '</td>');
        }
    }
    print("</table>");
    ?>
</body>
</html>