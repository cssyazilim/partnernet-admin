<?php
require_once __DIR__ . '/../../config/auth.php';
$isAdmin = has_role(['admin', 'super_admin', 'merkez']);
$res = api_request('GET', API_CORE . '/quotes', ['query' => ['limit' => 10]]);
?>
<div class="card">
    <h2>Teklifler</h2>
    <?php if ($isAdmin): ?><a class="btn" href="#">+ Yeni Teklif</a><?php endif; ?>
    <?php if (!$res['ok']): ?>
        <div>Teklifler alınamadı (<?= h($res['status']) ?>)</div>
    <?php else: ?>
        <table width="100%" cellspacing="0" cellpadding="8" style="border-collapse:collapse">
            <thead>
                <tr style="text-align:left;border-bottom:1px solid #334155">
                    <th>ID</th>
                    <th>Müşteri</th>
                    <th>Durum</th>
                    <th>Tutar</th>
                    <th>Tarih</th><?php if ($isAdmin): ?><th>İşlem</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($res['data']['items'] ?? []) as $q): ?>
                    <tr style="border-bottom:1px solid #1f2937">
                        <td><?= h($q['id'] ?? '') ?></td>
                        <td><?= h($q['customer_name'] ?? '-') ?></td>
                        <td><?= h($q['status'] ?? '-') ?></td>
                        <td><?= h($q['total'] ?? '-') ?></td>
                        <td><?= h($q['created_at'] ?? '-') ?></td>
                        <?php if ($isAdmin): ?><td><a class="btn danger" href="#">Sil</a></td><?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>