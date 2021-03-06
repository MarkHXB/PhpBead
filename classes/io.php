<?php
interface IFileIO
{
    function save($data);
    function load();
}

abstract class FileIO implements IFileIO
{
    protected $filepath;

    public function __construct($filename)
    {
        if (!is_readable($filename) || !is_writable($filename)) {
            throw new Exception("Data source ${filename} is invalid.");
        }
        $this->filepath = realpath($filename);
    }
}

class JsonIO extends FileIO
{
    public function load($assoc = true)
    {
        $file_content = file_get_contents($this->filepath);
        return json_decode($file_content, $assoc) ?: [];
    }

    public function save($data)
    {
        $json_content = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($this->filepath, $json_content);
    }
}

class SerializeIO extends FileIO
{
    public function load()
    {
        $file_content = file_get_contents($this->filepath);
        return unserialize($file_content) ?: [];
    }

    public function save($data)
    {
        $serialized_content = serialize($data);
        file_put_contents($this->filepath, $serialized_content);
    }
}
