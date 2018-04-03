<?php

namespace App\Services;

use Redmine\Client;

class Redmine
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return array|null
     */
    public function getProjects(int $offset = 0, int $limit = 1): ?array
    {
        $data = $this->client->project->all([
            'offset' => $offset,
            'limit' => $limit,
        ]);

        if (!array_key_exists('projects', $data)) {
            return null;
        }

        return $data['projects'];
    }

    /**
     * @return int|null
     */
    public function getProjectsCount(): ?int
    {
        $data = $this->client->project->all(['limit' => 1]);

        if (!array_key_exists('total_count', $data)) {
            return null;
        }

        return $data['total_count'];
    }

    /**
     * @param string $id
     *
     * @return array|null
     */
    public function getProject(string $id): ?array
    {
        if (!$data = $this->client->project->show($id)) {
            return null;
        }

        return $data['project'];
    }

    /**
     * @param int $projectId
     * @param int $offset
     * @param int $limit
     *
     * @return array|null
     */
    public function getIssuesByProjectId(int $projectId, int $offset = 0, int $limit = 1): ?array
    {
        $data = $this->client->issue->all([
            'project_id' => $projectId,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        if (!array_key_exists('issues', $data)) {
            return null;
        }

        return $data['issues'];
    }

    /**
     * @param int $projectId
     *
     * @return int|null
     */
    public function getIssuesByProjectIdCount(int $projectId): ?int
    {
        $data = $this->client->issue->all([
            'project_id' => $projectId,
            'limit' => 1,
        ]);

        if (!array_key_exists('total_count', $data)) {
            return null;
        }

        return $data['total_count'];
    }

    /**
     * @param string $projectId
     * @param int    $hours
     *
     * @return bool
     */
    public function newTimeEntryPerProject(string $projectId, int $hours): bool
    {
        $data = $this->client->time_entry->create([
            'project_id' => $projectId,
            'hours' => $hours,
        ]);

        if (!$data) {
            return false;
        }

        return true;
    }

    /**
     * @param int $issueId
     * @param int $hours
     *
     * @return bool
     */
    public function newTimeEntryPerIssue(int $issueId, int $hours): bool
    {
        $data = $this->client->time_entry->create([
            'issue_id' => $issueId,
            'hours' => $hours,
        ]);

        if (!$data) {
            return false;
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @return array|null
     */
    public function getIssue(int $id): ?array
    {
        if (!$data = $this->client->issue->show($id)) {
            return null;
        }

        return $data['issue'];
    }
}
