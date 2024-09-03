<?php

namespace Rougin\Blueprint\Common;

/**
 * @deprecated since ~0.4, use "League\Flysystem" instead.
 *
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class File
{
    /**
     * @var resource
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $path
     * @param string $mode
     */
    public function __construct($path, $mode = 'wb')
    {
        $this->path = $path;

        $this->file = fopen($path, $mode);
    }

    /**
     * Closes an open file pointer.
     *
     * @return boolean
     */
    public function close()
    {
        return fclose($this->file);
    }

    /**
     * Reads entire file into a string.
     *
     * @return string
     */
    public function getContents()
    {
        return file_get_contents($this->path);
    }

    /**
     * Writes a string to a file.
     *
     * @param string $content
     *
     * @return void
     */
    public function putContents($content)
    {
        file_put_contents($this->path, $content);
    }

    /**
     * Changes the file mode of the file.
     *
     * @param integer $mode
     *
     * @return boolean
     */
    public function chmod($mode)
    {
        return chmod($this->path, $mode);
    }
}
