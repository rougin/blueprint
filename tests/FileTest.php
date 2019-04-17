<?php

namespace Rougin\Blueprint;

/**
 * File Test
 *
 * @package Blueprint
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class FileTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests a newly created file.
     *
     * @return void
     */
    public function testNewFile()
    {
        $testFile = new \Rougin\Blueprint\Common\File('test.php');
        $contents = 'hello world!';

        $testFile->putContents($contents);
        $testFile->chmod(0777);

        $this->assertEquals($contents, $testFile->getContents());

        $testFile->close();

        unlink('test.php');
    }
}
