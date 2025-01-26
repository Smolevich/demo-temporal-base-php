<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;
use App\Workflow\FileDownloadWorkflow;
use Temporal\Client\GRPC\ServiceClient;

// Создаем GRPC клиент
$serviceClient = ServiceClient::create('temporal:7233');

// Create Temporal client
$workflowClient = WorkflowClient::create($serviceClient);

// Configure workflow options
$workflowOptions = WorkflowOptions::new()
    ->withWorkflowId('file-download-' . uniqid())
    ->withTaskQueue('default');

// Start workflow
$workflow = $workflowClient->newWorkflowStub(
    FileDownloadWorkflow::class,
    $workflowOptions
);

// URL для скачивания
$url = "http://file-server:5000/download";

// Задаем путь для сохранения файла
$destinationPath = "copy_largefile.csv";  // Укажите путь, где вы хотите сохранить файл

// Start workflow asynchronously с передачей URL и destinationPath
$run = $workflowClient->start($workflow, 'download', $url, $destinationPath);

echo "Started workflow {$run->getExecution()->getID()}\n";
