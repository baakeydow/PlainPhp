<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Error 404</title>
</head>
<body>

    <h2>Please Login to access the admin page</h2>

    <p><?= $this->user->getFlash() ?></p>
    <form action="/admin" class="form-horizontal" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="username">Username:</label>
            <div class="col-sm-10 focus">
                <input class="form-control" type="text" name="username" placeholder="Nickname" autofocus required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="passwd">Password:</label>
            <div class="col-sm-10">
                <input class="form-control" type="password" name="passwd" placeholder="****" required>
            </div>
        </div>
        <input class="btn btn-primary pull-right" type="submit" value="Connect">
    </form>

</body>
</html>
