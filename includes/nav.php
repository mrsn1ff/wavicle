<?php foreach ($menuItems as $key => $item): ?>
    <li class="<?php echo isset($item['children']) ? 'dropdown' : ''; ?> <?php echo $activePage === $key ? 'current' : ''; ?>">
        <a href="<?php echo $item['href']; ?>"><?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?></a>
        <?php if (!empty($item['children'])): ?>
        <ul>
            <?php foreach ($item['children'] as $childKey => $child): ?>
            <li><a href="<?php echo $child['href']; ?>"><?php echo htmlspecialchars($child['label'], ENT_QUOTES, 'UTF-8'); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
