<?php
header('Location: /wavicle_v5/catalog-category.php?slug=' . urlencode($_GET['slug'] ?? ''));
exit;
