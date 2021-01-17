<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CsvDataRepository")
 */
class CsvData
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $csv_filename;
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $csv_header;
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $csv_data;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $csv_type;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCsvFilename()
    {
        return $this->csv_filename;
    }

    /**
     * @param mixed $csv_filename
     */
    public function setCsvFilename($csv_filename): void
    {
        $this->csv_filename = $csv_filename;
    }

    /**
     * @return mixed
     */
    public function getCsvHeader()
    {
        return $this->csv_header;
    }

    /**
     * @param mixed $csv_header
     */
    public function setCsvHeader($csv_header): void
    {
        $this->csv_header = $csv_header;
    }

    /**
     * @return mixed
     */
    public function getCsvData()
    {
        return $this->csv_data;
    }

    /**
     * @param mixed $csv_data
     */
    public function setCsvData($csv_data): void
    {
        $this->csv_data = $csv_data;
    }

    /**
     * @return mixed
     */
    public function getCsvType()
    {
        return $this->csv_type;
    }

    /**
     * @param mixed $csv_type
     */
    public function setCsvType($csv_type): void
    {
        $this->csv_type = $csv_type;
    }

}
