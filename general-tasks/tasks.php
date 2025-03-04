<?php
/**
 * tasks.php
 *
 * This file contains solutions for Task 1.
 *
 * Task 1.1 - Factor Pair Listing for 900900
 * Task 1.2 - Number Classification (Deficient, Perfect, Abundant)
 * Task 1.3 - Harshad Number Checker
 *
 * Usage (from the command line):
 *   php tasks.php 1         // Runs Task 1.1 (prints all factor pairs for 900900)
 *   php tasks.php 2 28      // Runs Task 1.2 for the number 28 (output: perfect, deficient, or abundant)
 *   php tasks.php 3 1729    // Runs Task 1.3 for the number 1729 (checks if it's a Harshad number)
 *
 *
 */

function task1_1()
{
    $n = 900900;

    for ($i = 1; $i < $n; $i++) {
        if ($n % $i === 0) {
            for ($j = $i + 1; $j <= $n; $j++) {
                if ($n % $j === 0 && $i * $j === $n) {
                    echo "$i * $j = $n\n";
                }
            }
        }
    }
}

function task1_2($num)
{
    $sum = sumOfProperDivisorsSimple($num);
    if ($sum == $num) {
        return "perfect";
    } elseif ($sum < $num) {
        return "deficient";
    } else {
        return "abundant";
    }
}


function sumOfProperDivisorsSimple($number)
{
    $divisors = [];
    for ($i = 1; $i <= floor($number / 2); $i++) {
        if ($number % $i === 0) {
            $divisors[] = $i;
        }
    }
    return array_sum($divisors);
}

function task1_3($num)
{
    $digits = str_split($num);
    $digitSum = array_sum($digits);

    if ($digitSum == 0) {
        return false;
    }
    return ($num % $digitSum === 0);
}

if (php_sapi_name() == "cli") {
    if ($argc < 2) {
        echo "Usage: php tasks.php [task] [number]\n";
        echo "Task options:\n";
        echo "  1 - Print all factor pairs for 900900\n";
        echo "  2 - Classify a number as deficient, perfect, or abundant\n";
        echo "  3 - Check if a number is a Harshad number\n";
        exit(1);
    }

    $task = $argv[1];

    switch ($task) {
        case 1:
            task1_1();
            break;
        case 2:
            if (!isset($argv[2])) {
                echo "Please provide a number for task 1.2\n";
                exit(1);
            }
            $num = intval($argv[2]);
            $classification = task1_2($num);
            echo "The number $num is $classification.\n";
            break;
        case 3:
            if (!isset($argv[2])) {
                echo "Please provide a number for task 1.3\n";
                exit(1);
            }
            $num = intval($argv[2]);
            if (task1_3($num)) {
                echo "The number $num is a Harshad number.\n";
            } else {
                echo "The number $num is not a Harshad number.\n";
            }
            break;
        default:
            echo "Invalid task selected. Use 1, 2, or 3.\n";
    }
}
