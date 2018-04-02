<?php

namespace App\Pagerfanta\Adapter;

use App\DTO\ProjectDto;
use App\Services\Redmine;
use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class ProjectAdapter implements AdapterInterface
{
    /**
     * @var Redmine
     */
    private $redmine;

    /**
     * @var DenormalizerInterface
     */
    private $serializer;

    public function __construct(Redmine $redmine, DenormalizerInterface $serializer)
    {
        $this->redmine = $redmine;
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->redmine->getProjectsCount();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        if (!$projectsData = $this->redmine->getProjects($offset, $length)) {
            return null;
        }

        return $this->serializer->denormalize($projectsData, ProjectDto::class.'[]');
    }
}
