<?php
namespace Tests\Rcnchris\Core\Tools;

use Rcnchris\Core\Tools\Cmd;
use Tests\Rcnchris\BaseTestCase;

class CmdTest extends BaseTestCase {

    /**
     * @var Cmd
     */
    private $cmd;

    public function setUp()
    {
        $this->cmd = $this->makeCmd();
    }

    /**
     * Obtenir une instance de Cmd
     *
     * @return Cmd
     */
    public function makeCmd()
    {
        return new Cmd();
    }

    public function testInstance()
    {
        $this->ekoTitre('Tools - Shell');
        $this->assertInstanceOf(Cmd::class, $this->cmd);
    }

    public function testExec()
    {
        $path = $this->rootPath();
        $cmd = $this->makeCmd();
        $ret = $cmd->exec("cd $path && ls");
        $this->assertCount(1, $cmd->getCmds());
        $this->assertCount(1, $cmd->getCmds(true));
        $this->assertArrayHasKey('cmd', $cmd->getCmds()[0]);
        $this->assertArrayHasKey('time', $cmd->getCmds()[0]);
        $this->assertArrayHasKey('result', $cmd->getCmds()[0]);
        $this->assertArrayHasKey('ret', $cmd->getCmds()[0]);

        $this->assertContains('README.md', $cmd->getCmds(true)[0]);
    }

    public function testExecWrongCommand()
    {
        ob_start();
        $c = $this->makeCmd();
        $c->exec('lll');
        $this->assertEquals(1, count($c->getCmds()));
        $this->assertArrayHasKey('cmd', $c->getCmds()[0]);
        $content = ob_get_clean();
    }

    public function testExecMultipleCommands()
    {
        $this->assertInternalType('array', Cmd::exec('ls && pwd'));
    }

    public function testExecMultipleCommandsSeparate()
    {
        $path = dirname(__DIR__);
        $c = $this->makeCmd();
        $ret = $c->exec("cd $path && ls", true);
        $this->assertEquals(2, count($c->getCmds()));
        $this->assertEquals(2, count($c->getCmds(true)));
    }

    public function testGetCommands()
    {
        $this->cmd->exec('ls && pwd');
        $this->assertNotEmpty($this->cmd->getCmds());
    }

    public function testGetGitVersion()
    {
        $this->assertInternalType('string', $this->cmd->git());
    }

    public function testGetSizeWithDir()
    {
        $this->assertEquals('git version', substr($this->makeCmd()->git(), 0, 11));
    }

    public function testGetSizeWithFile()
    {
        $this->assertInternalType('integer', $this->cmd->size(__FILE__));
    }

    public function testGetSizeWithoutParameter()
    {
        $this->assertInternalType('integer', $this->cmd->size());
    }

    public function testGetSizeHuman()
    {
        $this->assertInternalType('string', $this->cmd->size(null, true));
    }

    public function testGetSizeGetObject()
    {
        $this->assertInstanceOf(\stdClass::class, $this->cmd->size(null, null, true));
    }

    public function testGetSizeFile()
    {
        $this->assertInstanceOf(\stdClass::class, $this->cmd->size(__FILE__, null, true));
    }

    public function testGetSizeFileHuman()
    {
        $this->assertInstanceOf(\stdClass::class, $this->cmd->size(__FILE__, true, true));
    }
}
