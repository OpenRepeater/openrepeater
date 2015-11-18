<?php
$pinNo = '7';
$value = '1';	
	
//file_put_contents('/sys/class/gpio/gpio'.$pinNo.'/value', $value);

file_put_contents('/sys/class/gpio/export', '44');

echo "done";
//echo exec('echo 1 > /sys/class/gpio/gpio7/value');

//ob_start();
//passthru("sudo echo 44 > /sys/class/gpio/export");
//$test = ob_get_clean();
//echo $test;

/*        require_once('GPIO.php');

        echo "Setting up Pins 17 and 22\n";
        $gpio = new GPIO();
        $gpio->setup(48, "out");
*/
/*
        echo "Turning on Pins 17 and 22\n";
        $gpio->output(17, 1);
        $gpio->output(22, 1);

        echo "Sleeping!\n";
        sleep(3);

        echo "Turning off Pins 17 and 22\n";
        $gpio->output(17, 0);
        $gpio->output(22, 0);

        echo "Unexporting all pins\n";
        $gpio->unexportAll();
*/
?>