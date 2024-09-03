<?php

namespace Rougin\Blueprint;

use Rougin\Blueprint\Common\File;

/**
 * @package Blueprint
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class FileTest extends Testcase
{
    /**
     * @return void
     */
    public function test_creating_new_file()
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
