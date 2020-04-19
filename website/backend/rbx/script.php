<?php
    function get_loadstring($script, $times = 1)
    {
        $loadstring = 'loadstring("\\' . implode("\\", unpack("C*", $script)) . '")()';

        for ($i = 0; $i < $times - 1; $i++)
        {
            $loadstring = 'loadstring("\\' . implode("\\", unpack("C*", $loadstring)) . '")()';
        }

        return $loadstring;
    }
?>