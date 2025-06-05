<?php
// Script de despliegue automático
$output = shell_exec('cd /home/plantifl/public_html/b/intranet && git pull 2>&1');
echo "<pre>$output</pre>";
?>
