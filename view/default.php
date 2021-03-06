
<?php if ($dm->getResponseMessage()) : ?>
    <div class="dm-response">
        <p><?= $dm->getResponseMessage(); ?></p>
    </div>
<?php endif; ?>

<form action="<?= $dm->getBaseUrl(); ?>" method="POST">
    <?php foreach (array('remote', 'local') as $env) : ?>
        <input type="hidden" name="site" value="<?= $dm->getSite(); ?>" />
        <div class="env env-<?= $env; ?>">
            <h2><?= $env; ?> config</h2>
            <?php foreach (array('db_host', 'db_name', 'db_uname') as $txtField) : ?>
                <?php
                if ($env === 'local' && $txtField === 'db_host') {
                    continue; //ahh yeah uuhh I guess we're not importing to somewhere else
                }
                ?>
                <div class="frm-field">
                    <label><?= $env; ?> <?= str_replace('_', ' ', $txtField); ?></label><br />
                    <input type="text" name="config[<?= $env; ?>][<?= $txtField; ?>]" value="<?= $dm->getConfig($env, $txtField); ?>" />
                </div>
            <?php endforeach; ?>
            <div class="frm-field">
                <label><?= $env; ?> db password</label><br />
                <input type="password" name="config[<?= $env; ?>][db_pass]" value="<?= $dm->getConfig($env, 'db_pass'); ?>" />
            </div>
        </div>
    <?php endforeach; ?>
    <div class="misc">
        <div class="frm-field">
            <label>exclude tables</label><br />
            <textarea name="config[misc][exclude_tables]"><?= 
                $dm->getConfig('misc', 'exclude_tables'); 
            ?></textarea>
            <small>Separate tables with newline</small>
        </div>
        <div class="frm-field">
            <label>additional SQL</label><br />
            <textarea name="config[misc][additional_sql]"><?= 
                $dm->getConfig('misc', 'additional_sql'); 
            ?></textarea><br />
            <small>Additional SQL commands to run after the database is imported</small>
        </div>
        <div class="frm-field">
            <label><input id="applyMagento" type="checkbox" name="config[misc][apply_magento_sql]" value="1"<?php echo ($dm->getConfig('misc', 'apply_magento_sql') == 1) ? ' checked="checked"' : ''; ?> /> Magento 1.x</label>
            <div id="showifmagento" <?php if ($dm->getConfig('misc','apply_magento_sql') != 1) { echo 'style="display:none;"'; } ?>>
                <small>The tool will run additional SQL to fix Magento DB dumps</small><br />
                <label>Dev site URL</label>
                <input type="text" name="config[misc][magento_siteurl]" value="<?= $dm->getConfig('misc', 'magento_siteurl'); ?>" />
                <small>Needs to include a trailing / </small>
                <br />
                <label>Table Prefix</label>
                <input type="text" name="config[misc][magento_tableprefix]" value="<?= $dm->getConfig('misc', 'magento_tableprefix'); ?>" />
            </div>
        </div>
        <div class="frm-field">
            <?php
            $checked = ($dm->getConfig('misc', 'delete_after_import') === 'y');
            ?>
            <input type="checkbox" name="config[misc][delete_after_import]" value="y"<?php if ($checked) echo ' checked="checked"'; ?> /> <label>Delete dump file after import</label>
        </div>
        <div class="frm-field">
            <?php $lastAction = $dm->getConfig('misc', 'last_action'); ?>
            <input type="radio" name="action" value="perform_dump"<?php if ($lastAction == 'perform_dump') echo ' checked="checked"'; ?> /><label>Dump and import</label><br />
            <input type="radio" name="action" value="perform_dump_nodata"<?php if ($lastAction == 'perform_dump_nodata') echo ' checked="checked"'; ?> /><label>Import schema only (no data)</label><br />
            <?php /*<input type="radio" name="action" value="save_dump"<?php if ($lastAction == 'save_dump') echo ' checked="checked"'; ?> /><label>Save dump only</label><br />*/?>
            <input type="radio" name="action" value="save_config"<?php if ($lastAction == 'save_config') echo ' checked="checked"'; ?> /><label>Save config only</label><br />
        </div>
    </div>
    <div class="misc">
        <h4><em>This utility was not built for the wild. By using this tool you agree to never upload it to a public-facing server.</em></h4>
        <button type="submit">Go</button>
    </div>
</form>
<script>
    
    document.getElementById('applyMagento').addEventListener('click', function(){
        if (document.getElementById('applyMagento').checked === true) {
            document.getElementById('showifmagento').style.display = 'block';
        } else {
            document.getElementById('showifmagento').style.display = 'none';
        }
    });
</script>
