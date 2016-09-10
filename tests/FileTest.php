<?php

namespace Rougin\Blueprint;

use Rougin\Blueprint\Common\File;

use PHPUnit_Framework_TestCase;

/**
 * File Test
 *
 * @package Blueprint
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class FileTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests a newly created file.
     *
     * @return void
     */
    public function testNewFile()
    {
        $testFile = new File('test.php');
        $contents = 'hello world!';

        $testFile->putContents($contents);
        $testFile->chmod(0777);

        $this->assertEquals($contents, $testFile->getContents());

        $testFile->close();

        unlink('test.php');
    }
}
