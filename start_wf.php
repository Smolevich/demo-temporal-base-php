<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Temporal\Client\WorkflowClient;
use Temporal\Client\WorkflowOptions;
use App\Workflow\FileDownloadWorkflow;
use Temporal\Client\GRPC\ServiceClient;

// Create GRPC client
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

// URL for downloading
$url = "http://file-server:5000/download";

// Set the path for saving the file
$destinationPath = "copy_largefile.csv";  // Specify the path where you want to save the file

// Start workflow asynchronously with URL and destinationPath
$run = $workflowClient->start($workflow, $url, $destinationPath);

echo "Started workflow {$run->getExecution()->getID()}\n";
