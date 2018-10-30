<?php
include 'Perceptron.php';
$perceptron = new Perceptron(2, 0.5);
$perceptron->setWeightRandom(); // between 0 , 10
$perceptron->setEpochLimit(10000);
//$perceptron->setWeight([0, 0]);

/*
 * AND Gate
 */
$data = [
    [[0, 0], 0],
    [[0, 1], 0],
    [[1, 0], 0],
    [[1, 1], 1]
];
$perceptron->trainer($data);

/*
 * Test Section
 * oh nice!
 */
$test = [1, 1];
echo "\n";
echo 'The answer for(' . $test[0] . ',' . $test[1] . ') is: ' . $perceptron->test([1, 1]) . "\n";
