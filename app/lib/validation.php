<?php

namespace Lib;

class Validation extends Connection
{
    private $requests = null;
    public function __construct(array $requests)
    {
        parent::__construct();
        $this->requests = (object) $requests;
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function validated(): object
    {
        $requests = $this->requests;
        $requiredFields = [];
        @$requests?->firstName or $requiredFields['firstName'] = 'required';
        @$requests?->lastName or $requiredFields['lastName'] = 'required';
        @$requests?->middleName or $requiredFields['middleName'] = 'required';
        @$requests?->birthDate or $requiredFields['birthDate'] = 'required';
        @$requests?->image or $requiredFields['image'] = 'required';

        (!$this->isDate($requests->birthDate)) &&
        $requiredFields['birthDate'] = 'not a valid date!';

        (!$this->isImage((object) $requests->image)) && $requiredFields['image'] = '`jpg|jpeg|png` format only';

        $this->error($requiredFields);
        $this->requests = $requests;

        return $this->sanitize();
    }

    private function error(array $errorFields)
    {
        if (count($errorFields)) {

            array_walk($errorFields, function (&$val, $key) {
                $val = "{$key} is {$val}!";
            });

            Response::json(["error" => $errorFields], 400);
        }
    }

    public function sanitize()
    {
        array_walk($this->requests, function (&$val, $key) {
            if ($key === 'image') {
                return;
            }

            $val = mysqli_escape_string($this->get(), $val);
        });

        return $this->requests;
    }

    private function isDate(string $date): bool
    {
        return (bool) preg_match("/(\d){4}-(\d){2}-(\d){2}$/m", $date);
    }

    private function isImage(object $image): bool
    {
        return (bool) preg_match("/((.png)|(.jpg)|(.jpeg)$)$/mi", @$image->name);
    }
}
