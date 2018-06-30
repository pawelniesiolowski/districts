<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Districts Application</title>
    <link rel="stylesheet" href="./../css/styles.css">
</head>
<body>
    <h1>Districts Application</h1>
    <h3>Informacje na temat dzielnic Gdańska i Krakowa</h3>
    <table id="district_table">
        <tr>
            <th><a href="<?= './?sort=name' ?>">Nazwa</a></th>
            <th><a href="<?= './?sort=population' ?>">Populacja</a></th>
            <th><a href="<?= './?sort=area' ?>">Powierzchnia</a></th>
            <th><a href="<?= './?sort=city_name' ?>">Miasto</a></th>
            <th>Usuń</th>
        </tr>
        <?php foreach ($districts as $district): ?>
        <tr>
            <td><?= $district->name ?></td>
            <td><?= $district->population ?></td>
            <td><?= $district->area ?></td>
            <td><?= $district->city ?></td>
            <td><a href="./delete?id=<?= $district->id ?>">X</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <form action="./save" method="post">
        <label>Nazwa<input type="text" name="name"></label>
        <label>Populacja<input type="number" name="population"></label>
        <label>Powierzchnia<input type="number" name="area"></label>
        <label>Miasto<input type="text" name="city_name"></label>
        <input type="submit" value="Zapisz">
    </form>
</body>
</html>
