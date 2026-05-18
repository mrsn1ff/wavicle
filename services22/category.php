<?php
header('Location: /wavicle/catalog-category.php?slug=' . urlencode($_GET['slug'] ?? ''));
exit;