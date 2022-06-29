<?php
namespace umbalaconmeogia\phputil;

class UTSample
{
    /**
     * Compare two variable.
     * @param mixed $a
     * @param mixed $b
     * @return int Return -1 if $a < $b, 1 if $a > $b, 0 if $a = $b
     */
    public function compare($a, $b)
    {
        $result = 0;
        if ($a < $b) {
            $result = -1;
        } else if ($a > $b) {
            $result = 1;
        }
        return $result;
    }
}