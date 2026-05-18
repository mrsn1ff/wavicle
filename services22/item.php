<?php
header('Location: /wavicle/catalog-item.php?cat=' . urlencode($_GET['cat'] ?? '') . '&slug=' . urlencode($_GET['slug'] ?? ''));
exit;