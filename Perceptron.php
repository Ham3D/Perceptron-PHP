<?php

class Perceptron
{
    protected $learningRate;
    protected $output;
    protected $bias;
    protected $vectorLength;
    protected $weightVector;
    protected $epoch = 0;
    protected $satisfied = false;
    protected $epochLimit = 1000;

    /**
     * @param int   $vectorLength طول آرایه
     * @param float $learningRate نرخ یادگیری
     * @param int   $bias w0
     */
    function __construct($vectorLength, $learningRate = 0.5, $bias = 0)
    {
        //Validation
        if ($vectorLength < 1) {
            throw new InvalidArgumentException();
        } elseif ($learningRate <= 0 || $learningRate > 1) {
            throw new InvalidArgumentException();
        }
        $this->vectorLength = $vectorLength;
        $this->bias = $bias;
        $this->learningRate = $learningRate;
    }

    /**
     * @param array $data An Big array of data Contains learning samples
     */
    public function trainer($data)
    {
        //if weights is not set , do it random
        if (empty($this->weightVector)) {
            Self::setWeightRandom();
        }

        while (!$this->satisfied) {
            foreach ($data as $trainSample) {
                Self::train($trainSample[0], $trainSample[1]);
            }

            //test weights
            $status = true;
            foreach ($data as $trainSample) {
                if (Self::test($trainSample[0]) != $trainSample[1]) {
                    $status = false;
                }
            }
            if ($status == true) {
                $this->satisfied = true;
            }
            $this->epoch++;

            if ($this->epoch > 1000000) {
                die('It Took too long , more than 1000000 Enoch!' . "\n");
            }
        }
        echo 'Training is Done in: ' . $this->epoch . " Epoch \n";
        echo 'Weights are : ' . "\n";
        var_dump($this->weightVector);
        echo("\n");
        echo("and W0 is: ");
        echo($this->bias . "\n");
    }

    /**
     * @param array $input array of inputs
     * @param int   $result 1 = true / 0 = false
     */
    public function train($input, $result)
    {
        //Validation
        if (!is_array($input) || ($result != 0 && $result != 1)) {
            throw new InvalidArgumentException();
        }

        // Test if current weights are Valid
        // Do test on input
        $output = Self::test($input);
        if ($output != $result) {
            //we need to change weights
            for ($i = 0; $i < $this->vectorLength; $i++) {
                $this->weightVector[$i] =
                    $this->weightVector[$i] + $this->learningRate * ((int)$result - (int)$output) * $input[$i];
            }
        }
        $this->bias = $this->bias + ((int)$result - (int)$output);
    }

    /**
     * @param $input Array
     * @return bool
     */
    public function test($input)
    {
        //Validation
        if (!is_array($input) || count($input) != $this->vectorLength) {
            throw new InvalidArgumentException();
        }

        $testResult = $this->dotVectors($this->weightVector, $input) + $this->bias;
        return $testResult > 0 ? 1 : 0;
    }

    /**
     * @param array $vector1
     * @param array $vector2
     *
     * @return number
     */
    private function dotVectors($vector1, $vector2)
    {
        $total = 0;
        $dim = count($vector1);
        for ($i = 0; $i < $dim; $i++) {
            $total += $vector1[$i] * $vector2[$i];
        }
        return $total;
    }

    /**
     * @param mixed $weightVector
     */
    public function setWeight($weightVector)
    {
        //Validation
        if (!is_array($weightVector) || count($weightVector) != $this->vectorLength) {
            throw new \InvalidArgumentException();
        }
        $this->weightVector = $weightVector;
    }

    /**
     *
     */
    public function setWeightRandom()
    {
        //set Default weights to 0
        for ($i = 0; $i < $this->vectorLength; $i++) {
            $this->weightVector[$i] = rand(0, 10);
        }
    }

    /**
     * @param int $epochLimit
     */
    public function setEpochLimit($epochLimit)
    {
        $this->epochLimit = $epochLimit;
    }
}
