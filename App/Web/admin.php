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
            <div class="row">
                <header class="col-sm-12" style="margin-bottom:60px;">
                    <h1>Admin</h1>
                    <p><a href="/">Go Back to Home</a></p>
                    <p><a href="/out">Log out</a></p>
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
                                <input class="form-control" type="text" name="author" placeholder="Author's Name" autofocus required value="<?php if (isset($news)) { echo $news->getAuthor(); } ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($errors) && in_array(News::INVALID_TITLE, $errors)) {
                            echo 'The Title is not valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="title">Title :</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="text" name="title" placeholder="Title" value="<?php if (isset($news)) { echo $news->getTitle(); } ?>">
                            </div>
                        </div>
                        <?php
                        if (isset($errors) && in_array(News::INVALID_CONTENT, $errors)) {
                            echo 'The Content is not Valid.<br />';
                        }
                        ?>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="content">Content :</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" rows="8" name="content"><?php if (isset($news)) { echo $news->getContent(); } ?></textarea>
                            </div>
                        </div>
                        <?php
                            if (isset($news) && !$news->isNew()) {
                            ?>
                                <input type="hidden" name="id" value="<?php echo $news->getId() ?>">
                                <input class="btn btn-primary pull-right" type="submit" value="modif" name="modif">
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
                    <p class="alert alert-info"><strong>There is currently <?php echo $Ctrl->count(); ?> news. Here is the List :</strong></p>
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
                        foreach ($Ctrl->getList() as $news)
                        {
                          echo '<tr><td>' . $news->getAuthor() .
                               '</td><td>' . $news->getTitle() .
                               '</td><td>' . $news->getDateAdded()->format('d/m/Y @ H\hi') .
                               '</td><td>' . ($news->getDateAdded() == $news->getDateModified() ? '-' : $news->getDateModified()->format('d/m/Y @ H\hi')) .
                               '</td><td><a href="?modif=' . $news->getId() . '">Modif</a> | <a href="?delete=' . $news->getId() . '">Delete</a></td></tr>';
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>
