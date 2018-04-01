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
     * @param string $url
     * @param string $apiKey
     */
    public function __construct(string $url, string $apiKey)
    {
        $this->url = $url;
        $this->apiKey = $apiKey;
        $this->client = new Client($url, $apiKey);
    }

    /**
     * @throws \Exception
     *
     * @return array|null
     */
    public function getProjects(): ?array
    {
        $data = $this->client->project->all();

        if (!array_key_exists('projects', $data)) {
            return null;
        }

        return $data['projects'];
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
     * @param string $projectId
     *
     * @return array|null
     */
    public function getIssuesByProjectId(string $projectId): ?array
    {
        $data = $this->client->issue->all(['project_id' => $projectId]);

        if (!array_key_exists('issues', $data)) {
            return null;
        }

        return $data['issues'];
    }

    /**
     * @param string $projectId
     * @param int    $hours
     */
    public function newTimeEntryPerProject(string $projectId, int $hours): void
    {
        $this->client->time_entry->create([
            'project_id' => $projectId,
            'hours' => $hours,
        ]);
    }

    /**
     * @param int $issueId
     * @param int $hours
     */
    public function newTimeEntryPerIssue(int $issueId, int $hours): void
    {
        $this->client->time_entry->create([
            'issue_id' => $issueId,
            'hours' => $hours,
        ]);
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
