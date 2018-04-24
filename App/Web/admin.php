<!DOCTYPE html>
 <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="bndao">
        <title>Admin</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css"  href="App/Web/css/style.css" media="all">
    </head>
    <body>
        <div class="container">
            <header class="col-sm-12" style="margin-bottom:60px;">
                <p><a href="/">Go Back to Home</a></p>
                <p><a href="/out">Log out</a></p>
            </header>
            <div class="row">
                <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 1) {
                    echo '<h1 style="margin-bottom:20px;"> Welcome <b><a href="/">' . $_SESSION['user'] . '</a></b> ! </h1>';
                    echo '<p style="margin-bottom:20px;"> You have <b> full access </b> to this admin ! </p>';
                ?>
                <h1 style="margin-bottom:60px;">Users Manager</h1>
                <div id="content-form" class="col-sm-12">
                    <form action="/admin" class="form-horizontal" method="post">
                        <?php
                        if (isset($userNotice)) {
                            echo '<div class="col-sm-12"><p class="alert alert-success" role="alert"><strong>' . $userNotice . '</strong></p>';
                        }
                        if (isset($userErrors) && in_array(User::INVALID_NAME, $userErrors)) {
                            echo 'The Nick is not Valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="nickname">Nickname:</label>
                            <div class="col-sm-10 focus">
                                <input class="form-control" type="text" name="nickname" placeholder="DoftomDow" required value="<?php if (isset($user)) { echo $user->getNickName(); } ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($userErrors) && in_array(User::INVALID_EMAIL, $userErrors)) {
                            echo 'The Email is not valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="email">Email:</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="email" placeholder="doftom@dow.com" required value="<?php if (isset($user)) { echo $user->getEmail(); } ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($userErrors) && in_array(User::INVALID_PWD, $userErrors)) {
                            echo 'The Password is not Valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="pwd">Password:</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="password" name="pwd" placeholder="*@#*@#*#@*#***..." required value="<?php if (isset($user)) { echo $user->getPWD(); } ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="admin">Give super user permission:</label>
                            <div class="col-sm-10">
                                <input type="radio" name="admin"
                                <?php if (isset($user) && $user->getAccessLevel() == 1) echo "checked";?>
                                value="true">yes
                                <input type="radio" name="admin"
                                <?php if (isset($user) && $user->getAccessLevel() == 2) echo "checked";?>
                                value="false">nope
                            </div>
                        </div>
                        <?php
                            if (isset($user) && !$user->isNew()) {
                            ?>
                                <input type="hidden" name="userId" value="<?php echo $user->getId() ?>">
                                <input class="btn btn-primary pull-right" type="submit" value="Edit" name="editUser">
                            <?php
                            } else {
                            ?>
                                <input class="btn btn-primary pull-right" type="submit" value="Add">
                            <?php
                            }
                        ?>
                    </form>
                </div>
                <div class="col-sm-12">
                    <p class="alert alert-info"><strong>There is currently <?php echo $Ctrl->count('users'); ?> users:</strong></p>
                </div>
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nickname</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>AccesLevel</th>
                            <th>Account Creation</th>
                            <th>Last Access</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        foreach ($Ctrl->getThem('users') as $user)
                        {
                          echo '<tr><td>' . $user->getNickName() .
                               '</td><td>' . $user->getEmail() .
                               '</td><td>' . $user->getPWD() .
                               '</td><td>' . $user->getAccessLevel() .
                               '</td><td>' . $user->getDateCreated()->format('d/m/Y @ H\hi') .
                               '</td><td>' . ($user->getDateCreated() == $user->getLastAccess() ? '-' : $user->getLastAccess()->format('d/m/Y @ H\hi')) .
                               '</td><td><a href="?editUser=' . $user->getId() . '">Edit</a> | <a href="?delUser=' . $user->getId() . '">Delete</a></td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php
            }
            ?>
            <?php if (isset($_SESSION['user'])) {
            ?>
            <!-- News -->
            <div class="row">
                <header class="col-sm-12" style="margin-bottom:60px;">
                    <h1>News Manager</h1>
                </header>
                <div id="content-form" class="col-sm-12">
                    <form action="/admin" class="form-horizontal" method="post">
                        <?php
                        if (isset($message)) {
                            echo '<div class="col-sm-12"><p class="alert alert-success" role="alert"><strong>' . $message . '</strong></p>';
                        }
                        if (isset($errors) && in_array(News::INVALID_AUTHOR, $errors)) {
                            echo 'The Author is not Valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="author">Author:</label>
                            <div class="col-sm-10 focus">
                                <input class="form-control" type="text" name="author" required disabled value="<?php echo $_SESSION['user'] ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($errors) && in_array(News::INVALID_TITLE, $errors)) {
                            echo 'The Title is not valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="title">Title:</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="title" placeholder="Title" required value="<?php if (isset($news)) { echo $news->getTitle(); } ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($errors) && in_array(News::INVALID_CONTENT, $errors)) {
                            echo 'The Content is not Valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="content">Content:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="8" required name="content"><?php if (isset($news)) { echo $news->getContent(); } ?></textarea>
                            </div>
                        </div>
                        <?php
                            if (isset($news) && !$news->isNew()) {
                            ?>
                                <input type="hidden" name="id" value="<?php echo $news->getId() ?>">
                                <input class="btn btn-primary pull-right" type="submit" value="Edit" name="modif">
                            <?php
                            } else {
                            ?>
                                <input class="btn btn-primary pull-right" type="submit" value="Add">
                            <?php
                            }
                        ?>
                    </form>
                </div>
                <div class="col-sm-12">
                    <p class="alert alert-info"><strong>There is currently <?php echo $Ctrl->count('news'); ?> news. Here is the List:</strong></p>
                </div>
                <div  class="col-sm-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Author</th>
                            <th>Title</th>
                            <th>Date Added</th>
                            <th>Last modification</th>
                            <th>Actions</th>
                        </tr>
                        <?php
                        foreach ($Ctrl->getThem('news') as $news)
                        {
                          echo '<tr><td>' . $news->getAuthor() .
                               '</td><td>' . $news->getTitle() .
                               '</td><td>' . $news->getDateAdded()->format('d/m/Y @ H\hi') .
                               '</td><td>' . ($news->getDateAdded() == $news->getDateModified() ? '-' : $news->getDateModified()->format('d/m/Y @ H\hi')) .
                               '</td><td><a href="?modif=' . $news->getId() . '">Edit</a> | <a href="?delete=' . $news->getId() . '">Delete</a></td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <?php
        }
        ?>
    </body>
</html>
