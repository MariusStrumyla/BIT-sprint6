<?php
session_start();
// logout 
if (isset($_GET['action']) and $_GET['action'] == 'logout') {
    session_destroy();
    session_start();
}

// login 
$loginMsg = '';
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($_POST['username'] == 'Marko' && $_POST['password'] == 'Polo') {
        $_SESSION['logged_in'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = $_POST['username'];
    } else {
        $loginMsg = 'Wrong username or password.';
    }
}

// Create
if(isset($_GET["create_dir"])){
    if($_GET["create_dir"] != ""){
        $dir_to_create = './' . $_GET["path"] . $_GET["create_dir"];
        if (!is_dir($dir_to_create)) mkdir($dir_to_create, 0777, true);
    }
    $url = preg_replace("/(&?|\??)create_dir=(.+)?/", "", $_SERVER["REQUEST_URI"]);
    header('Location: ' . urldecode($url));
}

// Delete
if(isset($_POST['delete'])){
    $objToDelete = './' . $_GET["path"] . $_POST['delete']; 
    $objToDeleteEscaped = str_replace("&nbsp;", " ", htmlentities($objToDelete, null, 'utf-8'));
    if(is_file($objToDeleteEscaped)){
        if (file_exists($objToDeleteEscaped)) {
            unlink($objToDeleteEscaped);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP file browser</title>
</head>

<body>


    <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
        ? print("")
        : print("") ?>
    <div>
        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
            print('<h2>' . 'Welcome, adventurer!' . '</h2>');
        } else {
            NULL;
        } ?>
    </div>
    <div>
        <button>
            <a href="index.php?action=logout"> Logout</a>
        </button>
    </div>


    <!-- Login form -->
    <div id="loginForm" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                            ? print("style = \"display: none\"")
                            : print("style = \"display: block\"") ?>>
        <h2>Please enter your Username and Password</h2>
        <form action="" method="post" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                            ? print("style = \"display: none\"")
                                            : print("style = \"display: block\"") ?>>
            <input type="text" name="username" placeholder="Username = Marko" required autofocus></br>
            <input type="password" name="password" placeholder="Password = Polo" required><br>
            <button type="submit" name="login">Login</button>
            <h4><?php echo ($loginMsg); ?></h4>
        </form>
    </div>

    <div id="mainContainer" <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
                                ? print("style = \"display: block\"")
                                : print("style = \"display: none\"") ?>>

        <?php isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true
            ? print("")
            : print("style = \"display: none\"") ?>


        <?php
        // Current directory path
        $path = isset($_GET["path"]) ? './' . $_GET["path"] : './';
        $files_and_dirs = scandir($path);

        print('<h2>Current directory: ' . str_replace('?path=/', '', $_SERVER['REQUEST_URI']) . '</h2>');

        // READ files and directories
        print('<table><th>Type</th><th>Name</th><th>Actions</th>');
        foreach ($files_and_dirs as $fnd) {
            if ($fnd != ".." and $fnd != ".") {
                print('<tr>');
                print('<td>' . (is_dir($path . $fnd) ? "Directory" : "File") . '</td>');
                print('<td>' . (is_dir($path . $fnd)
                    ? '<a href="' . (isset($_GET['path'])
                        ? $_SERVER['REQUEST_URI'] . $fnd . '/'
                        : $_SERVER['REQUEST_URI'] . '?path=' . $fnd . '/') . '">' . $fnd . '</a>'
                    : $fnd)
                    . '</td>');
                    print('<td>'
                . (is_dir($path . $fnd) 
                    ? ''
                    : '<form style="display: inline-block" action="" method="post">
                        <input type="hidden" name="delete" value=' . str_replace(' ', '&nbsp;', $fnd) . '>
                        <input type="submit" value="Delete">
                       </form>'
                ) 
                . "</form></td>");
            print('</tr>');
            }
        }
        print("</table>");
        ?>


        <div>
    <form action="/FsBrowserPHP" method="get">
                <input type="hidden" name="path" value="<?php print($_GET['path']) ?>" /> 
                <input placeholder="Name of new directory" type="text" id="create_dir" name="create_dir">
                <button type="submit">Submit</button>
            </form>
    </div>
</body>

</html>