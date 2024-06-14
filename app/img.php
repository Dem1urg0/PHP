<?php
$link = mysqli_connect('mariadb', 'root', 'rootroot', 'dbphp');
$sql = "SELECT * FROM `imgs` WHERE `id` =" . $_GET['id'];
$img = mysqli_fetch_assoc(mysqli_query($link, $sql));
$block = "<img class=\"img\" src=\"/img/{$img['file']}\">";

$views = $img['views'];
$views++;
$sqlUpd = 'UPDATE `imgs` SET `views` =' . $views . " WHERE `imgs`.`id` =" . $_GET['id'];
mysqli_query($link, $sqlUpd);

$sql_review = "INSERT INTO `reviews` (`img_id`, `id`, `stars`, `text`) VALUES ('2', NULL, '4', 'Хорошо')";


$id = $_GET['id'];
$sql_review = "SELECT `stars`, `text` FROM `reviews` WHERE `img_id` =" . "$id";
$reviews = mysqli_fetch_all(mysqli_query($link, $sql_review));

$block_review = '';
$num = 1;
foreach ($reviews as $review) {
    $block_review .= "<div><h4>ОТЗЫВ:$num</h4><p>SCORE:" . $review[0] . "/5" . "</p><p>TEXT:" . $review[1] . "</p></div>";
    $num++;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST["stars"]) && isset($_POST["message"])) {
        $stars = intval($_POST["stars"]);
        $message = mysqli_real_escape_string($link, $_POST['message']);

        $sqlUpd_review = "INSERT INTO `reviews` (`img_id`, `stars`, `text`) VALUES ($id, $stars, '$message')";
        mysqli_query($link, $sqlUpd_review) or die(mysqli_error($link));
    }
}

// средняя оценка

$sql_score = 'SELECT `stars` FROM `reviews` WHERE `img_id` =' . $id;
$score = mysqli_fetch_all(mysqli_query($link, $sql_score));
$sum = 0;
foreach ($score as $item) {
    $sum += $item[0];
}
if (count($score) == 0) {
    $avg = 'нет';
} else $avg = $sum / count($score);
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-5">
    <style>
        @import url(https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic);

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #ebebeb;
            overflow-x: hidden;
            text-align: center;
        }

        header {
            width: 100%;
            height: 50px;
            background-color: #f44355;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        }

        header > nav > div {
            float: left;
            width: 16.6666%;
            height: 100%;
            position: relative;
        }

        header > nav > div > a {
            text-align: center;
            width: 100%;
            height: 100%;
            display: block;
            line-height: 50px;
            color: #fbfbfb;
            transition: background-color 0.2s ease;
            text-transform: uppercase;
        }

        header > nav > div:hover > a {
            background-color: rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        header > nav > div > div {
            display: none;
            overflow: hidden;
            background-color: white;
            min-width: 200%;
            position: absolute;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19), 0 6px 6px rgba(0, 0, 0, 0.23);
            padding: 10px;
        }

        header > nav > div:not(:first-of-type):not(:last-of-type) > div {
            left: -50%;
            border-radius: 0 0 3px 3px;
        }

        header > nav > div:first-of-type > div {
            left: 0;
            border-radius: 0 0 3px 0;
        }

        header > nav > div:last-of-type > div {
            right: 0;
            border-radius: 0 0 0 3px;
        }

        header > nav > div:hover > div {
            display: block;
        }

        header > nav > div > div > a {
            display: block;
            float: left;
            padding: 8px 10px;
            width: 46%;
            margin: 2%;
            text-align: center;
            background-color: #f44355;
            color: #fbfbfb;
            border-radius: 2px;
            transition: background-color 0.2s ease;
        }

        header > nav > div > div > a:hover {
            background-color: #212121;
            cursor: pointer;
        }

        h1 {
            margin-top: 100px;
            font-weight: 100;
        }

        p {
            color: #aaa;
            font-weight: 300;
        }

        .pics {
            display: grid;
            grid-template-columns: repeat(4, 125px);
            justify-content: center;
        }

        .img {
            margin-top: 25px;
            width: 600px;
            height: 450px;
            background-color: #b5ee82;
        }

        .back {
            position: absolute;
            font-size: 40px;
            text-decoration: none;
            color: #f44355;
            border: #f44355 5px solid;
            margin-top: 50px;
            margin-left: 5px;
            padding-left: 5px;
            padding-right: 5px;
        }

        .views {
            position: absolute;
            margin-top: 125px;
            margin-left: 15px;
        }

        @media (max-width: 600px) {
            header > nav > div > div > a {
                margin: 5px 0;
                width: 100%;
            }

            header > nav > div > a > span {
                display: none;
            }
        }
    </style>
</head>
<body>
<header>
</header>
<div class="pics">
    <?php echo $block ?>
    <a class="back" href="/?page=3">BACK</a>
    <h2 class="views">VIEWS:<?php echo $views ?></h2>
    <h2 style="color: #f44355; position: absolute; margin-top: 170px; margin-left: 15px; width: 108px;">Средняя оценка: <?php echo $avg ?></h2>
</div>
<div class="do_review">

</div>
<div class="review">
    <form action="" method="post">
        <input type="hidden" value="<?= $id ?>">
        <label for="message">Оставьте отзыв:</label><br>
        <textarea name="message" id="message" cols="30" rows="10"></textarea>
        <select name="stars">
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
        </select>
        <input type="submit">
    </form>
    <?php echo $block_review ?>
</div>
</body>
</html>

