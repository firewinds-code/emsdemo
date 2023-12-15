<?php


$the_date = date('2020-09-19');
echo "the_day_of_week=" . $the_day_of_week = date("w", strtotime($the_date)); //sunday is 0

$first_day_of_week = date("Y-m-d", strtotime($the_date) - 60 * 60 * 24 * ($the_day_of_week) + 60 * 60 * 24 * 1);
$last_day_of_week = date("Y-m-d", strtotime($first_day_of_week) + 60 * 60 * 24 * 6);

echo $first_day_of_week;
echo "~";
echo $last_day_of_week;
echo "<br>";

echo 'sunday this week' . $currentSundayDate = date('Y-m-d', strtotime('sunday last week'));
echo "<br>";

echo 'monday this week' . $currentMondayDate = date('Y-m-d', strtotime('monday last week'));

echo "<br>";
echo "<br>";

$date = strtotime('2020-09-20');
/*echo 'date='.date('w', $date);
echo "<br>";echo "<br>";
echo 'date='.date('N', $date);*/
echo 'Sunday=' . $sunday = date('Y-m-d', strtotime('-' . date('w', $date) . ' days', $date));
echo "<br>";
echo "<br>";
echo "Moday=" . $monday =  date('Y-m-d', strtotime('-' . (date('N', $date) + 6) . ' days', $date));
echo "<br>";
echo "<br>";
echo $dayofweek = date("w", strtotime('2020-09-21'));
