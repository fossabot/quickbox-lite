<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/util.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/localize.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/info.system.php');

$sysMemInfo = SystemInfo::meminfo();

/**
 * @param int|float|string $percent
 *
 * @return string
 */
function get_ram_color($percent) {
    $percent = (float) $percent;
    if ($percent >= 90) {
        return 'progress-bar-danger';
    }
    if ($percent >= 70) {
        return 'progress-bar-warning';
    }

    return 'progress-bar-success';
}

$memTotal         = formatsize($sysMemInfo['MemTotal'], 3, 1);
$memUsed          = formatsize($sysMemInfo['MemUsed'], 3, 1);
$memFree          = formatsize($sysMemInfo['MemFree'], 3, 1);
$memCached        = formatsize($sysMemInfo['Cached'], 3, 1); // memory cache
$memBuffers       = formatsize($sysMemInfo['Buffers'], 3, 1); // buffer
$swapTotal        = formatsize($sysMemInfo['SwapTotal'], 3, 1);
$swapUsed         = formatsize($sysMemInfo['SwapUsed'], 3, 1);
$swapFree         = formatsize($sysMemInfo['SwapFree'], 3, 1);
$swapPercent      = number_format($sysMemInfo['SwapPercent'], 3);
$memRealUsed      = formatsize($sysMemInfo['MemRealUsed'], 3, 1); // Real memory usage
$memRealFree      = formatsize($sysMemInfo['MemRealFree'], 3, 1); // Real memory free
$memRealPercent   = number_format($sysMemInfo['MemRealPercent'], 3); // Real memory usage ratio
$memPercent       = number_format($sysMemInfo['MemPercent'], 3); // Total Memory Usage
$memCachedPercent = number_format($sysMemInfo['CachedPercent'], 3); // cache memory usage
?>

<div class="row">
  <!-- PHSYSICAL MEMORY USAGE -->
  <div class="col-sm-12">
    <!--div class="vertical-container"-->
      <p style="font-size:10px"><?php echo T('PHYSICAL_MEMORY_TITLE'); ?>: <?php echo "{$memPercent}"; ?>%<br/>
        <?php echo T('PHYSICAL_MEMORY_USED_TXT'); ?>: <font color='#eb4549'><?php echo "{$memUsed}"; ?></font>  | <?php echo T('PHYSICAL_MEMORY_IDLE_TXT'); ?>: <font color='#eb4549'><?php echo "{$memFree}"; ?></font>
      </p>
      <div class="progress progress-striped">
        <?php $ramcolor = get_ram_color($memPercent); ?>
        <div style="width:<?php echo "{$memPercent}"; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo "{$memPercent}"; ?>" role="progressbar" class="progress-bar <?php echo $ramcolor; ?>">
          <span class="sr-only"><?php echo "{$memPercent}"; ?>% <?php echo T('USED'); ?></span>
        </div>
      </div>
    <!--/div-->
  </div>
  <?php
  // Determine if the cache is zero, no display
  if ($memCached > 1e-5) { ?>
  <!-- CACHED MEMORY USAGE -->
  <div class="col-sm-12" style="padding-top:10px">
    <p style="font-size:10px"><?php echo T('CACHED_MEMORY_TITLE'); ?>: <?php echo "{$memCachedPercent}"; ?>%<br/>
      <?php echo T('CACHED_MEMORY_USAGE_TXT', ['cached' => $memCached]); ?> | <?php echo T('CACHED_MEMORY_BUFFERS_TXT', ['buffered' => $memBuffers]); ?></p>
    <div class="progress progress-striped">
      <?php $ramcolor = get_ram_color($memCachedPercent); ?>
      <div style="width:<?php echo "{$memCachedPercent}"; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo "{$memCachedPercent}"; ?>" role="progressbar" class="progress-bar <?php echo $ramcolor; ?>">
        <span class="sr-only"><?php echo "{$memCachedPercent}"; ?>% <?php echo T('USED'); ?></span>
      </div>
    </div>
  </div>
  <!-- REAL MEMORY USAGE -->
  <div class="col-sm-12" style="padding-top:10px">
    <p style="font-size:10px"><?php echo T('REAL_MEMORY_TITLE'); ?>: <?php echo "{$memRealPercent}"; ?>%<br/>
      <?php echo T('REAL_MEMORY_USAGE_TXT', ['used' => $memRealUsed]); ?> | <?php echo T('REAL_MEMORY_FREE_TXT', ['free' => $memRealFree]); ?></p>
    <div class="progress progress-striped">
    <?php $ramcolor = get_ram_color($memRealPercent); ?>
      <div style="width:<?php echo "{$memRealPercent}"; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo "{$memRealPercent}"; ?>" role="progressbar" class="progress-bar <?php echo $ramcolor; ?>">
        <span class="sr-only"><?php echo "{$memRealPercent}"; ?>% <?php echo T('USED'); ?></span>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php
  // If SWAP district judge is 0, no display
  if ($swapTotal > 1e-5) { ?>
  <!-- SWAP USAGE -->
  <div class="col-sm-12" style="padding-top:10px">
    <p style="font-size:10px">
      <?php echo T('SWAP_TITLE'); ?>: <?php echo "{$swapPercent}"; ?>%<br/>
      <?php echo T('SWAP_TOTAL_TXT'); ?>: <?php echo T('TOTAL_L', ['total' => $swapTotal]); ?> | <?php echo T('SWAP_USED_TXT', ['used' => $swapUsed]); ?> | <?php echo T('SWAP_IDLE_TXT', ['free' => $swapFree]); ?>
    </p>
    <div class="progress progress-striped">
      <?php $ramcolor = get_ram_color($swapPercent); ?>
      <div style="width:<?php echo "{$swapPercent}"; ?>%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="<?php echo "{$swapPercent}"; ?>" role="progressbar" class="progress-bar <?php echo $ramcolor; ?>">
        <span class="sr-only"><?php echo "{$swapPercent}"; ?>% <?php echo T('USED'); ?></span>
      </div>
    </div>
  </div>
  <?php } ?>
</div>
<hr />
<h3><?php echo T('TOTAL_RAM'); ?></h3>
<h4 class="nomargin"><?php echo $memTotal; ?>
  <button onclick="boxHandler(event)" data-package="mem" data-operation="clean" data-toggle="modal" data-target="#sysResponse" class="btn btn-xs btn-default pull-right"><?php echo T('CLEAR_CACHE'); ?></button>
</h4>
