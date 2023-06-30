<?php

namespace Spatie\HttpLogger;

class Sanitizer
{
    protected string $mask;

    public function __construct(string $mask = "****")
    {
        $this->mask = $mask;
    }

    public function clean(array $input, $keys)
    {
        $keys = (array) $keys;

        if (count($keys) === 0) {
            return $input;
        }

        $keys = array_map([$this, 'normalize'], $keys);

        foreach ($input as $key => $value) {
            $normalizedKey = $this->normalize($key);

            if (in_array($normalizedKey, $keys)) {
                $input[$key] = is_array($value) ? [$this->mask] : $this->mask;

                continue;
            }

            if (is_array($value)) {
                $input[$key] = $this->clean($value, $keys);
            }
        }

        return $input;
    }

    public function normalize(string $string)
    {
        return strtolower($string);
    }

    public function setMask(string $mask)
    {
        $this->mask = $mask;
    }
}
