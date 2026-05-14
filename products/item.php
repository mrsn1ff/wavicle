<?php
header('Location: /wavicle_v5/catalog-item.php?cat=' . urlencode($_GET['cat'] ?? '') . '&slug=' . urlencode($_GET['slug'] ?? ''));
exit;
