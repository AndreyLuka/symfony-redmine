<?php

namespace App\Pagerfanta\Adapter;

use App\DTO\IssueDto;
use App\Services\Redmine;
use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IssueAdapter implements AdapterInterface
{
    /**
     * @var Redmine
     */
    private $redmine;

    /**
     * @var DenormalizerInterface
     */
    private $serializer;

    /**
     * @var int
     */
    private $projectId;

    public function __construct(Redmine $redmine, DenormalizerInterface $serializer, int $projectId)
    {
        $this->redmine = $redmine;
        $this->serializer = $serializer;
        $this->projectId = $projectId;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->redmine->getIssuesByProjectIdCount($this->projectId);
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        if (!$issuesData = $this->redmine->getIssuesByProjectId($this->projectId, $offset, $length)) {
            return null;
        }

        return $this->serializer->denormalize($issuesData, IssueDto::class.'[]');
    }
}
