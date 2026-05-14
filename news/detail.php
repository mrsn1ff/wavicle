<?php
header('Location: /wavicle_v5/news-detail.php?slug=' . urlencode($_GET['slug'] ?? ''));
exit;
