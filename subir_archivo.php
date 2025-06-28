<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir archivo</title>
</head>
<body>
    <form enctype="multipart/form-data" action="salva_archivo.php" method="post">

        <input type="file" id="archivo" name="archivo"><br><br> // tipo file para subir archivos
        <input type="submit" value="Subir archivo" name="submit">

    </form>
</body>
</html>