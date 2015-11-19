<?php
        require_once('GPIO.php');

        echo "Setting up Pins 17 and 22\n";
        $gpio = new GPIO();
        $gpio->setup(17, "out");
        $gpio->setup(22, "out");

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

?>