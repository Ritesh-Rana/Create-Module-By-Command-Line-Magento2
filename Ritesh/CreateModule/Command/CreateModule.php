<?php
namespace Ritesh\CreateModule\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateModule extends Command implements \Magento\Framework\ObjectManager\NoninterceptableInterface
{
    const VENDORNAME='Vendor Name';
    const MODULENAME='Module Name';
    protected function configure()
    {
        
		$this->setName('create:module')
			->setDescription('Create : Module --vendorName="" --moduleName="" ');

        $this->addArgument(
            self::VENDORNAME,
            InputArgument::REQUIRED,
            'Vendor Name'
        );
        $this->addArgument(
            self::MODULENAME,
            InputArgument::REQUIRED,
            'Module Name'
        );

		parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $Nvendor=$input->getArgument(self::VENDORNAME);
        $Nmodule=$input->getArgument(self::MODULENAME);
        
        $mkdir='app/code/'.$Nvendor.'/'.$Nmodule.'/etc';
        mkdir($mkdir,0775,true);

        $mxml=$mkdir.'/module.xml';
        touch($mxml);

        $xml='<?xml version="1.0"?>
    <config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
        <module name="'.$Nvendor."_".$Nmodule.'" setup_version="0.0.1">
        </module>
    </config>';
        $open=fopen($mxml,'a');
        fwrite($open,$xml);
        fclose($open);

        $mkdir='app/code/'.$Nvendor.'/'.$Nmodule;
        $registration=$mkdir.'/registration.php';
        touch($registration);

        $NameSpace=$Nvendor.'_'.$Nmodule;

        $regis="<?php
/*
 * @package        Ritesh_Rana
 * @author         Ritesh Rana
 */

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    '$NameSpace',
    __DIR__
);
";
        $open=fopen($registration,'a');
        fwrite($open,$regis);
        fclose($open);
        chmod($mkdir,0777);
        $output->writeln("Bingo ! Created new Module");
    }
}
