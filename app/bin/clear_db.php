<?php
if (!defined('IN_SYSTEM'))
	define('IN_SYSTEM', true);
include __DIR__ . '/../../public/start.php';

Logging::basicConfig('DEBUG', ['file' => ROOT . '/log/clear_db.log']);

$base_model = new BaseModel();
$history_model = new HistoryModel();

$clear_tables = [
	'collection',
	'post',
	'discuss',
];

$lt_date = date("Y-m-d 00:00:00");
$last_day_date = date("Y-m-d", strtotime("-1 day"));

$clear_result = array();
foreach ($clear_tables as $clear_table) {
	echo "clear {$clear_table}..." . PHP_EOL;
	if ($clear_table == 'post')
		$where = "`published` < '{$lt_date}'";
	else if ($clear_table == 'discuss')
		$where = "`discuss_time` < '{$lt_date}'";
	else if ($clear_table == 'collection')
		$where = "`create_time` < '{$lt_date}'";
	$clear_count = $base_model->exec("delete from {$clear_table} where {$where}");
	$clear_result[$clear_table] = $clear_count;
	$output = str_pad(' ', 4) . $clear_table . " cleared " . $clear_result[$clear_table] . PHP_EOL;
	echo $output;
	Logging::debug($output);
}

$history_result = $history_model->insert([
	"collection" => $clear_result['collection'],
	"post" => $clear_result['post'],
	"discuss" => $clear_result['discuss'],
	"date" => $last_day_date
]);
$output = "记录历史执行结果(插入的id): {$history_result}" . PHP_EOL;
echo $output;
Logging::debug($output);
