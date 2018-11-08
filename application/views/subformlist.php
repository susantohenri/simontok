<li class="item" data-uuid="<?= $item['uuid'] ?>" data-child-controller="<?= $item['childController'] ?>" data-child-uuid="<?= $item['childUuid'] ?>" data-urutan="<?= $item['urutan'] ?>" data-parent="<?= $item['parent'] ?>">
    <div class="item-row">
        <div class="item-col fixed item-col-check">
            <label class="item-check" id="select-all-items">
              <i class="fa fa-plus-square expand-btn"></i>
              <span></span>
            </label>
        </div>
        <div class="item-col item-col-header fixed col-sm-9">
            <div>
                <a target="_blank" href="<?= site_url($current['controller']) . '/readList/' . $item['uuid'] ?>">
                  <?= "{$item['kode']} {$item['uraian']}" ?>
                </a>
                &nbsp;
                <a href="<?= site_url("Jabatan/assignment/{$current['controller']}/{$item['uuid']}") ?>" target="_blank"><i class="fa fa-unlock-alt"></i></a>
            </div>
        </div>
        <div class="item-col item-col-header text-right">
            <div>
                <span class="pagu"><?= $item['pagu'] ?></span>
            </div>
        </div>
        <div class="item-col item-col-header text-right">
            <div>
                <span class="realisasi"><?= $item['realisasi'] ?></span>
            </div>
        </div>
    </div>
</li>