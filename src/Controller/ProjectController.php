<?php

namespace App\Controller;

use App\DTO\ProjectDto;
use App\Entity\Comment;
use App\Entity\TimeEntry;
use App\Form\CommentType;
use App\Form\TimeEntryType;
use App\Pagerfanta\Adapter\IssueAdapter;
use App\Pagerfanta\Adapter\ProjectAdapter;
use App\Repository\CommentRepository;
use App\Services\Redmine;
use Pagerfanta\Pagerfanta;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @Route("/projects")
 */
class ProjectController extends AbstractController
{
    /**
     * @Route("", defaults={"page": "1"}, name="project_index")
     * @Route("/page/{page}", defaults={"page": "1"}, requirements={"page": "[1-9]\d*"}, name="project_index_paginated")
     *
     * @param int            $page
     * @param ProjectAdapter $adapter
     *
     * @return Response
     */
    public function index(int $page, ProjectAdapter $adapter): Response
    {
        $paginator = new Pagerfanta($adapter);
        $paginator->setMaxPerPage(getenv('NUM_ITEMS'));
        $paginator->setCurrentPage($page);

        return $this->render('project/index.html.twig', [
            'projects' => $paginator,
        ]);
    }

    /**
     * @Route("/{identifier}", defaults={"page": "1"}, name="project_show")
     * @Route("/{identifier}/comments/page/{page}", defaults={"page": "1"}, requirements={"page": "[1-9]\d*"}, name="project_comment_index_paginated")
     *
     * @param string                $identifier
     * @param int                   $page
     * @param CommentRepository     $commentRepository
     * @param Redmine               $redmine
     * @param DenormalizerInterface $serializer
     *
     * @return Response
     */
    public function show(string $identifier, int $page, CommentRepository $commentRepository, Redmine $redmine, DenormalizerInterface $serializer): Response
    {
        if (!$projectData = $redmine->getProject($identifier)) {
            throw $this->createNotFoundException();
        }

        $project = $serializer->denormalize($projectData, ProjectDto::class);

        $comments = $commentRepository->findByProjectId($project->getid(), $page);

        $form = $this->createForm(CommentType::class);

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{identifier}/issues", defaults={"page": "1"}, name="project_issue_index")
     * @Route("/{identifier}/issues/page/{page}", defaults={"page": "1"}, requirements={"page": "[1-9]\d*"}, name="project_issue_index_paginated")
     *
     * @param string                $identifier
     * @param int                   $page
     * @param Redmine               $redmine
     * @param DenormalizerInterface $serializer
     *
     * @return Response
     */
    public function issueIndex(string $identifier, int $page, Redmine $redmine, DenormalizerInterface $serializer): Response
    {
        if (!$projectData = $redmine->getProject($identifier)) {
            throw $this->createNotFoundException();
        }

        $project = $serializer->denormalize($projectData, ProjectDto::class);

        $adapter = new IssueAdapter($redmine, $serializer, $project->getId());
        $paginator = new Pagerfanta($adapter);
        $paginator->setMaxPerPage(getenv('NUM_ITEMS'));
        $paginator->setCurrentPage($page);

        return $this->render('project/issue_index.html.twig', [
            'project' => $project,
            'issues' => $paginator,
        ]);
    }

    /**
     * @Route("/{identifier}/time-entries/new", name="project_time_entry_new")
     *
     * @param string                $identifier
     * @param Request               $request
     * @param Redmine               $redmine
     * @param DenormalizerInterface $serializer
     *
     * @return Response
     */
    public function timeEntryNew(string $identifier, Request $request, Redmine $redmine, DenormalizerInterface $serializer): Response
    {
        if (!$projectData = $redmine->getProject($identifier)) {
            throw $this->createNotFoundException();
        }

        $project = $serializer->denormalize($projectData, ProjectDto::class);

        $timeEntry = new TimeEntry();

        $form = $this->createForm(TimeEntryType::class, $timeEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $redmine->newTimeEntryPerProject($project->getId(), $timeEntry->getTime());

            return $this->redirectToRoute('project_show', ['identifier' => $project->getIdentifier()]);
        }

        return $this->render('project/time_entry_new.html.twig', [
            'project' => $project,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{identifier}/comments/new", name="project_comment_new")
     * @Method("POST")
     *
     * @param string                $identifier
     * @param Request               $request
     * @param CommentRepository     $commentRepository
     * @param Redmine               $redmine
     * @param DenormalizerInterface $serializer
     *
     * @return Response
     */
    public function commentNew(string $identifier, Request $request, CommentRepository $commentRepository, Redmine $redmine, DenormalizerInterface $serializer): Response
    {
        if (!$projectData = $redmine->getProject($identifier)) {
            throw $this->createNotFoundException();
        }

        $project = $serializer->denormalize($projectData, ProjectDto::class);

        $comment = new Comment();
        $comment->setProjectId($project->getId());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            return $this->redirectToRoute('project_show', ['identifier' => $project->getIdentifier()]);
        }

        $comments = $commentRepository->findByProjectId($project->getid());

        return $this->render('project/show.html.twig', [
            'project' => $project,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }
}
