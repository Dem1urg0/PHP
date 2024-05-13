<?php
$a = rand(-15, 15);
$b = rand(-15, 15);
echo "$a and $b";
if ($a >= 0 && $b >= 0) {
    echo $a - $b;
} else if ($a <= 0 && $b <= 0) {
    echo $a * $b;
} else {
    echo $a + $b;
}
