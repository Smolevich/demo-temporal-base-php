<?php

require_once __DIR__ . '/vendor/autoload.php';

use Temporal\WorkerFactory;
use Temporal\Client\GRPC\ServiceClient;
use Temporal\Client\ClientOptions;
use Temporal\DataConverter\DataConverter;
use Temporal\Client\WorkflowClient;
use Temporal\Worker\WorkerOptions;
use Temporal\Worker\Transport\Goridge;
use Temporal\Activity\ActivityOptions;
use Spiral\Goridge\RPC\RPC;
use Spiral\Goridge\RPC\RPCConnection;
use Spiral\Goridge\Relay;
use App\Workflow\FileDownloadWorkflow;
use App\Activity\FileDownloadActivity;

// Настраиваем обработку ошибок
set_error_handler(function ($severity, $message, $file, $line) {
    fwrite(STDERR, sprintf("[%s] %s in %s:%d\n", date('Y-m-d H:i:s'), $message, $file, $line));
    return true;
});

try {
    // Получаем значения из переменных окружения
    $address = getenv('TEMPORAL_ADDRESS') ?: 'temporal:7233';
    $namespace = getenv('TEMPORAL_NAMESPACE') ?: 'default';

    // Создаем конвертер данных
    $dataConverter = DataConverter::createDefault();

    // Создаем RPC подключение через Goridge
    $relay = Relay::create('pipes');
    $rpc = Goridge::create();

    // Создаем фабрику с необходимыми параметрами
    $factory = new WorkerFactory(
        $dataConverter,
        $rpc
    );

    // Создаем опции для воркера
    $workerOptions = new WorkerOptions();

    // Создаем worker с опциями
    $worker = $factory->newWorker(
        taskQueue: 'default',
        options: $workerOptions
    );

    // Регистрируем workflow и activity
    $worker->registerWorkflowTypes(FileDownloadWorkflow::class);
    $worker->registerActivityImplementations(new FileDownloadActivity());

    // Запускаем worker
    $factory->run();
} catch (Throwable $e) {
    fwrite(STDERR, sprintf(
        "[%s] Error: %s\nStack trace:\n%s\n",
        date('Y-m-d H:i:s'),
        $e->getMessage(),
        $e->getTraceAsString()
    ));
    exit(1);
}
