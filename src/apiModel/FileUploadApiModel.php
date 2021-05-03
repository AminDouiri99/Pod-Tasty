<?php


namespace App\apiModel;
use Symfony\Component\Validator\Constraints as Assert;


class FileUploadApiModel
{
    /**
     * @Assert\NotBlank()
     */
    public $filename;
    /**
     * @Assert\NotBlank()
     */
    private $data;
    private $decodedData;
    public function setData(?string $data)
    {
        $this->data = $data;
        $this->decodedData = base64_decode($data);
    }
}