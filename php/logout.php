<?php
session_start();
session_destroy();
header("Location: ../index.html"); // cambia a tu login si es distinto
exit;