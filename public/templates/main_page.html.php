<!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Districts Application</title>
    <link rel="stylesheet" href="./../css/styles.css">
</head>
<body>
    <header>
        <h1>Districts Application</h1>
        <h3>Informacje na temat dzielnic Gdańska i Krakowa</h3>
    </header>
    <main>
        <section class="districts-info">
            <table id="district-table">
                <tr>
                    <th><a href="./?sort=name">Nazwa</a></th>
                    <th><a href="./?sort=area">Powierzchnia (km<sup>2</sup>)</a></th>
                    <th><a href="./?sort=population">Populacja</a></th>
                    <th><a href="./?sort=city">Miasto</a></th>
                    <th>Usuń</th>
                </tr>
                <?php foreach ($districts as $district): ?>
                <tr>
                    <td><?= $district->name ?></td>
                    <td><?= $district->area ?></td>
                    <td><?= $district->population ?></td>
                    <td><?= $district->city ?></td>
                    <td><a href="./delete?id=<?= $district->id ?>">X</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>
        <section class="districts-control">
            <form id="district-form" action="./save" method="post">
                <label>Nazwa:
                    <input type="text" name="name" value="<?= $_SESSION['form_data']['name'] ?? '' ?>">
                </label>
                <label>Powierzchnia:
                    <input type="text" name="area" value="<?= $_SESSION['form_data']['area'] ?? '' ?>">
                </label>
                <label>Populacja:
                    <input type="number" name="population" value="<?= $_SESSION['form_data']['population'] ?? '' ?>">
                </label>
                <label>Miasto:
                    <input type="text" name="city" value="<?= $_SESSION['form_data']['city'] ?? '' ?>">
                </label>
                <br>
                <input type="submit" value="Zapisz" class="button">
                <p class="error"><?= $_SESSION['form_errors']['name'] ?? '' ?></p>
                <p class="error"><?= $_SESSION['form_errors']['area'] ?? '' ?></p>
                <p class="error"><?= $_SESSION['form_errors']['population'] ?? '' ?></p>
                <p class="error"><?= $_SESSION['form_errors']['city'] ?? '' ?></p>
                <?php
                unset($_SESSION['form_data']);
                unset($_SESSION['form_errors']);
                ?>
            </form>
            <div class="button">
                <a href="./actualize" class="button">Aktualizuj dane</a>
            </div>
        </section>
    </main>
    <script src="./../js/filter.js"></script>
</body>
</html>
