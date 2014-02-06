<?php

/**
 * Validates an amount against an max amount and can return an error message
 */
class ValidateTotal
{
    /**
     * @var array
     */
    private $translation;

    /**
     * @var float
     */
    private $total;
    /**
     * @var float
     */
    private $minTotal;
    /**
     * @var float
     */
    private $maxTotal;


    /**
     * @param array $translation
     */
    public function __construct($translation)
    {
        $this->translation = $translation;
    }

    /**
     * Validates amount
     *
     * @param  float $total
     * @param  float $minTotal
     * @param  float $maxTotal
     * @return bool
     */
    public function validate($total, $minTotal, $maxTotal)
    {
        $this->total = $total;
        $this->minTotal = $minTotal;
        $this->maxTotal = $maxTotal;

        return
            (!$this->maxTotal || $total <= $this->maxTotal) &&
            (!$this->minTotal || $total >= $this->minTotal);
    }

    /**
     * Returns error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return str_replace("#max_total#", $this->maxTotal, $this->translation['error_total_to_high']);
    }
}
