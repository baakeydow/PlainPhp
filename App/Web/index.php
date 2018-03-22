<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="bndao">
        <title>News</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css"  href="App/Web/css/style.css" media="all">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <?php
                if (isset($_GET['id'])) {
                    echo '<p><a href="/">Go to Home page</a></p>';
                    $news = $Ctrl->getOne((int) $_GET['id']);
                    echo '<p style="margin:0 20px;text-align: center;">By <em>', $news->getAuthor(), '</em>, the ', $news->getDateAdded()->format('d/m/Y @ H\hi'), '</p>', "\n",
                    '<h2 style="margin-bottom: 20px;text-align: right;">', $news->getTitle(), '</h2>', "\n",
                    '<pre>', nl2br($news->getContent()), '</pre>', "\n";
                    if ($news->getDateAdded() != $news->getDateModified()) {
                        echo '<p style="text-align: right;"><small><em>Modified the ', $news->getDateModified()->format('d/m/Y @ H\hi'), '</em></small></p>';
                    }
                } else {
                    echo '<p><a href="/admin">Go to admin Page</a></p>';
                    echo '<h1 style="text-align:center;margin-bottom:60px;">Here is the 5 latest news added</h1>';
                    foreach ($Ctrl->getNews(0, 5) as $news) {
                        if (strlen($news->getContent()) <= 800) {
                            $content = $news->getContent();
                        } else {
                            $start = substr($news->getContent(), 0, 800);
                            $start = substr($start, 0, strrpos($start, ' ')) . '...';
                            $content = $start;
                        }
                        echo '<h4 style="margin-bottom: 20px;text-align: right;"><a href="?id=', $news->getId(), '">', $news->getTitle(), '</a></h4>', "\n",
                        '<pre>', nl2br($content), '</pre>';
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>
