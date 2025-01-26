<?php

declare(strict_types=1);

namespace App\Workflow;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;
use Generator;
use Temporal\Activity\ActivityOptions;
use App\Activity\FileDownloadActivity;
use Temporal\Common\RetryOptions;
use Temporal\Workflow;

#[WorkflowInterface]
class FileDownloadWorkflow
{
    private $activity;

    public function __construct()
    {
        $this->activity = Workflow::newActivityStub(
            FileDownloadActivity::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(600)
        );
    }

    #[WorkflowMethod]
    public function download(string $url, string $destinationPath): Generator
    {
        return yield $this->activity->downloadFile($url, $destinationPath);
    }
}
